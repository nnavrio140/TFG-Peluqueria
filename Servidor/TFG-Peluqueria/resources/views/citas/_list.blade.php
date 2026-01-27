@if ($citas->isEmpty())
    <p>No hay citas disponibles hoy.</p>
@else
<ul>
    @foreach($citas as $cita)
        <li>
            {{ $cita->fecha }} - 
            {{ $cita->hora_inicio }} - 
            Usuario: {{ $cita->usuario?->nombre ?? '—' }} -
            Empleado: {{ $cita->empleado?->usuario?->nombre ?? '—' }} -
            Servicio: {{ $cita->servicio?->nombre_servicio ?? '—' }} -
            Estado: {{ $cita->estado?->nombre_estado ?? '—' }}
            <a href="{{ route('citas.show', $cita) }}">Ver</a> -
                <a href="{{ route('citas.edit', $cita) }}">Editar</a> -
                <form action="{{ route('citas.destroy', $cita) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button>Eliminar</button>
                </form>

        </li>
    @endforeach
</ul>
@endif