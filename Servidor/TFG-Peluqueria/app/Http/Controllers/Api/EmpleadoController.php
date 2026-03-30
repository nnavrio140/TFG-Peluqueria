<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\HorarioResource;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    /**
     * Devuelve la lista de empleados activos con sus datos básicos y horarios.
     * Esto sirve para que React muestre qué peluqueros existen.
     */
    public function index()
    {
        $empleados = Empleado::with(['usuario', 'horarios'])->where('activo', true)->get();

        return EmpleadoResource::collection($empleados);
    }

    /**
     * Devuelve el detalle de un empleado. El load es para incluir el usuario y horarios relacionados.
     */
    public function show(Empleado $empleado)
    {
        return new EmpleadoResource($empleado->load(['usuario', 'horarios']));
    }

    /**
     * Devuelve solo los horarios de un empleado.
     * Útil para validar disponibilidad en el frontend.
     */
    public function horarios(Empleado $empleado)
    {
        return HorarioResource::collection($empleado->horarios);
    }
}
