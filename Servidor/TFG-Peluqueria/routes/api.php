<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Rutas de la API para:
| - autenticación (login, me, logout)
| - servicios
| - empleados y horarios
| - gestión de citas y disponibilidad
*/

Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Servicios
    Route::get('/servicios', [ServicioController::class, 'index']);
    Route::post('/servicios', [ServicioController::class, 'store']);
    Route::get('/servicios/{servicio}', [ServicioController::class, 'show']);
    Route::put('/servicios/{servicio}', [ServicioController::class, 'update']);
    Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy']);

    //Empleados
    Route::get('/empleados', [EmpleadoController::class, 'index']);
    Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show']);
    Route::get('/empleados/{empleado}/horarios', [EmpleadoController::class, 'horarios']);

    //Citas
    Route::get('/citas', [CitaController::class, 'index']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::get('/citas/disponibilidad', [CitaController::class, 'disponibilidad']);
    Route::get('/citas/{cita}', [CitaController::class, 'show']);
    Route::put('/citas/{cita}', [CitaController::class, 'update']);
    Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);
});