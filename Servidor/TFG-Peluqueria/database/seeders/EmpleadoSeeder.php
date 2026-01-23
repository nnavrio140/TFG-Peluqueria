<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        Empleado::firstOrCreate(['id_empleado' => 1, 'especialidad' => 'Corte', 'salario' => 1500, 'activo' => true, 'id_usuario' => 1, ]);
        Empleado::firstOrCreate(['id_empleado' => 2, 'especialidad' => 'Color', 'salario' => 1300, 'activo' => true,'id_usuario' => 2, ]);
        Empleado::firstOrCreate(['id_empleado' => 3, 'especialidad' => 'Peinado', 'salario' => 1200, 'activo' => true,'id_usuario' => 3, ]);
    }
}