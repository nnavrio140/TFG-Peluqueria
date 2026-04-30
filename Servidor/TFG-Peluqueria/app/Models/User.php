<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Rol;
use App\Models\Cita;
use App\Models\Empleado;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

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

    // 🔹 RELACIONES
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

    // 🔹 ROLES
    public function isAdmin()
    {
        $role = Rol::where('slug', 'admin')->first();
        return $role && $this->role_id === $role->id;
    }

    public function isEmployee()
    {
        $role = Rol::where('slug', 'empleado')->first();
        return $role && $this->role_id === $role->id;
    }

    public function isUser()
    {
        $role = Rol::where('slug', 'usuario')->first();
        return $role && $this->role_id === $role->id;
    }
}