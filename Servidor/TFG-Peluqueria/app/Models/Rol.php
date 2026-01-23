<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Rol extends Model
{
    protected $fillable = [
        'nombre_rol', 
        'descripcion',
        'slug'
        ];

    // Relación 1:N
    // Un rol puede estar asignado a muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_rol');
    }
}

