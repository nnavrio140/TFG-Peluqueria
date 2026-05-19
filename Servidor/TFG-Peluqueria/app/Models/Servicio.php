<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre_servicio',
        'descripcion_corta',
        'descripcion',
        'precio',
        'duracion',
        'imagen',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id');
    }
}