<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Horario;
use App\Models\User;


class Empleado extends Model
{
    protected $fillable = [
        'especialidad',
        'salario',
        'activo',
        'id_usuario',
    ];

    // Relación 1:1
    // Un empleado pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    // Relación 1:N
    // Un empleado puede tener varios horarios
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_empleado', 'id_empleado');
    }

    // Relación 1:N
    // Un empleado puede atender muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_empleado', 'id_empleado');
    }
}
