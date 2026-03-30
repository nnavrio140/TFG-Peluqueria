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
     * Devuelve todas las citas que puede ver el usuario.
     * - Admin: todas las citas.
     * - Empleado: solo sus citas.
     * - Cliente: solo sus propias citas.
     */
    public function index()
    {
        $usuarioActual = Auth::user();
        $consulta = Cita::with(['servicio', 'empleado.usuario', 'estado', 'usuario']);

        if (!$usuarioActual->isAdmin()) {
            if ($usuarioActual->isEmployee()) {
                $empleado = $usuarioActual->empleado;
                if ($empleado) {
                    $consulta->where('id_empleado', $empleado->id);
                } else {
                    // Si no existe el empleado, no mostrará citas.
                    $consulta->where('id_empleado', 0);
                }
            } else {
                $consulta->where('user_id', $usuarioActual->id);
            }
        }

        $citas = $consulta->get();
        return CitaResource::collection($citas);
    }

    /**
     * Crea una cita nueva.
     * Valida datos y comprueba que el horario está libre antes de guardar.
     */
    public function store(StoreCitaRequest $request)
    {
        $usuarioActual = Auth::user();
        $datos = $request->validated();

        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);
        $fecha = $datos['fecha'];
        $horaInicio = $datos['hora_inicio'];

        $this->verificarDisponibilidad($empleado, $servicio, $fecha, $horaInicio);

        if (isset($datos['id_estado'])) {
            $idEstado = $datos['id_estado'];
        } else {
            $estadoPendiente = Estado::where('nombre_estado', 'Pendiente')->first();
            if ($estadoPendiente) {
                $idEstado = $estadoPendiente->id;
            } else {
                $primerEstado = Estado::first();
                $idEstado = $primerEstado ? $primerEstado->id : null;
            }
        }

        if (isset($datos['user_id']) && $usuarioActual->isAdmin()) {
            $idUsuario = $datos['user_id'];
        } else {
            $idUsuario = $usuarioActual->id;
        }

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
     * Muestra una cita concreta.
     */
    public function show(Cita $cita)
    {
        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);
        return new CitaResource($cita);
    }

    /**
     * Actualiza una cita ya existente.
     * Solo el admin o el propietario de la cita pueden hacerlo.
     */
    public function update(StoreCitaRequest $request, Cita $cita)
    {
        $usuarioActual = Auth::user();

        if (!$usuarioActual->isAdmin() && $cita->user_id !== $usuarioActual->id) {
            return response()->json(['message' => 'No tienes permiso para modificar esta cita.'], 403);
        }

        $datos = $request->validated();
        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);
        $fecha = $datos['fecha'];
        $horaInicio = $datos['hora_inicio'];

        $this->verificarDisponibilidad($empleado, $servicio, $fecha, $horaInicio, $cita->id);

        if (isset($datos['user_id']) && $usuarioActual->isAdmin()) {
            $idUsuario = $datos['user_id'];
        } else {
            $idUsuario = $cita->user_id;
        }

        if (isset($datos['id_estado'])) {
            $idEstado = $datos['id_estado'];
        } else {
            $idEstado = $cita->id_estado;
        }

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
     * Elimina una cita.
     */
    public function destroy(Cita $cita)
    {
        $usuarioActual = Auth::user();

        if (!$usuarioActual->isAdmin() && $cita->user_id !== $usuarioActual->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta cita.'], 403);
        }

        $cita->delete();

        return response()->json(['message' => 'Cita eliminada correctamente'], 200);
    }

    /**
     * Devuelve las horas libres para un servicio y un empleado en una fecha.
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
                'nombre' => $empleado->usuario ? $empleado->usuario->nombre : null,
            ],
        ], 200);
    }

    /**
     * Verifica si un hueco de horaInicio es válido y libre.
     */
    private function verificarDisponibilidad(Empleado $empleado, Servicio $servicio, $fecha, $horaInicio, $ignoreId = null)
    {
        $horario = $this->obtenerHorarioDelDia($empleado, $fecha);

        if (!$horario) {
            abort(422, 'El empleado no trabaja ese día.');
        }

        $disponible = $this->espacioDisponible($empleado, $servicio, $fecha, $horaInicio, $ignoreId);

        if (!$disponible) {
            abort(422, 'El horario seleccionado no está disponible.');
        }
    }

    /**
     * Busca el horario del empleado para el día de la semana de la fecha.
     */
    private function obtenerHorarioDelDia(Empleado $empleado, $fecha)
    {
        $diaSemana = $this->obtenerDiaSemana($fecha);
        return $empleado->horarios()->where('dia_semana', $diaSemana)->first();
    }

    /**
     * Calcula los horarios posibles dentro del horario del empleado.
     * El paso se queda por defecto en 20 minutos.
     *
     * Detalle:
     * - 'strtotime' convierte una fecha+hora a un número en segundos.
     * - 'duracionSegundos' es la duración del servicio en segundos.
     * - El bucle avanza 20 minutos cada vez: 20 * 60 segundos, esto sireve para calcular los horarios disponibles.
     * - Solo devuelve las horas que están libres según espacioDisponible().
     */
    private function obtenerHorariosDisponibles(Empleado $empleado, Servicio $servicio, $fecha, $paso = 20)
    {
        $horario = $this->obtenerHorarioDelDia($empleado, $fecha);
        if (!$horario) {
            return [];
        }

        // strtotime devuelve segundos desde 1970.
        $inicio = strtotime($fecha . ' ' . $horario->hora_inicio);
        $fin = strtotime($fecha . ' ' . $horario->hora_fin);

        // La duración del servicio está en minutos.
        // * 60 convierte minutos a segundos porque las comparaciones se hacen en segundos.
        $duracionSegundos = (int) $servicio->duracion * 60;

        $horarios = [];
        $hora = $inicio;

        while ($hora + $duracionSegundos <= $fin) {
            $horaFormateada = date('H:i', $hora);
            if ($this->espacioDisponible($empleado, $servicio, $fecha, $horaFormateada)) {
                $horarios[] = $horaFormateada;
            }
            // Avanzamos el reloj en el bucle cada 20 minutos.
            // 20 minutos = 20 * 60 segundos.
            $hora = $hora + ($paso * 60);
        }

        return array_values(array_unique($horarios));
    }

    /**
     * Comprueba si el hueco está dentro del horario y no choca con otras citas.
     *
     * Detalle:
     * - inicioCita: cuando empieza la nueva cita, en segundos.
     * - finCita: cuando termina la nueva cita, en segundos.
     * - inicioHorario / finHorario: el horario del empleado, en segundos.
     * - Si la cita empieza antes o termina después del horario, no sirve.
     * - Luego revisa si se cruza con alguna cita ya guardada.
     */
    private function espacioDisponible(Empleado $empleado, Servicio $servicio, $fecha, $horaInicio, $ignoreId = null)
    {
        $horario = $this->obtenerHorarioDelDia($empleado, $fecha);
        if (!$horario) {
            return false;
        }

        // Hora de inicio de la nueva cita en segundos.
        $inicioCita = strtotime($fecha . ' ' . $horaInicio);
        // Fin de la nueva cita = inicio + duración del servicio.
        // El servicio está en minutos, por eso multiplicamos por 60.
        $finCita = $inicioCita + ((int) $servicio->duracion * 60);

        // Convertimos horario del empleado a segundos.
        $inicioHorario = strtotime($fecha . ' ' . $horario->hora_inicio);
        $finHorario = strtotime($fecha . ' ' . $horario->hora_fin);

        if ($inicioCita < $inicioHorario || $finCita > $finHorario) {
            return false;
        }

        $tiemposOcupados = $this->obtenerTiemposOcupados($empleado, $fecha, $ignoreId);
        foreach ($tiemposOcupados as $tiempo) {
            // Si la nueva cita se solapa con una cita ocupada.
            if ($inicioCita < $tiempo['end'] && $finCita > $tiempo['start']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Devuelve los intervalos ocupados por citas del empleado en la fecha.
     * ignoreId evita comparar con la propia cita cuando se actualiza.
     */
    private function obtenerTiemposOcupados(Empleado $empleado, $fecha, $ignoreId = null)
    {
        $consulta = Cita::with('servicio');
        $consulta = $consulta->where('id_empleado', $empleado->id);
        $consulta = $consulta->where('fecha', $fecha);

        if ($ignoreId != null) {
            $consulta = $consulta->where('id', '!=', $ignoreId);
        }

        $citas = $consulta->get();
        $tiempos = [];

        foreach ($citas as $cita) {
            $inicio = strtotime($fecha . ' ' . $cita->hora_inicio);
            $fin = $inicio + ((int) $cita->servicio->duracion * 60);
            $tiempos[] = ['start' => $inicio, 'end' => $fin];
        }

        return $tiempos;
    }

    /**
     * Convierte una fecha en el nombre del día de la semana.
     */
    private function obtenerDiaSemana($fecha)
    {
        $numeroDia = date('w', strtotime($fecha));
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $dias[$numeroDia];
    }
}
