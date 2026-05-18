<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        Estado::firstOrCreate(['slug' => 'confirmada'], ['nombre_estado' => 'Confirmada', 'descripcion' => 'La cita está activa y pendiente de realizarse']);
        Estado::firstOrCreate(['slug' => 'completada'], ['nombre_estado' => 'Completada', 'descripcion' => 'La cita ya ha finalizado correctamente']);
    }
}