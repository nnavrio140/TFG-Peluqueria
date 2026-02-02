<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Estado;
use Carbon\Carbon;

class UsuarioService
{
    public function createUser(array $data): User
    {
        // Crear usuario
        $user = User::create([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id'  => $data['role_id'],
        ]);

        // Crear cita automática para mañana
        Cita::create([
            'user_id'     => $user->id,
            'id_empleado' => Empleado::first()->id,
            'id_servicio' => Servicio::first()->id,
            'id_estado'   => Estado::where('nombre_estado', 'pendiente')->first()->id,
            'fecha'       => Carbon::tomorrow()->format('Y-m-d'),
            'hora_inicio' => '10:00',
        ]);

        return $user;
    }
}
