<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Resources\ServicioResource;

class ServicioController extends Controller
{
    /**
     * Devuelve todos los servicios disponibles.
     * Usado en React para la pantalla de selección de servicio.
     */
    public function index()
    {
        $servicios = Servicio::all();
        return ServicioResource::collection($servicios);
    }

    /**
     * Crea un servicio nuevo.
     * Endpoint de administración para añadir servicios.
     */
    public function store(StoreServicioRequest $request)
    {
        $validated = $request->validated();
        $servicio = Servicio::create($validated);

        return response()->json([
            'message' => 'Servicio creado correctamente',
            'data' => new ServicioResource($servicio)
        ], 201);
    }

    /**
     * Devuelve los datos de un servicio concreto.
     */
    public function show(Servicio $servicio)
    {
        return new ServicioResource($servicio);
    }

    /**
     * Actualiza los datos de un servicio existente.
     */
    public function update(StoreServicioRequest $request, Servicio $servicio)
    {
        $validated = $request->validated();
        $servicio->update($validated);

        return response()->json([
            'message' => 'Servicio actualizado correctamente',
            'data' => new ServicioResource($servicio)
        ], 200);
    }

    /**
     * Elimina un servicio de la base de datos.
     */
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return response()->json([
            'message' => 'Servicio eliminado correctamente'
        ], 200);
    }
}