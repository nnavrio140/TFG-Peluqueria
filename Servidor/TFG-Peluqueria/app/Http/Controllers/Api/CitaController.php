<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Resources\ServicioResource;

class CitaController extends Controller
{
     public function index()
    {
        // Obtener todas las citas
        $citas = Cita::all();
        return CitaResource::collection($citas);
    }


    public function store(StoreCitaRequest $request)
    {
        //Comprobamos y guardamos la cita
        $validated = $request->validated();
        $cita = Cita::create($validated);
        return response()->json(['message' => 'Cita creada correctamente','data' => new CitaResource($cita)], 201);
    }

    public function show(Cita $cita)
    {
        return new CitaResource($cita);
    }

    public function update(StoreCitaRequest $request, Cita $cita)
    {
        $validated = $request->validated();
        $cita->update($validated);
        return response()->json(['message' => 'Cita actualizada correctamente','data' => new CitaResource($cita)], 200);
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return response()->json(['message' => 'Cita eliminada correctamente'], 200);
    }
}
