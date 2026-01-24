<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cita;
use App\Models\Estado;

class HistorialCitaFactory extends Factory
{
    protected $model = \App\Models\HistorialCita::class;

    public function definition(): array
    {
        return [
            'id_cita' => Cita::inRandomOrder()->first()->id,
            'id_estado' => Estado::inRandomOrder()->first()->id,
            'fecha' => now(),
        ];
    }
}
