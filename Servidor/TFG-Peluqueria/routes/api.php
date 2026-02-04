<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicioController;

Route::get('/servicios', [ServicioController::class, 'index']);
Route::post('/servicios', [ServicioController::class, 'store']);
