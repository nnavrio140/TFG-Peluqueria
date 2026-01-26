<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Obtener todos los usuarios
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        // Obtener roles disponibles para el formulario
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $data = $request->only(['name', 'email', 'rol_id']);
        $data['password'] = bcrypt($request->password);

        User::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        // Obtener roles disponibles para el formulario
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        //Porque al editar ya existe ese email, y Laravel necesita saber que ese es válido.
        'email'    => 'required|email|unique:users,email,' . $usuario->id,
        'password' => 'nullable|string|min:8|confirmed',
        'rol_id'   => 'required|exists:roles,id',
    ]);

   $data = $request->only(['name', 'email', 'rol_id']);

    // Solo actualiza la contraseña si el usuario escribió una nueva.
    // Si el campo password viene vacío, se mantiene la contraseña actual.
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    $usuario->update($data);

    return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
}

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
