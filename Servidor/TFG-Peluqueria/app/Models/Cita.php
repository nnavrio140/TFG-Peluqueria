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

    /**
     * Relación de cita con el servicio reservado.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    /**
     * Relación de cita con el usuario que la reservó.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación de cita con el empleado asignado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    /**
     * Relación de cita con su estado actual.
     */
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    /**
     * Historial de cambios de estado o acciones sobre la cita.
     */
    public function historial()
    {
        return $this->hasMany(HistorialCita::class, 'id_cita');
    }

    // Scope para filtrar citas por fecha y usuario es necesario que se llame scope 
    public function scopeParaDashboard($query, User $usuario)
    {
        //Significa que cuando hagas ->get(), Laravel traerá en la misma consulta los datos relacionados de usuario, servicio, empleado y estado, evitando consultas adicionales para cada relación.
        $query->with(['usuario', 'servicio', 'empleado', 'estado']);

        //Si es admin que muestre todas las de hoy
        if ($usuario->isAdmin()) {
            return $query->whereDate('fecha', Carbon::today());
        }

        //Si es empleado que muestre solo las de hoy y las suyas
        if ($usuario->isEmployee()) {
            return $query->whereDate('fecha', Carbon::today())
                        ->where('id_empleado', $usuario->empleado->id);
        }

        //Si es cliente que muestre solo las suyas
        return $query->where('user_id', $usuario->id);
    }
}
