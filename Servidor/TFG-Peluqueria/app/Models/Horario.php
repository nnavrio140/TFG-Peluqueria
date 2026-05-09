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
        'empleado_id',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}