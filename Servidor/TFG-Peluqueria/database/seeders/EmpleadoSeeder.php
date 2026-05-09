<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        Empleado::firstOrCreate(['user_id' => 2], ['especialidad' => 'Corte & Estilo', 'salario' => 1500, 'activo' => true]);
        Empleado::firstOrCreate(['user_id' => 3], ['especialidad' => 'Barbero Clásico', 'salario' => 1300, 'activo' => true]);
        Empleado::firstOrCreate(['user_id' => 4], ['especialidad' => 'Facial & Grooming', 'salario' => 1200, 'activo' => true]);
    }
}