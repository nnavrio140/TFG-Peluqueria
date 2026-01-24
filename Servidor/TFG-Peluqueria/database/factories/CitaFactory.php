<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Estado;

class CitaFactory extends Factory
{
    protected $model = \App\Models\Cita::class;

    public function definition(): array
    {
        return [
            // Selecciona un usuario con rol 'usuario'
            'user_id' => User::whereHas('rol', fn ($q) => $q->where('slug', 'usuario'))->inRandomOrder()->first()->id,
            //Coger las FK aleatorias
            'id_empleado' => Empleado::inRandomOrder()->first()->id,
            'id_servicio' => Servicio::inRandomOrder()->first()->id,
            'id_estado' => Estado::inRandomOrder()->first()->id,
            //Genera fedha entre hoy y un mes a partir de hoy
            'fecha' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            //Genera horas en formato 24 horas
            'hora_inicio' => $this->faker->time('H:i'),
        ];
    }
}
