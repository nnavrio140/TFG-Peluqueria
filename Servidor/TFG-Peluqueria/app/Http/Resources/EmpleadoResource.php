<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HorarioResource;

class EmpleadoResource extends JsonResource
{
    /**
     * Devuelve al empleado con sus datos básicos y horarios.
     * Útil para listar peluqueros y conocer sus turnos.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->usuario?->nombre,
            'especialidad' => $this->especialidad,
            'imagen' => $this->imagen,
            'imagen_url' => $this->imagen_url,
            'activo' => (bool) $this->activo,
            'usuario_id' => $this->user_id,
            'horarios' => HorarioResource::collection($this->whenLoaded('horarios')),
        ];
    }
}