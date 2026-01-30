<?php

namespace App\Services;
use App\Models\Servicio;

class ServicioService
{
    public function createServicio(array $data): Servicio
    {
        return Servicio::create($data);
    }
}