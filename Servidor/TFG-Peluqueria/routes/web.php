<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UserController;


// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home y páginas estáticas
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');

Route::middleware('auth')->group(function () {
    // Rutas protegidas por autenticación
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('citas', CitaController::class)->except(['index']);
    
    // Ver servicios disponible para todos
    Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::get('/servicios/{servicio}', [ServicioController::class, 'show'])->name('servicios.show');
    
    // Rutas protegidas por rol: solo admin y empleados
    Route::middleware('admin_or_employee')->group(function () {
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
        
        Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
        Route::get('/servicios/create', [ServicioController::class, 'create'])->name('servicios.create');
        Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
        Route::put('/servicios/{servicio}', [ServicioController::class, 'update'])->name('servicios.update');
        Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
        
        Route::resource('usuarios', UserController::class);
    });
});

