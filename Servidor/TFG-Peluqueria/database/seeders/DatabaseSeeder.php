<?php

namespace Database\Seeders;

//use App\Models\User;

use App\Models\Empleado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            UsuarioSeeder::class,
            EmpleadoSeeder::class,
            EstadoSeeder::class,
            ServicioSeeder::class,
            HorarioSeeder::class,
            CitaSeeder::class,
            HistorialCitaSeeder::class,
        ]);

        // if (app()->environment('local')) {
        //     // Sólo se ejecutan estos seeders en el entorno local (desarrollo)
        //     $this->call([
        //         ProyectoSeeder::class,
        //         TareaSeeder::class,
        //     ]);
        // }

        
    }
}
