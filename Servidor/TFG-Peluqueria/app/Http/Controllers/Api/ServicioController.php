<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;

class ServicioController extends Controller
{
    public function index()
    {
        // Obtener todos los servicios
        $servicios = Servicio::all();
        return $servicios->toJson();
    }

    public function store(StoreServicioRequest $request)
    {
        //Comprobamos y guardamos el servicio
        $validated = $request->validated();
        $servicio = Servicio::create($validated);
        return response()->json(['message' => 'Servicio creado correctamente','data' => $servicio], 201);
    }
}
