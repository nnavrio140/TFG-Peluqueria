@if ($usuarios->isEmpty())
    <p>No hay usuarios disponibles.</p>
@else
    <ul>
        @foreach ($usuarios as $usuario)
            <li>
                {{ $usuario->nombre }} -
                {{ $usuario->rol->slug }} -
                {{ $usuario->email }} -
                <a href="{{ route('usuarios.show', $usuario) }}">Ver</a> -
                <a href="{{ route('usuarios.edit', $usuario) }}">Editar</a> -
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button>Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif
