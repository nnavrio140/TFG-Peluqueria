<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Resources\ServicioResource;

class ServicioController extends Controller
{
    public function index()
    {
        // Obtener todos los servicios
        //$servicios = Servicio::all();
        // $servicios = Servicio::with('tareas')->paginate(2);
        $servicios = Servicio::paginate(2);
        //return response()->json($servicios, 200);
        return ServicioResource::collection($servicios);
    }

    public function store(StoreServicioRequest $request)
    {
        //Comprobamos y guardamos el servicio
        $validated = $request->validated();
        $servicio = Servicio::create($validated);
        return response()->json(['message' => 'Servicio creado correctamente','data' => $servicio], 201);
    }
}
