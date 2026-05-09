<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Rol::where('slug', 'admin')->first();
        $empleadoRole = Rol::where('slug', 'empleado')->first();

        User::firstOrCreate(['email' => 'admin@admin.com'], ['nombre' => 'admin', 'password' => Hash::make('1234'), 'role_id' => $adminRole->id]);
        User::firstOrCreate(['email' => 'juanje@admin.com'], ['nombre' => 'Juanje Gutierrez', 'password' => Hash::make('1234'), 'role_id' => $empleadoRole->id]);
        User::firstOrCreate(['email' => 'nico@admin.com'], ['nombre' => 'Nicolas Navarrete', 'password' => Hash::make('1234'), 'role_id' => $empleadoRole->id]);
        User::firstOrCreate(['email' => 'antonio@admin.com'], ['nombre' => 'Antonio Villalba', 'password' => Hash::make('1234'), 'role_id' => $empleadoRole->id]);
    }
}