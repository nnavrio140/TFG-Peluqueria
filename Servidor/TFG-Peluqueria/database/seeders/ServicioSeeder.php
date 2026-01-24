<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        Servicio::firstOrCreate(['nombre_servicio' => 'Corte & Barba', 'descripcion' => 'Corte moderno combinado con arreglo y perfilado de barba, adaptado a tu estilo.', 'precio' => 12.00, 'duracion' => 30]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Afeitado Clásico', 'descripcion' => 'Afeitado tradicional con navaja y toalla caliente, usando productos premium.', 'precio' => 8.00, 'duracion' => 15]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Facial & Shave', 'descripcion' => 'Tratamiento facial exclusivo junto con afeitado completo para una piel fresca y revitalizada.', 'precio' => 20.00, 'duracion' => 45]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Facial', 'descripcion' => 'Limpieza profunda del rostro que elimina impurezas, hidrata y devuelve vitalidad a la piel.', 'precio' =>15.00, 'duracion' => 30]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Mustache Trimming', 'descripcion' => 'Perfilado y recorte de bigote con precisión para un look elegante y bien cuidado.', 'precio' => 5.00, 'duracion' => 15]);
        Servicio::firstOrCreate(['nombre_servicio' => 'Hair Styling', 'descripcion' => 'Peinado y acabado profesional, ideal para eventos especiales o el día a día.', 'precio' => 6.00, 'duracion' => 15]);

    }
}
