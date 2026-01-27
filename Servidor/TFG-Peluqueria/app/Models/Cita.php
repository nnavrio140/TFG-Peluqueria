<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Servicio;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\HistorialCita;


class Cita extends Model
{
    protected $fillable = [
        'fecha',
        'hora_inicio',
        'user_id',
        'id_servicio',
        'id_estado',
        'id_empleado'
    ];

    // Necesario si tiene factory
    use HasFactory;

    // Relaciones correctas

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    public function historial()
    {
        return $this->hasMany(HistorialCita::class, 'id_cita');
    }
}
