<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        Estado::firstOrCreate(['slug' => 'pendiente'], ['nombre_estado' => 'Pendiente', 'descripcion' => 'Aún no gestionada']);
        Estado::firstOrCreate(['slug' => 'confirmada'], ['nombre_estado' => 'Confirmada', 'descripcion' => 'Confirmada por usuario o empleado']);
        Estado::firstOrCreate(['slug' => 'cancelada'], ['nombre_estado' => 'Cancelada', 'descripcion' => 'Ha sido cancelada']);
        Estado::firstOrCreate(['slug' => 'completada'], ['nombre_estado' => 'Completada', 'descripcion' => 'Servicio realizado']);
    }
}