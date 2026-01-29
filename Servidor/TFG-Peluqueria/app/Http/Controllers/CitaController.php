<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
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

    public function store(StoreCitaRequest $request)
    {
        //Comprobamos y guardamos la cita
        $validated = $request->validated();
        //Creamos la cita
        Cita::create($validated);
        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente');
    }

    public function show(Cita $cita)
    {
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
         // Obtener datos para el formulario
        $estados = Estado::all();
        $usuarios = User::all();
        $empleados = Empleado::all();
        $servicios = Servicio::all();
        return view('citas.edit', compact('cita', 'estados', 'usuarios', 'empleados', 'servicios'));
    }

    public function update(UpdateCitaRequest $request, Cita $cita)
    {
         $validated = $request->validated();
         $cita->update($validated);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente');
    }

    public function destroy(Cita $cita)
    {
        // Borrar el historial de la cita primero
        $cita->historial()->delete();
        
        // Luego borrar la cita
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente');
    }
}
