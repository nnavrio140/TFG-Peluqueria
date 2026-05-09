<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festivo extends Model
{
    protected $fillable = [
        'fecha',
        'nombre',
        'recurrente',
        'activo',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}