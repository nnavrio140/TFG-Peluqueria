<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;
use App\Models\User;
use App\Models\Cita;

class Empleado extends Model
{
    protected $fillable = [
        'especialidad',
        'salario',
        'imagen',
        'activo',
        'user_id',
    ];

    protected $appends = [
        'imagen_url',
    ];

    public function getImagenUrlAttribute()
    {
        if (!$this->imagen) {
            return null;
        }

        return asset('storage/' . $this->imagen);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'empleado_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'empleado_id');
    }
}