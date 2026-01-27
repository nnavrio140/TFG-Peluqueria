<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Rol::where('slug', 'admin')->first();
        $empleadoRole = Rol::where('slug', 'empleado')->first();

        User::firstOrCreate(['nombre' => 'admin', 'email' => 'admin@admin.com', 'password' => bcrypt('1234'), 'role_id' => $adminRole->id]);
        User::firstOrCreate(['nombre' => 'Juanje', 'email' => 'juanje@admin.com', 'password' => bcrypt('1234'), 'role_id' => $empleadoRole->id]);
        User::firstOrCreate(['nombre' => 'Nico', 'email' => 'nico@admin.com', 'password' => bcrypt('1234'), 'role_id' => $empleadoRole->id]);
        User::firstOrCreate(['nombre' => 'Antonio', 'email' => 'antonio@admin.com', 'password' => bcrypt('1234'), 'role_id' => $empleadoRole->id]);


        User::factory()->count(10)->create();

    

    }
}
