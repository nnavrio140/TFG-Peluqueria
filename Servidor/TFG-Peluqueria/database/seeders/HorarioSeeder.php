<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use App\Models\Empleado;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $dias = [
            'lunes',
            'martes',
            'miercoles',
            'jueves',
            'viernes',
            'sabado',
            'domingo'
        ];

        $empleados = Empleado::all();

        foreach ($empleados as $empleado) {

            foreach ($dias as $dia) {

                if (in_array($dia, ['sabado', 'domingo'])) {
                    continue;
                }

                $bloques = [
                    ['hora_inicio' => '10:00', 'hora_fin' => '14:00'],
                    ['hora_inicio' => '16:00', 'hora_fin' => '20:00'],
                ];

                foreach ($bloques as $bloque) {
                    Horario::updateOrCreate(
                        [
                            'empleado_id' => $empleado->id,
                            'dia_semana' => $dia,
                            'hora_inicio' => $bloque['hora_inicio'],
                            'hora_fin' => $bloque['hora_fin'],
                        ],
                        [
                            'activo' => true,
                        ]
                    );
                }
            }
        }
    }
}