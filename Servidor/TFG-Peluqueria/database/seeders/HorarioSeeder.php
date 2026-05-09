<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use App\Models\Empleado;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $dias = ['lunes','martes','miercoles','jueves','viernes'];
        $empleados = Empleado::all();

        foreach ($empleados as $index => $empleado) {
            foreach ($dias as $dia) {

                Horario::firstOrCreate(
                    ['empleado_id' => $empleado->id, 'dia_semana' => $dia],
                    [
                        'hora_inicio' => match($index){
                            0 => '09:00',
                            1 => '09:00',
                            2 => '10:00',
                            default => '09:00'
                        },
                        'hora_fin' => match($index){
                            0 => '16:00',
                            1 => '18:00',
                            2 => '19:00',
                            default => '18:00'
                        }
                    ]
                );
            }
        }
    }
}