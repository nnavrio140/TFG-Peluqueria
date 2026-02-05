<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado y sea admin
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Si no cumple los requisitos, redirigir al dashboard
        return redirect()->route('dashboard.index')->with('error', 'No tienes permiso para acceder a este recurso');
    }
}
