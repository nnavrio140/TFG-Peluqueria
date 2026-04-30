<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        Servicio::firstOrCreate([
            'nombre_servicio' => 'Corte & Barba',
            'descripcion_corta' => 'Corte clásico o moderno adaptado a tu estilo. Incluye asesoría personalizada.',
            'descripcion' => 'Corte clásico o moderno combinado con arreglo y perfilado de barba. Trabajamos cada detalle para lograr un estilo preciso, definido y a tu medida.',
            'precio' => 12.00,
            'duracion' => 30
        ]);

        Servicio::firstOrCreate([
            'nombre_servicio' => 'Corte',
            'descripcion_corta' => 'Afeitado tradicional con toalla caliente para una experiencia suave y relajante.',
            'descripcion' => 'Corte de cabello con técnica precisa y acabado profesional. Estilo limpio y definido para un look moderno, elegante y bien cuidado.',
            'precio' => 8.00,
            'duracion' => 30
        ]);

        Servicio::firstOrCreate([
            'nombre_servicio' => 'Corte & Teñido',
            'descripcion_corta' => 'Perfilado y definición de barba con navaja para un acabado limpio y preciso.',
            'descripcion' => 'Corte y coloración profesional adaptados a tu estilo. Acabado uniforme y duradero para realzar tu imagen y renovar tu look.',
            'precio' => 20.00,
            'duracion' => 45
        ]);

        Servicio::firstOrCreate([
            'nombre_servicio' => 'Afeitado',
            'descripcion_corta' => 'Tratamiento facial completo para limpiar, hidratar y revitalizar la piel del rostro.',
            'descripcion' => 'Afeitado tradicional con navaja y toalla caliente. Productos de primera calidad para una experiencia relajante, segura y profesional.',
            'precio' => 6.00,
            'duracion' => 15
        ]);
    }
}