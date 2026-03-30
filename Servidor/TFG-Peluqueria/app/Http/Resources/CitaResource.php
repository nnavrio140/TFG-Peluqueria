<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    /**
     * Convierte la cita a JSON para la API.
     * Incluye datos de servicio, empleado, estado y usuario.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $horaInicio = $this->hora_inicio;
        $horaFin = null;
        if ($this->servicio) {
            $horaFin = \Carbon\Carbon::parse($horaInicio)
                ->addMinutes($this->servicio->duracion)
                ->format('H:i');
        }

        return [
            'id' => $this->id,
            'fecha' => $this->fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'servicio' => [
                'id' => $this->servicio?->id,
                'nombre' => $this->servicio?->nombre_servicio,
                'duracion' => $this->servicio?->duracion,
                'precio' => $this->servicio?->precio,
            ],
            'empleado' => [
                'id' => $this->empleado?->id,
                'nombre' => $this->empleado?->usuario?->nombre,
            ],
            'estado' => $this->estado?->nombre_estado,
            'usuario' => [
                'id' => $this->usuario?->id,
                'nombre' => $this->usuario?->nombre,
            ],
        ];
    }
}
