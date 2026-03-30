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
        'user_id',
    ];

    // Relación 1:1
    // Un empleado pertenece a un usuario
    /**
     * Relación de empleado con su usuario correspondiente.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación 1:N
    // Un empleado puede tener varios horarios
    /**
     * Horarios de trabajo del empleado.
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_empleado');
    }

    // Relación 1:N
    // Un empleado puede atender muchas citas
    /**
     * Citas que tiene asignadas este empleado.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_empleado');
    }
}
