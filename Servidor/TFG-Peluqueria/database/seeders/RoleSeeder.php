<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Rol::firstOrCreate(['slug' => 'admin', 'nombre_rol' => 'Administrador', 'descripcion' => 'Acceso total al sistema',]);
        Rol::firstOrCreate(['slug' => 'empleado','nombre_rol' => 'Empleado','descripcion' => 'Empleado que atiende citas']);
        Rol::firstOrCreate(['slug' => 'usuario','nombre_rol' => 'Usuario','descripcion' => 'Usuario cliente',]);
    }
}
