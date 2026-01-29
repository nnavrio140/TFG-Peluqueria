<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Rol;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->only(['nombre', 'email', 'password', 'role_id']));

        return redirect()->route('usuarios.index');
    }

    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $usuario)
    {
        $validated = $request->validated();
        $usuario->update($validated);

        return redirect()->route('usuarios.index');
    }

    public function destroy(User $usuario)
    {
        //Todo esto se ahoraría con claves foráneas y on delete cascade, pero lo hago así para practicar

        // Borrar sesiones del usuario
        DB::table('sessions')->where('user_id', $usuario->id)->delete();

        // Borrar historial de las citas del usuario
        foreach ($usuario->citas as $cita) {
            $cita->historial()->delete();
        }

        // Borrar citas del usuario
        $usuario->citas()->delete();

        // Borrar empleado asociado si existe
        if ($usuario->empleado) {
            $usuario->empleado()->delete();
        }

        // Borrar el usuario
        $usuario->delete();

        return redirect()->route('usuarios.index');
    }
}
