<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Estado;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener usuario logado
        $usuario = Auth::user();

        // Obtener citas del día actual 
        $citas = Cita::with(['usuario', 'servicio', 'empleado', 'estado'])->whereDate('fecha', Carbon::today())->get();

        // Obtener todos los servicios
        $servicios = Servicio::all();

        // Obtener todos los estados
        $estados = Estado::all();

        return view('dashboard.index', compact('usuario', 'citas', 'servicios', 'estados'));
    }
}
