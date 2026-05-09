<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Festivo;

class FestivoSeeder extends Seeder
{
    public function run(): void
    {
        Festivo::firstOrCreate(['fecha' => '2026-01-01'], ['nombre' => 'Año Nuevo', 'recurrente' => true]);
        Festivo::firstOrCreate(['fecha' => '2026-01-06'], ['nombre' => 'Epifanía del Señor (Reyes Magos)', 'recurrente' => true]);

        Festivo::firstOrCreate(['fecha' => '2026-04-02'], ['nombre' => 'Jueves Santo', 'recurrente' => false]);
        Festivo::firstOrCreate(['fecha' => '2026-04-03'], ['nombre' => 'Viernes Santo', 'recurrente' => false]);

        Festivo::firstOrCreate(['fecha' => '2026-05-01'], ['nombre' => 'Fiesta del Trabajo', 'recurrente' => true]);

        Festivo::firstOrCreate(['fecha' => '2026-08-15'], ['nombre' => 'Asunción de la Virgen', 'recurrente' => true]);

        Festivo::firstOrCreate(['fecha' => '2026-10-12'], ['nombre' => 'Fiesta Nacional de España', 'recurrente' => true]);

        Festivo::firstOrCreate(['fecha' => '2026-12-07'], ['nombre' => 'Día de la Constitución (traslado)', 'recurrente' => false]);

        Festivo::firstOrCreate(['fecha' => '2026-12-08'], ['nombre' => 'Inmaculada Concepción', 'recurrente' => true]);

        Festivo::firstOrCreate(['fecha' => '2026-12-25'], ['nombre' => 'Natividad del Señor (Navidad)', 'recurrente' => true]);

        Festivo::firstOrCreate(['fecha' => '2026-02-28'], ['nombre' => 'Día de Andalucía', 'recurrente' => true]);
    }
}