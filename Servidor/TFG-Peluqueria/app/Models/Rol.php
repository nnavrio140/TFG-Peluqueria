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

    public function usuarios()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}

