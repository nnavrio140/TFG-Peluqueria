<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Servicio;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\User;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'user_id',
        'servicio_id',
        'estado_id',
        'empleado_id'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function scopeParaDashboard($query, User $usuario)
    {
        $query->with(['usuario', 'servicio', 'empleado', 'estado']);

        if ($usuario->isAdmin()) {
            return $query->whereDate('fecha', Carbon::today());
        }

        if ($usuario->isEmployee()) {
            return $query->whereDate('fecha', Carbon::today())
                         ->where('empleado_id', $usuario->empleado->id);
        }

        return $query->where('user_id', $usuario->id);
    }
}