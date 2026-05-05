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
*/

// 🔓 AUTH públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// 🔵 GOOGLE LOGIN
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// 🔓 SERVICIOS públicos
Route::get('/servicios', [ServicioController::class, 'index']);

// 🔓 EMPLEADOS (FUERA DE AUTH PARA PRUEBAS)
Route::get('/empleados', [EmpleadoController::class, 'index']);

// 🔒 RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Servicios protegidos
    Route::post('/servicios', [ServicioController::class, 'store']);
    Route::get('/servicios/{servicio}', [ServicioController::class, 'show']);
    Route::put('/servicios/{servicio}', [ServicioController::class, 'update']);
    Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy']);

    // EMPLEADOS (protegido también si lo quieres luego)
    Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show']);
    Route::get('/empleados/{empleado}/horarios', [EmpleadoController::class, 'horarios']);

    // CITAS
    Route::get('/citas', [CitaController::class, 'index']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::get('/citas/disponibilidad', [CitaController::class, 'disponibilidad']);
    Route::get('/citas/{cita}', [CitaController::class, 'show']);
    Route::put('/citas/{cita}', [CitaController::class, 'update']);
    Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);
});