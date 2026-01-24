<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        Empleado::firstOrCreate(['especialidad' => 'Corte & Estilo', 'salario' => 1500, 'activo' => true, 'user_id' => 2, ]);
        Empleado::firstOrCreate(['especialidad' => 'Barbero Clásico', 'salario' => 1300, 'activo' => true,'user_id' => 3, ]);
        Empleado::firstOrCreate(['especialidad' => 'Facial & Grooming', 'salario' => 1200, 'activo' => true,'user_id' => 4, ]);
    }
}