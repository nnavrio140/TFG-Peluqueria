<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Estado;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index() {
    
        $usuario = Auth::user();

        $citas = Cita::paraDashboard($usuario)->get();

        $servicios = Servicio::all();
        $usuarios = User::all();
        $estados = Estado::all();

        return view('dashboard.index', compact('usuario', 'citas', 'servicios', 'estados', 'usuarios'));
    }
}
