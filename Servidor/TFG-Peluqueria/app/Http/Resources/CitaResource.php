<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'servicio' => $this->servicio->nombre_servicio,
            'empleado' => $this->empleado->nombre_empleado,
            'estado' => $this->estado->nombre_estado,
        ];
    }
}
