<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Empleado;

class Horario extends Model
{
    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'id_empleado',
    ];

    // Relación N:1
    // Un horario pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }
}

