<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Resources\CitaResource;
use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    /**
     * Devuelve todas las citas visibles para el usuario actual.
     * - Admin: todas las citas
     * - Empleado: solo sus citas
     * - Cliente: solo sus propias citas
     */
    public function index()
    {
        $usuario = Auth::user();
        $consulta = Cita::with(['servicio', 'empleado.usuario', 'estado', 'usuario']);

        if (!$usuario->isAdmin()) {
            if ($usuario->isEmployee() && $usuario->empleado) {
                $consulta->where('id_empleado', $usuario->empleado->id);
            } else {
                $consulta->where('user_id', $usuario->id);
            }
        }

        $citas = $consulta->get();
        return CitaResource::collection($citas);
    }

    /**
     * Crea una nueva cita validando disponibilidad.
     */
    public function store(StoreCitaRequest $request)
    {
        $usuario = Auth::user();
        $datos = $request->validated();

        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);
        $fecha = $datos['fecha'];
        $horaInicio = $datos['hora_inicio'];

        $this->verificarDisponibilidad($empleado, $servicio, $fecha, $horaInicio);

        $idEstado = $datos['id_estado'] ?? Estado::where('nombre_estado', 'Pendiente')->first()?->id ?? Estado::first()?->id;
        $idUsuario = isset($datos['user_id']) && $usuario->isAdmin() ? $datos['user_id'] : $usuario->id;

        $cita = Cita::create([
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'user_id' => $idUsuario,
            'id_servicio' => $servicio->id,
            'id_empleado' => $empleado->id,
            'id_estado' => $idEstado,
        ]);

        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);

        return response()->json([
            'message' => 'Cita creada correctamente',
            'data' => new CitaResource($cita)
        ], 201);
    }

    /**
     * Devuelve los datos de una cita específica.
     */
    public function show(Cita $cita)
    {
        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);
        return new CitaResource($cita);
    }

    /**
     * Actualiza una cita existente validando permisos y disponibilidad.
     */
    public function update(StoreCitaRequest $request, Cita $cita)
    {
        $usuario = Auth::user();
        if (!$usuario->isAdmin() && $cita->user_id !== $usuario->id) {
            return response()->json(['message' => 'No tienes permiso para modificar esta cita.'], 403);
        }

        $datos = $request->validated();
        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);
        $fecha = $datos['fecha'];
        $horaInicio = $datos['hora_inicio'];

        $this->verificarDisponibilidad($empleado, $servicio, $fecha, $horaInicio, $cita->id);

        $idUsuario = isset($datos['user_id']) && $usuario->isAdmin() ? $datos['user_id'] : $cita->user_id;
        $idEstado = $datos['id_estado'] ?? $cita->id_estado;

        $cita->update([
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'user_id' => $idUsuario,
            'id_servicio' => $servicio->id,
            'id_empleado' => $empleado->id,
            'id_estado' => $idEstado,
        ]);

        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'data' => new CitaResource($cita)
        ], 200);
    }

    /**
     * Elimina una cita validando permisos.
     */
    public function destroy(Cita $cita)
    {
        $usuario = Auth::user();
        if (!$usuario->isAdmin() && $cita->user_id !== $usuario->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta cita.'], 403);
        }

        $cita->delete();
        return response()->json(['message' => 'Cita eliminada correctamente'], 200);
    }

    /**
     * Devuelve los horarios disponibles para un empleado y servicio en una fecha.
     */
    public function disponibilidad(Request $request)
    {
        $datos = $request->validate([
            'id_servicio' => 'required|exists:servicios,id',
            'id_empleado' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
        ]);

        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);
        $fecha = $datos['fecha'];

        $horarios = $this->obtenerHorariosDisponibles($empleado, $servicio, $fecha);

        return response()->json([
            'disponibilidad' => $horarios,
            'servicio' => [
                'id' => $servicio->id,
                'nombre' => $servicio->nombre_servicio,
                'duracion' => $servicio->duracion,
                'precio' => $servicio->precio,
            ],
            'empleado' => [
                'id' => $empleado->id,
                'nombre' => $empleado->usuario?->nombre,
            ],
        ], 200);
    }

    // ------------------ Métodos privados ------------------

    /**
     * Verifica si el horario está disponible.
     */
    private function verificarDisponibilidad(Empleado $empleado, Servicio $servicio, $fecha, $horaInicio, $ignoreId = null)
    {
        $horario = $this->obtenerHorarioDelDia($empleado, $fecha);
        if (!$horario) abort(422, 'El empleado no trabaja ese día.');

        if (!$this->espacioDisponible($empleado, $servicio, $fecha, $horaInicio, $ignoreId)) {
            abort(422, 'El horario seleccionado no está disponible.');
        }
    }

    /**
     * Obtiene el horario del empleado para un día específico.
     */
    private function obtenerHorarioDelDia(Empleado $empleado, $fecha)
    {
        $diaSemana = $this->obtenerDiaSemana($fecha);
        return $empleado->horarios()->where('dia_semana', $diaSemana)->first();
    }

    /**
     * Calcula los horarios libres dentro del horario del empleado.
     */
    private function obtenerHorariosDisponibles(Empleado $empleado, Servicio $servicio, $fecha, $paso = 20)
    {
        $horario = $this->obtenerHorarioDelDia($empleado, $fecha);
        if (!$horario) return [];

        $inicio = strtotime($fecha . ' ' . $horario->hora_inicio);
        $fin = strtotime($fecha . ' ' . $horario->hora_fin);
        $duracionSegundos = (int)$servicio->duracion * 60;

        $horarios = [];
        for ($hora = $inicio; $hora + $duracionSegundos <= $fin; $hora += $paso * 60) {
            $horaFormateada = date('H:i', $hora);
            if ($this->espacioDisponible($empleado, $servicio, $fecha, $horaFormateada)) {
                $horarios[] = $horaFormateada;
            }
        }

        return array_values(array_unique($horarios));
    }

    /**
     * Comprueba si un horario específico está libre y dentro del horario.
     */
    private function espacioDisponible(Empleado $empleado, Servicio $servicio, $fecha, $horaInicio, $ignoreId = null)
    {
        $horario = $this->obtenerHorarioDelDia($empleado, $fecha);
        if (!$horario) return false;

        $inicioCita = strtotime($fecha . ' ' . $horaInicio);
        $finCita = $inicioCita + ((int)$servicio->duracion * 60);

        $inicioHorario = strtotime($fecha . ' ' . $horario->hora_inicio);
        $finHorario = strtotime($fecha . ' ' . $horario->hora_fin);

        if ($inicioCita < $inicioHorario || $finCita > $finHorario) return false;

        foreach ($this->obtenerTiemposOcupados($empleado, $fecha, $ignoreId) as $tiempo) {
            if ($inicioCita < $tiempo['end'] && $finCita > $tiempo['start']) return false;
        }

        return true;
    }

    /**
     * Devuelve los intervalos ocupados por otras citas.
     */
    private function obtenerTiemposOcupados(Empleado $empleado, $fecha, $ignoreId = null)
    {
        $consulta = Cita::with('servicio')->where('id_empleado', $empleado->id)->where('fecha', $fecha);
        if ($ignoreId) $consulta->where('id', '!=', $ignoreId);

        $tiempos = [];
        foreach ($consulta->get() as $cita) {
            $inicio = strtotime($fecha . ' ' . $cita->hora_inicio);
            $fin = $inicio + ((int)$cita->servicio->duracion * 60);
            $tiempos[] = ['start' => $inicio, 'end' => $fin];
        }

        return $tiempos;
    }

    /**
     * Convierte una fecha al nombre del día de la semana.
     */
    private function obtenerDiaSemana($fecha)
    {
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $dias[date('w', strtotime($fecha))];
    }
}