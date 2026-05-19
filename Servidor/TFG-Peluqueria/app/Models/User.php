<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Rol;
use App\Models\Cita;
use App\Models\Empleado;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'role_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'user_id');
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->rol && $this->rol->slug === 'admin';
    }

    public function isEmployee()
    {
        return $this->rol && $this->rol->slug === 'empleado';
    }

    public function isUser()
    {
        return $this->rol && $this->rol->slug === 'usuario';
    }

    public function isAdminOrEmploye()
    {
        return $this->isAdmin() || $this->isEmployee();
    }
}