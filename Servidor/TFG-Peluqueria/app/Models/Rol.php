<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'nombre_rol', 
        'descripcion',
        'slug'
        ];

    // Relación 1:N
    // Un rol puede estar asignado a muchos usuarios
    /**
     * Usuarios que tienen este rol.
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}

