<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::truncate();

        $posts = [
            [
                'title' => 'Buzz Cut',
                'image' => 'blog/buzzcut.webp',
            ],
            [
                'title' => 'Low Fade',
                'image' => 'blog/lowfade.webp',
            ],
            [
                'title' => 'Taper Fade',
                'image' => 'blog/taperfade.webp',
            ],
            [
                'title' => 'French Crop',
                'image' => 'blog/frenchcrop.webp',
            ],
            [
                'title' => 'Mid Fade',
                'image' => 'blog/midfade.webp',
            ],
            [
                'title' => 'Corte Libertino',
                'image' => 'blog/librito.webp',
            ],
            [
                'title' => 'Corte Mullet',
                'image' => 'blog/mullet.webp',
            ],
            [
                'title' => 'Corte Francés',
                'image' => 'blog/frances.webp',
            ],
        ];

        foreach ($posts as $post) {
            Blog::create($post);
        }
    }
}