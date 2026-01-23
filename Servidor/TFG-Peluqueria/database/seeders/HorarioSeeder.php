<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        // Días laborales
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // EMPLEADO 1 → menos horas (jefe)
        foreach ($dias as $dia) {
            Horario::firstOrCreate([
                'dia_semana' => $dia,
                'hora_inicio' => '9:00',
                'hora_fin' => '16:00',
                'id_empleado' => 1,
            ]);
        }

        // EMPLEADO 2 → jornada completa
        foreach ($dias as $dia) {
            Horario::firstOrCreate([
                'dia_semana' => $dia,
                'hora_inicio' => '09:00',
                'hora_fin' => '18:00',
                'id_empleado' => 2,
            ]);
        }

        // EMPLEADO 3 → jornada completa 
        foreach ($dias as $dia) {
            Horario::firstOrCreate([
                'dia_semana' => $dia,
                'hora_inicio' => '10:00',
                'hora_fin' => '19:00',
                'id_empleado' => 3,
            ]);
        }
    }
}
