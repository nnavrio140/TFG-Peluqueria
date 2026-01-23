<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estado::firstOrCreate(['nombre_estado' => 'Pendiente', 'descripcion' => 'Todavía no se ha hecho']);
        Estado::firstOrCreate(['nombre_estado' => 'En Progreso', 'descripcion' => 'En curso']);
        Estado::firstOrCreate(['nombre_estado' => 'Confirmada', 'descripcion' => 'Confirmada por usuario o empleado']);
        Estado::firstOrCreate(['nombre_estado' => 'Cancelada', 'descripcion' => 'Ha sido cancelada']);
        Estado::firstOrCreate(['nombre_estado' => 'Completada', 'descripcion' => 'Cita completada']);
    }
}
