<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Cita;

class Servicio extends Model
{

    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'precio',
        'duracion'
    ];

    // Relaciones
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio');
    }
}

