<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        Servicio::updateOrCreate(
            ['nombre_servicio' => 'Corte & Barba'],
            [
                'descripcion_corta' => 'Corte y arreglo de barba con acabado profesional.',
                'descripcion' => 'Corte clásico o moderno combinado con arreglo y perfilado de barba. Trabajamos cada detalle para lograr un estilo preciso, definido y a tu medida.',
                'precio' => 12.00,
                'duracion' => 40,
                'imagen' => 'servicios/corte_barba.webp',
                'activo' => true,
            ]
        );

        Servicio::updateOrCreate(
            ['nombre_servicio' => 'Corte'],
            [
                'descripcion_corta' => 'Corte de cabello moderno con acabado limpio y definido.',
                'descripcion' => 'Corte de cabello con técnica precisa y acabado profesional. Estilo limpio y definido para un look moderno, elegante y bien cuidado.',
                'precio' => 8.00,
                'duracion' => 20,
                'imagen' => 'servicios/corte.webp',
                'activo' => true,
            ]
        );

        Servicio::updateOrCreate(
            ['nombre_servicio' => 'Corte & Teñido'],
            [
                'descripcion_corta' => 'Corte y coloración profesional adaptados a tu estilo.',
                'descripcion' => 'Corte y coloración profesional adaptados a tu estilo. Acabado uniforme y duradero para realzar tu imagen y renovar tu look.',
                'precio' => 20.00,
                'duracion' => 60,
                'imagen' => 'servicios/corte_tinte.webp',
                'activo' => true,
            ]
        );

        Servicio::updateOrCreate(
            ['nombre_servicio' => 'Afeitado'],
            [
                'descripcion_corta' => 'Afeitado clásico con navaja y toalla caliente.',
                'descripcion' => 'Afeitado tradicional con navaja y toalla caliente. Productos de primera calidad para una experiencia relajante, segura y profesional.',
                'precio' => 6.00,
                'duracion' => 15,
                'imagen' => 'servicios/afeitado.webp',
                'activo' => true,
            ]
        );
    }
}