<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Estado extends Model
{
    protected $table = 'estados';

    protected $fillable = [
        'slug',
        'nombre_estado',
        'descripcion'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'estado_id');
    }
}