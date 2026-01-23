<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Cita;

class Estado extends Model
{
    protected $fillable = [
    'nombre_estado', 
    'descripcion'
    ];

    // Relación 1:N
    // Un estado puede estar en muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_estado', 'id_estado');
    }
}

