<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Servicio;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\HistorialCita;
use Carbon\Carbon;
use App\Models\User;


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

    // Scope para filtrar citas por fecha y usuario es necesario que se llame scope 
    public function scopeParaDashboard($query, User $usuario)
    {
        $query->with(['usuario', 'servicio', 'empleado', 'estado']);

        if ($usuario->isAdmin()) {
            return $query->whereDate('fecha', Carbon::today());
        }

        if ($usuario->isEmployee()) {
            return $query->whereDate('fecha', Carbon::today())
                        ->where('id_empleado', $usuario->empleado->id);
        }

        return $query->where('user_id', $usuario->id);
    }
}
