<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Rol;
use App\Models\Cita;
use App\Models\Empleado;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'role_id');
    }

    // Relación 1:N
    // Un usuario puede tener muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'user_id');
    }

    // Relación 1:1
    // Un usuario puede ser un empleado
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'user_id');
    }

    // Verificar si el usuario es administrador
    public function isAdmin()
    {
        $administratorRole = Rol::where('slug', 'admin')->first();
        return $this->role_id === $administratorRole->id;
    }

    // Verificar si el usuario es administrador o empleado
    public function isAdminOrEmploye()
    {
        $administratorRole = Rol::where('slug', 'admin')->first();
        $employeeRole = Rol::where('slug', 'empleado')->first();
        return $this->role_id === $administratorRole->id || $this->role_id === $employeeRole->id;
    }

    public function isEmploye()
    {
        $employeeRole = Rol::where('slug', 'empleado')->first();
        return $this->role_id === $employeeRole->id;
    }
}

