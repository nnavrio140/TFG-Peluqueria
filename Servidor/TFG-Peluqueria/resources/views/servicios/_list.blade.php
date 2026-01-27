@if ($servicios->isEmpty())
    <p>No hay servicios disponibles.</p>
@else
    <ul>
        @foreach ($servicios as $servicio)
            <li>
                {{ $servicio->nombre_servicio }} -
                {{ $servicio->descripcion }} -
                {{ $servicio->precio }}€ -
                {{ $servicio->duracion }} min
                <a href="{{ route('servicios.show', $servicio) }}">Ver</a> -
                <a href="{{ route('servicios.edit', $servicio) }}">Editar</a> -
                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button>Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif
