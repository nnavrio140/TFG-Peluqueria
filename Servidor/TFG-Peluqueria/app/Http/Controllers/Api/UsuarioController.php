<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('rol')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'usuarios' => $usuarios,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', 'string', Rule::in(['admin', 'empleado', 'usuario'])],
        ]);

        $rol = Rol::where('slug', $validated['rol'])->first();

        if (!$rol) {
            return response()->json([
                'message' => 'El rol seleccionado no existe.',
            ], 422);
        }

        $usuario = User::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $rol->id,
        ]);

        $usuario->load('rol');

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'usuario' => $usuario,
        ], 201);
    }

    public function show(User $usuario)
    {
        $usuario->load('rol');

        return response()->json([
            'usuario' => $usuario,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'rol' => ['required', 'string', Rule::in(['admin', 'empleado', 'usuario'])],
        ]);

        $rol = Rol::where('slug', $validated['rol'])->first();

        if (!$rol) {
            return response()->json([
                'message' => 'El rol seleccionado no existe.',
            ], 422);
        }

        $usuario->nombre = $validated['nombre'];
        $usuario->email = $validated['email'];
        $usuario->role_id = $rol->id;

        if (!empty($validated['password'])) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->save();
        $usuario->load('rol');

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'usuario' => $usuario,
        ]);
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }
}