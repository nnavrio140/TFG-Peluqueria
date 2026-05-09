<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cita;
use App\Models\Estado;

class HistorialCita extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'cita_id',
        'estado_id',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}