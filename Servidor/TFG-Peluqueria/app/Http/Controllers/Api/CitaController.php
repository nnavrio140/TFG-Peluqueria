<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Resources\CitaResource;
use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\Festivo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $consulta = Cita::with([
            'servicio',
            'empleado.usuario',
            'estado',
            'usuario'
        ]);

        if (!$usuario->isAdmin()) {
            if ($usuario->isEmployee() && $usuario->empleado) {
                $consulta->where('empleado_id', $usuario->empleado->id);
            } else {
                $consulta->where('user_id', $usuario->id);
            }
        }

        $citas = $consulta->get()->each(fn ($cita) => $this->sincronizarEstadoAutomatico($cita));

        return CitaResource::collection($citas);
    }

    private function obtenerEstadoId(string $slug)
    {
        return Estado::where('slug', $slug)->first()?->id;
    }

    private function sincronizarEstadoAutomatico(Cita $cita)
    {
        if (!$cita->estado) {
            $cita->load('estado');
        }

        $fechaHoraFin = Carbon::parse($cita->fecha . ' ' . $cita->hora_fin);
        $estadoSlug = $fechaHoraFin->isPast() ? 'completada' : 'confirmada';

        if ($cita->estado?->slug !== $estadoSlug) {
            $nuevoEstadoId = $this->obtenerEstadoId($estadoSlug);
            if ($nuevoEstadoId) {
                $cita->estado_id = $nuevoEstadoId;
                $cita->save();
                $cita->load('estado');
            }
        }
    }

    public function store(StoreCitaRequest $request)
    {
        $usuario = Auth::user();
        $datos = $request->validated();

        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);

        $fecha = $datos['fecha'];
        $horaInicio = $datos['hora_inicio'];

        $this->verificarDisponibilidad($empleado, $servicio, $fecha, $horaInicio);

        $idUsuario = isset($datos['user_id']) && $usuario->isAdmin()
            ? $datos['user_id']
            : $usuario->id;

        $horaFin = Carbon::createFromFormat('H:i', $horaInicio)
            ->addMinutes($servicio->duracion)
            ->format('H:i:s');

        $idEstado = $this->obtenerEstadoId('confirmada')
            ?? Estado::where('nombre_estado', 'Confirmada')->first()?->id
            ?? Estado::first()?->id;

        $cita = Cita::create([
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'user_id' => $idUsuario,
            'servicio_id' => $servicio->id,
            'empleado_id' => $empleado->id,
            'estado_id' => $idEstado,
        ]);

        $this->sincronizarEstadoAutomatico($cita);

        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);

        return response()->json([
            'message' => 'Cita creada correctamente',
            'data' => new CitaResource($cita)
        ], 201);
    }

    public function show(Cita $cita)
    {
        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);
        $this->sincronizarEstadoAutomatico($cita);

        return new CitaResource($cita);
    }

    public function update(StoreCitaRequest $request, Cita $cita)
    {
        $usuario = Auth::user();

        $puedeEditar = $usuario->isAdmin() || $cita->user_id === $usuario->id ||
            ($usuario->isEmployee() && $usuario->empleado && $cita->empleado_id === $usuario->empleado->id);

        if (!$puedeEditar) {
            return response()->json([
                'message' => 'No tienes permiso para modificar esta cita.'
            ], 403);
        }

        $datos = $request->validated();

        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);

        $fecha = $datos['fecha'];
        $horaInicio = $datos['hora_inicio'];

        $this->verificarDisponibilidad($empleado, $servicio, $fecha, $horaInicio, $cita->id);

        $idUsuario = isset($datos['user_id']) && $usuario->isAdmin()
            ? $datos['user_id']
            : $cita->user_id;

        $horaFin = Carbon::createFromFormat('H:i', $horaInicio)
            ->addMinutes($servicio->duracion)
            ->format('H:i:s');

        $estadoSlug = Carbon::parse($fecha . ' ' . $horaFin)->isPast()
            ? 'completada'
            : 'confirmada';

        $idEstado = $this->obtenerEstadoId($estadoSlug)
            ?? Estado::where('nombre_estado', ucfirst($estadoSlug))->first()?->id
            ?? $cita->id_estado;

        $cita->update([
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'user_id' => $idUsuario,
            'servicio_id' => $servicio->id,
            'empleado_id' => $empleado->id,
            'estado_id' => $idEstado,
        ]);

        $cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']);
        $this->sincronizarEstadoAutomatico($cita);

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'data' => new CitaResource($cita)
        ]);
    }

    public function destroy(Cita $cita)
    {
        $usuario = Auth::user();

        $puedeEliminar = $usuario->isAdmin() || $cita->user_id === $usuario->id ||
            ($usuario->isEmployee() && $usuario->empleado && $cita->empleado_id === $usuario->empleado->id);

        if (!$puedeEliminar) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar esta cita.'
            ], 403);
        }

        $cita->delete();

        return response()->json([
            'message' => 'Cita eliminada correctamente'
        ]);
    }

    public function disponibilidad(Request $request)
    {
        $datos = $request->validate([
            'id_servicio' => 'required|exists:servicios,id',
            'id_empleado' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
        ]);

        $servicio = Servicio::findOrFail($datos['id_servicio']);
        $empleado = Empleado::findOrFail($datos['id_empleado']);

        $horarios = $this->obtenerHorariosDisponibles(
            $empleado,
            $servicio,
            $datos['fecha']
        );

        return response()->json([
            'disponibilidad' => $horarios,
        ]);
    }

