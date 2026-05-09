<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\HorarioResource;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    /**
     * Lista empleados activos.
     * Opcionalmente filtra por servicio usando ?servicio_id=XX
     */
    public function index()
    {
        $query = Empleado::with(['usuario', 'horarios'])->where('activo', true);

        // Filtrar por servicio si viene el query
        if ($servicioId = request()->query('servicio_id')) {
            $query->whereHas('citas', function($q) use ($servicioId) {
                $q->where('id_servicio', $servicioId);
            });
        }

        $empleados = $query->get();
        return EmpleadoResource::collection($empleados);
    }

    /**
     * Devuelve el detalle de un empleado.
     * Incluye usuario y horarios relacionados.
     */
    public function show(Empleado $empleado)
    {
        return new EmpleadoResource($empleado->load(['usuario', 'horarios']));
    }

    /**
     * Devuelve solo los horarios de un empleado.
     * Útil para validar disponibilidad en React.
     */
    public function horarios(Empleado $empleado)
    {
        return HorarioResource::collection($empleado->horarios);
    }
}