<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Servicio;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\HistorialCita;


class Cita extends Model
{
    protected $fillable = [
        'fecha',
        'hora_inicio',
        'id_usuario',
        'id_servicio',
        'id_estado',
        'id_empleado'
    ];

    // Relaciones correctas

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
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