public function diasDisponibles(Request $request)
{
    $datos = $request->validate([
        'id_servicio' => 'required|exists:servicios,id',
        'id_empleado' => 'required|exists:empleados,id',
    ]);

    $servicio = Servicio::findOrFail($datos['id_servicio']);
    $empleado = Empleado::findOrFail($datos['id_empleado']);

    $diasDisponibles = [];

    // Buscar disponibilidad durante los próximos 365 días
    for ($i = 0; $i < 365; $i++) {

        $fecha = now()->addDays($i)->format('Y-m-d');

        $horarios = $this->obtenerHorariosDisponibles(
            $empleado,
            $servicio,
            $fecha
        );

        if (!empty($horarios)) {
            $diasDisponibles[] = $fecha;
        }
    }

    return response()->json([
        'dias' => $diasDisponibles
    ]);
}

    // =========================
    // 🔥 MÉTODOS PRIVADOS
    // =========================

    private function verificarDisponibilidad(
        Empleado $empleado,
        Servicio $servicio,
        $fecha,
        $horaInicio,
        $ignoreId = null
    ) {
        $horarios = $this->obtenerHorariosDelDia($empleado, $fecha);

        if ($horarios->isEmpty()) {
            abort(422, 'El empleado no trabaja ese día.');
        }

        if (strtotime($fecha . ' ' . $horaInicio) < now()->timestamp) {
            abort(422, 'No puedes reservar en una fecha pasada.');
        }

        if (!$this->espacioDisponible($empleado, $servicio, $fecha, $horaInicio, $ignoreId)) {
            abort(422, 'El horario seleccionado no está disponible.');
        }
    }

    private function obtenerHorariosDelDia(Empleado $empleado, $fecha)
    {
        if ($this->estaFestivo($fecha)) {
            return collect();
        }

        $diaSemana = $this->obtenerDiaSemana($fecha);

        return $empleado->horarios()
            ->where('dia_semana', $diaSemana)
            ->get();
    }

    private function obtenerDiaSemana($fecha)
    {
        $diasSemana = [
            'domingo',
            'lunes',
            'martes',
            'miercoles',
            'jueves',
            'viernes',
            'sabado',
        ];

        return $diasSemana[Carbon::parse($fecha)->dayOfWeek];
    }

    private function estaFestivo($fecha)
    {
        $fechaFormateada = date('Y-m-d', strtotime($fecha));

        return Festivo::activos()->get()->contains(function ($festivo) use ($fechaFormateada) {

            if ($festivo->fecha === $fechaFormateada) {
                return true;
            }

            if (
                $festivo->recurrente &&
                date('m-d', strtotime($festivo->fecha)) === date('m-d', strtotime($fechaFormateada))
            ) {
                return true;
            }

            return false;
        });
    }

    private function obtenerHorariosDisponibles(
        Empleado $empleado,
        Servicio $servicio,
        $fecha
    ) {
        $horariosDelDia = $this->obtenerHorariosDelDia($empleado, $fecha);

        if ($horariosDelDia->isEmpty()) {
            return [];
        }

        $duracion = (int) $servicio->duracion * 60;
        $paso = max((int) $servicio->duracion, 1);

        $horarios = [];

        foreach ($horariosDelDia as $horario) {
            $inicio = strtotime($fecha . ' ' . $horario->hora_inicio);
            $fin = strtotime($fecha . ' ' . $horario->hora_fin);

            for ($hora = $inicio; $hora + $duracion <= $fin; $hora += $paso * 60) {
                $horaFormateada = date('H:i', $hora);

                if ($this->espacioDisponible($empleado, $servicio, $fecha, $horaFormateada)) {
                    $horarios[] = $horaFormateada;
                }
            }
        }

        return array_values(array_unique($horarios));
    }

    private function espacioDisponible(
        Empleado $empleado,
        Servicio $servicio,
        $fecha,
        $horaInicio,
        $ignoreId = null
    ) {
        $horariosDelDia = $this->obtenerHorariosDelDia($empleado, $fecha);

        if ($horariosDelDia->isEmpty()) {
            return false;
        }

        $inicioCita = strtotime($fecha . ' ' . $horaInicio);
        $finCita = $inicioCita + ((int) $servicio->duracion * 60);

        $esDentroDeHorario = $horariosDelDia->contains(function ($horario) use ($fecha, $inicioCita, $finCita) {
            $inicioHorario = strtotime($fecha . ' ' . $horario->hora_inicio);
            $finHorario = strtotime($fecha . ' ' . $horario->hora_fin);

            return $inicioCita >= $inicioHorario && $finCita <= $finHorario;
        });

        if (!$esDentroDeHorario) {
            return false;
        }

        foreach ($this->obtenerTiemposOcupados($empleado, $fecha, $ignoreId) as $tiempo) {
            if ($inicioCita < $tiempo['end'] && $finCita > $tiempo['start']) {
                return false;
            }
        }

        return true;
    }

    private function obtenerTiemposOcupados(Empleado $empleado, $fecha, $ignoreId = null)
    {
        $consulta = Cita::with('servicio')
            ->where('empleado_id', $empleado->id)
            ->where('fecha', $fecha);

        if ($ignoreId) {
            $consulta->where('id', '!=', $ignoreId);
        }

        $tiempos = [];

        foreach ($consulta->get() as $cita) {

            $inicio = strtotime($fecha . ' ' . $cita->hora_inicio);
            $fin = $inicio + ((int) $cita->servicio->duracion * 60);

            $tiempos[] = [
                'start' => $inicio,
                'end' => $fin
            ];
        }

        return $tiempos;
    }
}