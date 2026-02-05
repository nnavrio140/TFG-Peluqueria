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
    public function index()
    {
        // Obtener usuario logado (garantizado por middleware auth)
        /** @var User $usuario */
        $usuario = Auth::user();

        // Obtener citas segun roles
        if ($usuario->isAdmin()) {
            //Si es admin obtener todas las citas del día actual
            $citas = Cita::with(['usuario', 'servicio', 'empleado', 'estado'])->whereDate('fecha', Carbon::today())->get();
        } elseif ($usuario->isEmployee()) {
            // Si es empleado obtener todas las citas del día actual suyas
            $citas = Cita::with(['usuario', 'servicio', 'empleado', 'estado'])->whereDate('fecha', Carbon::today())->where('id_empleado', $usuario->empleado->id)->get();
        } else {
            // Si es cliente obtener todas sus citas da igual el día
            $citas = Cita::with(['usuario', 'servicio', 'empleado', 'estado'])->where('user_id', $usuario->id)->get();
        }
        // Obtener todos los servicios
        $servicios = Servicio::all();

         // Obtener todos los usuarios
        $usuarios = User::all();

        // Obtener todos los estados
        $estados = Estado::all();

        return view('dashboard.index', compact('usuario', 'citas', 'servicios', 'estados', 'usuarios'));
    }
}
