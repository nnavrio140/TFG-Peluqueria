<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    /**
     * Convierte el servicio a JSON para la API.
     * Incluye duración, precio e imagen para el frontend.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre_servicio,
            'descripcion_corta' => $this->descripcion_corta,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'duracion' => $this->duracion,

            'imagen' => $this->imagen,
            'imagen_url' => $this->imagen
                ? asset('storage/' . $this->imagen)
                : null,
        ];
    }
}