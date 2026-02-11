<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {

Route::get('/servicios', [ServicioController::class, 'index']);
Route::post('/servicios', [ServicioController::class, 'store']);

Route::get('/citas', [CitaController::class, 'index']);
Route::post('/citas', [CitaController::class, 'store']);
// });