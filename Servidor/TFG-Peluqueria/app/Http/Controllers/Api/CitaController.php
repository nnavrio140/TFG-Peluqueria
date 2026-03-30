<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Resources\CitaResource;
use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    /**
     * Devuelve las citas visibles para el usuario autenticado.
     * - admin: todas las citas
     * - empleado: solo citas donde es el empleado asignado
     * - usuario: solo sus propias citas
     */
    public function index()
    {
        $usuario = Auth::user();

        $query = Cita::with(['servicio', 'empleado.usuario', 'estado', 'usuario']);

        if ($usuario && !$usuario->isAdmin()) {
            if ($usuario->isEmployee()) {
                $empleado = $usuario->empleado;
                if ($empleado) {
                    $query->where('id_empleado', $empleado->id);
                } else {
                    $query->where('id_empleado', 0);
                }
            } else {
                $query->where('user_id', $usuario->id);
            }
        }

        $citas = $query->get();
        return CitaResource::collection($citas);
    }

    /**
     * Crea una cita nueva.
     * Valida la entrada, comprueba disponibilidad y guarda la cita con estado "Pendiente" si no se envía.
     */
    public function store(StoreCitaRequest $request)
    {
        $usuario = Auth::user();
        $validated = $request->validated();

        $servicio = Servicio::findOrFail($validated['id_servicio']);
        $empleado = Empleado::findOrFail($validated['id_empleado']);

        $this->authorizeBooking($servicio, $empleado, $validated['fecha'], $validated['hora_inicio']);

        $estadoId = $validated['id_estado'] ?? Estado::where('nombre_estado', 'Pendiente')->first()?->id ?? Estado::first()?->id;

        $cita = Cita::create([
            'fecha' => $validated['fecha'],
            'hora_inicio' => $validated['hora_inicio'],
            'user_id' => $usuario->isAdmin() && isset($validated['user_id']) ? $validated['user_id'] : $usuario->id,
            'id_servicio' => $servicio->id,
            'id_empleado' => $empleado->id,
            'id_estado' => $estadoId,
        ]);

        return response()->json([
            'message' => 'Cita creada correctamente',
            'data' => new CitaResource($cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']))
        ], 201);
    }

    /**
     * Devuelve los datos completos de una cita.
     * Incluye relaciones de servicio, empleado, estado y usuario.
     */
    public function show(Cita $cita)
    {
        return new CitaResource($cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']));
    }

    /**
     * Actualiza una cita existente.
     * Solo permite editarla si eres admin o si eres el usuario dueño de la cita.
     */
    public function update(StoreCitaRequest $request, Cita $cita)
    {
        $usuario = Auth::user();
        if (!$usuario->isAdmin() && $cita->user_id !== $usuario->id) {
            return response()->json(['message' => 'No tienes permiso para modificar esta cita.'], 403);
        }

        $validated = $request->validated();

        $servicio = Servicio::findOrFail($validated['id_servicio']);
        $empleado = Empleado::findOrFail($validated['id_empleado']);

        $this->authorizeBooking($servicio, $empleado, $validated['fecha'], $validated['hora_inicio'], $cita->id);

        $cita->update([
            'fecha' => $validated['fecha'],
            'hora_inicio' => $validated['hora_inicio'],
            'user_id' => $usuario->isAdmin() && isset($validated['user_id']) ? $validated['user_id'] : $cita->user_id,
            'id_servicio' => $servicio->id,
            'id_empleado' => $empleado->id,
            'id_estado' => $validated['id_estado'] ?? $cita->id_estado,
        ]);

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'data' => new CitaResource($cita->load(['servicio', 'empleado.usuario', 'estado', 'usuario']))
        ], 200);
    }

    /**
     * Elimina una cita.
     * Solo puede borrar el propietario de la cita o un administrador.
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
     * Calcula la disponibilidad de horarios para un servicio + empleado + fecha.
     * Devuelve los posibles inicios de cita que no chocan con otras reservas.
     */
    public function disponibilidad(Request $request)
    {
        $data = $request->validate([
            'id_servicio' => 'required|exists:servicios,id',
            'id_empleado' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
        ]);

        $servicio = Servicio::findOrFail($data['id_servicio']);
        $empleado = Empleado::findOrFail($data['id_empleado']);

        $slots = $this->getAvailableSlots($empleado, $servicio, $data['fecha']);

        return response()->json([
            'disponibilidad' => $slots,
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

    /**
     * Valida si se puede reservar una cita en el horario y día del empleado.
     * Comprueba que el empleado trabaje ese día y que el slot esté libre.
     */
    private function authorizeBooking(Servicio $servicio, Empleado $empleado, string $fecha, string $horaInicio, ?int $excludeAppointmentId = null): void
    {
        $diaSemana = $this->getDiaSemana($fecha);
        $horario = $empleado->horarios()->where('dia_semana', $diaSemana)->first();

        if (!$horario) {
            abort(422, 'El empleado no trabaja ese día.');
        }

        if (!$this->isSlotAvailable($empleado, $servicio, $fecha, $horaInicio, $excludeAppointmentId)) {
            abort(422, 'El horario seleccionado no está disponible.');
        }
    }

    /**
     * Calcula todos los horarios de inicio posibles para un servicio.
     * Recorre la jornada del empleado y devuelve solo los slots que no chocan.
     */
    private function getAvailableSlots(Empleado $empleado, Servicio $servicio, string $fecha, int $intervalMinutes = 15): array
    {
        $diaSemana = $this->getDiaSemana($fecha);
        $horario = $empleado->horarios()->where('dia_semana', $diaSemana)->first();
        if (!$horario) {
            return [];
        }

        $start = Carbon::parse($horario->hora_inicio);
        $end = Carbon::parse($horario->hora_fin);
        $duration = (int) $servicio->duracion;

        $slots = [];
        $cursor = $start->copy();

        while ($cursor->copy()->addMinutes($duration)->lessThanOrEqualTo($end)) {
            $candidateStart = $cursor->format('H:i');
            if ($this->isSlotAvailable($empleado, $servicio, $fecha, $candidateStart)) {
                $slots[] = $candidateStart;
            }
            $cursor->addMinutes($intervalMinutes);
        }

        return array_values(array_unique($slots));
    }

    /**
     * Verifica si un horario específico está libre para un servicio.
     * Comprueba solapamientos con otras citas ya reservadas.
     */
    private function isSlotAvailable(Empleado $empleado, Servicio $servicio, string $fecha, string $horaInicio, ?int $excludeAppointmentId = null): bool
    {
        $diaSemana = $this->getDiaSemana($fecha);
        $horario = $empleado->horarios()->where('dia_semana', $diaSemana)->first();
        if (!$horario) {
            return false;
        }

        $slotStart = Carbon::parse($horaInicio);
        $slotEnd = $slotStart->copy()->addMinutes($servicio->duracion);

        $scheduleStart = Carbon::parse($horario->hora_inicio);
        $scheduleEnd = Carbon::parse($horario->hora_fin);

        if ($slotStart->lt($scheduleStart) || $slotEnd->gt($scheduleEnd)) {
            return false;
        }

        $busy = $this->getBusyIntervals($empleado, $fecha, $excludeAppointmentId);

        foreach ($busy as $interval) {
            if ($slotStart->lt($interval['end']) && $slotEnd->gt($interval['start'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtiene los intervalos ocupados por otras citas de ese empleado en la misma fecha.
     * Sirve para comparar solapamientos de horarios.
     */
    private function getBusyIntervals(Empleado $empleado, string $fecha, ?int $excludeAppointmentId = null): array
    {
        $query = Cita::with('servicio')
            ->where('id_empleado', $empleado->id)
            ->where('fecha', $fecha);

        if ($excludeAppointmentId) {
            $query->where('id', '<>', $excludeAppointmentId);
        }

        return $query->get()->map(function (Cita $cita) {
            $start = Carbon::parse($cita->hora_inicio);
            $end = $start->copy()->addMinutes($cita->servicio->duracion);
            return [
                'start' => $start,
                'end' => $end,
            ];
        })->toArray();
    }

    /**
     * Convierte la fecha en el nombre del día de la semana en español.
     * Esto se usa para buscar el horario del empleado en esa jornada.
     */
    private function getDiaSemana(string $fecha): string
    {
        $map = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];

        return $map[Carbon::parse($fecha)->dayOfWeek];
    }
}
