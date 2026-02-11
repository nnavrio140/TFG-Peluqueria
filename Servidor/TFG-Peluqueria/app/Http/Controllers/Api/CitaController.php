<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Http\Requests\StoreCitaRequest;

class CitaController extends Controller
{
     public function index()
    {
        // Obtener todas las citas
        $citas = Cita::all();
        return response()->json($citas, 200);
    }


    public function store(StoreCitaRequest $request)
    {
        //Comprobamos y guardamos la cita
        $validated = $request->validated();
        $cita = Cita::create($validated);
        return response()->json(['message' => 'Cita creada correctamente','data' => $cita], 201);
    }
}
