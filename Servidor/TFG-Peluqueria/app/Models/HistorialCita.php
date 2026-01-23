<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Cita;
use App\Models\Estado;

class HistorialCita extends Model
{
    protected $fillable = [
        'fecha',
        'id_cita',
        'id_estado',
    ];

    // Relación N:1
    // Un historial pertenece a una cita
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'id_cita');
    }

    // Relación N:1
    // Un historial referencia un estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }
}
