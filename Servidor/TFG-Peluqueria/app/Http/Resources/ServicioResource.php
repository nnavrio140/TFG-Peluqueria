<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    /**
     * Convierte el servicio a JSON para la API.
     * Incluye duración y precio para el selector de cita.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre_servicio,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'duracion' => $this->duracion,
        ];
    }
}
