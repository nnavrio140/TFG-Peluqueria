<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        Servicio::firstOrCreate(['nombre_servicio' => 'Corte de Cabello', 'descripcion' => 'Corte tradicional', 'precio' => 15.00, 'duracion' => 30]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Afeitado Clásico', 'descripcion' => 'Afeitado tradicional con brocha y navaja', 'precio' => 21.00, 'duracion' => 30]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Arreglo de Barba', 'descripcion' => 'Perfilado y arreglo de barba', 'precio' => 9.00, 'duracion' => 30]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Peinado', 'descripcion' => 'Peinado y estilo final', 'precio' => 8.00, 'duracion' => 15]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Lavado y Peinado', 'descripcion' => 'Lavado de cabello y peinado', 'precio' => 6.00, 'duracion' => 15]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Corte + Barba', 'descripcion' => 'Combo de corte de cabello y arreglo de barba', 'precio' => 39.00, 'duracion' => 60]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Corte + Afeitado', 'descripcion' => 'Corte de cabello y afeitado clásico', 'precio' => 40.00, 'duracion' => 50]);
    }
}
