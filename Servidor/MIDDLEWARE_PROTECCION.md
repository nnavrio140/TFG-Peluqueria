# Protección de Rutas con Middleware

## Estructura Implementada

### 1. Middleware Creados

#### `IsAdminOrEmployee.php`
- Verifica que el usuario sea **admin** o **empleado**
- Se aplica a rutas de servicios y usuarios
- Redirige a dashboard si no cumple los requisitos

#### `IsAdmin.php`
- Verifica que el usuario sea únicamente **admin**
- Disponible para uso futuro en rutas que requieran solo admin

### 2. Registro en `bootstrap/app.php`

Los middleware se registran como alias:
```php
'admin_or_employee' => \App\Http\Middleware\IsAdminOrEmployee::class,
'admin' => \App\Http\Middleware\IsAdmin::class,
```

### 3. Rutas Protegidas

#### Web Routes (`routes/web.php`)
```php
Route::middleware('auth')->group(function () {
    // Disponibles para todos los usuarios autenticados
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('citas', CitaController::class);
    
    // Solo para admin y empleados
    Route::middleware('admin_or_employee')->group(function () {
        Route::resource('servicios', ServicioController::class);
        Route::resource('usuarios', UserController::class);
    });
});
```

#### API Routes (`routes/api.php`)
```php
// Solo para admin y empleados
Route::middleware('admin_or_employee')->group(function () {
    Route::get('/servicios', [ServicioController::class, 'index']);
    Route::post('/servicios', [ServicioController::class, 'store']);
});

// Disponibles para todos
Route::get('/citas', [CitaController::class, 'index']);
Route::post('/citas', [CitaController::class, 'store']);
```

## Acceso por Rol

| Recurso | Usuario | Empleado | Admin |
|---------|---------|----------|-------|
| Dashboard | ✅ | ✅ | ✅ |
| Citas (ver, crear, editar) | ✅ | ✅ | ✅ |
| Servicios (ver, crear, editar, eliminar) | ❌ | ✅ | ✅ |
| Usuarios (ver, crear, editar, eliminar) | ❌ | ✅ | ✅ |

## Cómo Funciona

1. **Usuario normal** intenta acceder a `/servicios`
   - Middleware verifica: `auth()->user()->isAdminOrEmploye()`
   - Retorna `false` porque es usuario regular
   - Redirige a dashboard con mensaje: "No tienes permiso para acceder a este recurso"

2. **Empleado** intenta acceder a `/usuarios`
   - Middleware verifica: `auth()->user()->isAdminOrEmploye()`
   - Retorna `true`
   - Permite acceso ✅

3. **Admin** intenta acceder a `/servicios`
   - Middleware verifica: `auth()->user()->isAdminOrEmploye()`
   - Retorna `true`
   - Permite acceso ✅

## Validación Adicional en Request Classes

Los Form Requests también validan autorización:

- **StoreServicioRequest**: Verifica `isAdminOrEmploye()`
- **StoreUserRequest**: Verifica `isAdminOrEmploye()`
- **UpdateUserRequest**: Verifica `isAdminOrEmploye()`

Esto proporciona doble protección: middleware + request validation

## Ventajas de esta Implementación

✅ **Protección en el servidor**: No depende del frontend (como las vistas)
✅ **Doble capa**: Middleware + Request validation
✅ **Fácil mantenimiento**: Centralizado en middleware
✅ **Escalable**: Fácil agregar más permisos
✅ **Consistente**: Usa el mismo sistema de roles que las vistas
