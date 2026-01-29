   <h1>Editar Usuario</h1>
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}"></br></br>
        @error('nombre')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Rol</label>
            <select name="role_id">
            @foreach($roles as $rol)
                   <option value="{{ $rol->id }}" {{ old('role_id', $usuario->role_id) == $rol->id ? 'selected' : '' }}>
                        {{ $rol->slug }}
                    </option>
                @endforeach
            </select></br></br>
        @error('role_id')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $usuario->email) }}"></br></br>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('usuarios.index') }}">Volver</a>