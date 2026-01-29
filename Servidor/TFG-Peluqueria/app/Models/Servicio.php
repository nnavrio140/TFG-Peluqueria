<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Cita;

class Servicio extends Model
{
    protected $table = 'servicios';
    public $timestamps = true;

    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'precio',
        'duracion',
    ];

    // Relación 1:N
    // Un servicio puede estar asociado a muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio');
    }
}

