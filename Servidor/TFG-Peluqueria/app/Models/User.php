<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Rol;
use App\Models\Cita;
use App\Models\Empleado;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = [
        'nombre',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación N:1
    // Un usuario pertenece a un solo rol
    /**
     * Relación con el rol del usuario (admin, empleado, usuario).
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'role_id');
    }

    // Relación 1:N
    // Un usuario puede tener muchas citas
    /**
     * Todas las citas reservadas por este usuario.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'user_id');
    }

    // Relación 1:1
    // Un usuario puede ser un empleado
    /**
     * Relación opcional con el registro de empleado si el usuario es peluquero.
     */
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'user_id');
    }

    // Verificar si el usuario es administrador
    /**
     * Comprueba si el usuario tiene rol de administrador.
     */
    public function isAdmin()
    {
        $administratorRole = Rol::where('slug', 'admin')->first();
        return $this->role_id === $administratorRole->id;
    }

    // Verificar si el usuario es administrador o empleado
    /**
     * Comprueba si el usuario es administrador o empleado.
     */
    public function isAdminOrEmploye()
    {
        $administratorRole = Rol::where('slug', 'admin')->first();
        $employeeRole = Rol::where('slug', 'empleado')->first();
        return $this->role_id === $administratorRole->id || $this->role_id === $employeeRole->id;
    }

    /**
     * Comprueba si el usuario tiene rol de empleado.
     */
    public function isEmployee()
    {
        $employeeRole = Rol::where('slug', 'empleado')->first();
        return $this->role_id === $employeeRole->id;
    }

    /**
     * Comprueba si el usuario tiene rol de cliente/usuario.
     */
    public function isUser()
    {
        $userRole = Rol::where('slug', 'usuario')->first();
        return $this->role_id === $userRole->id;
    }

    
}