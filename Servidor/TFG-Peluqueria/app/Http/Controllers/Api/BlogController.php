<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Mostrar todos los posts del blog.
     */
    public function index()
    {
        $posts = Blog::latest()->get();

        return BlogResource::collection($posts);
    }

    /**
     * Crear un nuevo post con título e imagen.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = $request->file('image')->store('blog', 'public');

        $post = Blog::create([
            'title' => $request->title,

            // Guardamos igual que servicios: blog/nombre.webp
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Post creado correctamente',
            'data' => new BlogResource($post),
        ], 201);
    }

    /**
     * Actualizar un post del blog.
     */
    public function update(Request $request, $id)
    {
        $post = Blog::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->has('title')) {
            $post->title = $request->title;
        }

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $request->file('image')->store('blog', 'public');

            // Guardamos igual que servicios: blog/nombre.webp
            $post->image = $imagePath;
        }

        $post->save();

        return response()->json([
            'message' => 'Post actualizado correctamente',
            'data' => new BlogResource($post),
        ], 200);
    }

    /**
     * Eliminar un post.
     */
    public function destroy($id)
    {
        $post = Blog::findOrFail($id);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post eliminado correctamente',
        ], 200);
    }
}