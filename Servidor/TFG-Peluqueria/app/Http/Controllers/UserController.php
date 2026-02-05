<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UsuarioService;

class UserController extends Controller
{
    protected $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

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

    public function store(Request $request)
    {
        $this->usuarioService->createUser($request->all());

        return redirect()->route('dashboard.index')->with('success', 'Usuario creado y cita asignada');
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

        return redirect()->route('dashboard.index');
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

        return redirect()->route('dashboard.index');
    }
}
