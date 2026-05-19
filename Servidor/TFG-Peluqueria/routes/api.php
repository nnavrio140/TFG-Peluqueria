<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\BlogController;

# AUTH públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

# GOOGLE LOGIN
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

# SERVICIOS públicas
Route::get('/servicios', [ServicioController::class, 'index']);
Route::get('/servicios/{servicio}', [ServicioController::class, 'show']);

# EMPLEADOS públicas
Route::get('/empleados', [EmpleadoController::class, 'index']);
Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show']);
Route::get('/empleados/{empleado}/horarios', [EmpleadoController::class, 'horarios']);

# DISPONIBILIDAD pública
Route::get('/disponibilidad', [CitaController::class, 'disponibilidad']);
Route::get('/dias-disponibles', [CitaController::class, 'diasDisponibles']);

# CONTACTO pública
Route::post('/contact', [ContactController::class, 'store']);

# BLOG público
Route::get('/blog', [BlogController::class, 'index']);

# RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    # AUTH
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    # USUARIOS ADMIN
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);

    # CONTACTO ADMIN SOLO VER
    Route::get('/contact', [ContactController::class, 'index']);

    # CITAS
    Route::get('/citas', [CitaController::class, 'index']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::get('/citas/{cita}', [CitaController::class, 'show']);
    Route::put('/citas/{cita}', [CitaController::class, 'update']);
    Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);

    # SERVICIOS ADMIN
    Route::post('/servicios', [ServicioController::class, 'store']);
    Route::put('/servicios/{servicio}', [ServicioController::class, 'update']);
    Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy']);

    # BLOG ADMIN
    Route::post('/blog', [BlogController::class, 'store']);
    Route::put('/blog/{id}', [BlogController::class, 'update']);
    Route::delete('/blog/{id}', [BlogController::class, 'destroy']);
});