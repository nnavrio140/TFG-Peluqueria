@if ($usuarios->isEmpty())
    <p>No hay usuarios disponibles.</p>
@else
    <ul>
        @foreach ($usuarios as $usuario)
            <li>
                {{ $usuario->nombre }} -
                {{ $usuario->rol->slug }} -
                {{ $usuario->email }} 
                 <!-- Solo lo mostramos a rol admin o empleado -->
                @if (auth()->user()->rol->slug === 'admin' || auth()->user()->rol->slug === 'empleado')
                -
                <a href="{{ route('usuarios.show', $usuario) }}">Ver</a> -
                <a href="{{ route('usuarios.edit', $usuario) }}">Editar</a> -
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button>Eliminar</button>
                @endif
                </form>
            </li>
        @endforeach
    </ul>
@endif
