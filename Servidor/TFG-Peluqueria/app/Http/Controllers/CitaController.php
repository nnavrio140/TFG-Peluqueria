<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\Cita;


class CitaController extends Controller
{
    public function index()
    {
        // Obtener todas las citas
        $citas = Cita::all();
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        // Obtener datos para el formulario
        $estados = Estado::all();
        $usuarios = User::all();
        $empleados = Empleado::all();
        $servicios = Servicio::all();
        return view('citas.create', compact('estados', 'usuarios', 'empleados', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'fecha' => 'required|date',
           'hora_inicio' => 'required',
           'id_usuario' => 'required|exists:usuarios,id',
           'id_empleado' => 'required|exists:empleados,id',
           'id_servicio' => 'required|exists:servicios,id',
           'id_estado' => 'required|exists:estados,id',
        ]);

        Cita::create($request->all());

        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente');
    }

    public function show(Cita $cita)
    {
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, Cita $cita)
    {
         $request->validate([
           'fecha' => 'required|date',
           'hora_inicio' => 'required',
           'id_usuario' => 'required|exists:usuarios,id',
           'id_empleado' => 'required|exists:empleados,id',
           'id_servicio' => 'required|exists:servicios,id',
           'id_estado' => 'required|exists:estados,id',
        ]);

        $cita->update($request->all());

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente');
    }
}
