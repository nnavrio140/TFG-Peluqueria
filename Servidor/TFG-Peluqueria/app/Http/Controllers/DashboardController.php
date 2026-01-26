<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Estado;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener usuario logado
        $usuario = Auth::user();

        // Obtener todas las citas
        $citas = Cita::with(['usuario', 'servicio', 'empleado', 'estado'])->get();

        // Obtener todos los servicios
        $servicios = Servicio::all();

        // Obtener todos los estados
        $estados = Estado::all();

        return view('dashboard.index', compact('usuario', 'citas', 'servicios', 'estados'));
    }
}
