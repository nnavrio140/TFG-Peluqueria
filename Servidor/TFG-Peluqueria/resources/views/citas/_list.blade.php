@if ($citas->isEmpty())
    @if (auth()->user()->isAdmin())
        <p>No hay citas.</p>
    @elseif (auth()->user()->isEmployee())
        <p>No tienes citas para el día de hoy.</p>
    @elseif (auth()->user()->isUser())
        <p>No tienes citas sacadas.</p>
        <a href="{{ route('citas.create') }}">Crear nueva cita</a>
    @endif
@else
<a href="{{ route('citas.create') }}">Crear nueva cita</a> 
    <ul>
        @foreach($citas as $cita)
            <li>
                {{ $cita->fecha }} -
                {{ $cita->hora_inicio }} -
                Usuario: {{ $cita->usuario?->nombre ?? '—' }} -
                Empleado: {{ $cita->empleado?->usuario?->nombre ?? '—' }} -
                Servicio: {{ $cita->servicio?->nombre_servicio ?? '—' }} -
                Estado: {{ $cita->estado?->nombre_estado ?? '—' }} -
                <a href="{{ route('citas.show', $cita) }}">Ver</a> -
                <a href="{{ route('citas.edit', $cita) }}">Editar</a> -

                <form action="{{ route('citas.destroy', $cita) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button>Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>

@endif
