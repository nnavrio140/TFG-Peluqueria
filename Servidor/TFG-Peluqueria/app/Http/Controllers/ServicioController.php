<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{


    public function index()
    {
        // Obtener todos los servicios
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        // Solo muestra el formulario y no necesita datos adicionales
        return view('servicios.create');
    }

    public function store(StoreServicioRequest $request)
    {
        //Comprobamos y guardamos el servicio
        $validated = $request->validated();
        Servicio::create($validated);
        return redirect()->route('dashboard.index')->with('success', 'Servicio creado correctamente');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(UpdateServicioRequest $request, Servicio $servicio)
    {
        $validated = $request->validated();
        $servicio->update($validated);

        return redirect()->route('dashboard.index')->with('success', 'Servicio actualizado correctamente');
    }

    public function destroy(Servicio $servicio)
    {
          /** @var User $user */
        $user = Auth::user();
        // Solo el admin o empleados 
        if (!$user->isAdminOrEmploye()) {
            return redirect()->route('dashboard.index')->with('error', 'No tienes permiso para eliminar este servicio');
        }
        $servicio->delete();

        return redirect()->route('dashboard.index')->with('success', 'Servicio eliminado correctamente');
    }
}
