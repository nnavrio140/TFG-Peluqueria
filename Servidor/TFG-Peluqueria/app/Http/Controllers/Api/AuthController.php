<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Autentica al usuario y genera un token Sanctum.
     * Se utiliza para login desde React y obtener el token de acceso.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    /**
     * Devuelve los datos del usuario autenticado.
     * Útil para saber quién está conectado y su rol.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Crea una nueva cuenta de usuario.
     * Recibe: nombre, email, password
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 2, // Por defecto, rol de cliente (ajusta según tu tabla de roles)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado correctamente',
                'token' => $token,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina el token actual y cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()?->delete();
        }

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
