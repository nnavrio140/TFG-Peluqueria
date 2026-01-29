 <h1>Crear Usuario</h1>
<form action="{{ route('usuarios.store') }}" method="POST">
    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre') }}"></br></br>
    @error('nombre')
        <div class="error">{{ $message }}</div>
    @enderror

   <label>Rol</label>
    <select name="role_id">
        <option value="">Seleccione un rol</option>
        @foreach($roles as $rol)
            <option value="{{ $rol->id }}" {{ old('rol') == $rol->id ? 'selected' : '' }}>
                {{ $rol->slug }}
            </option>
        @endforeach
    </select></br></br>
    @error('role_id')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}"></br></br>
    @error('email')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Password</label>
    <input type="password" name="password" value="{{ old('password') }}"></br></br>
    @error('password')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Confirmar Password</label>
    <input type="password" name="password_confirmation"><br><br>
     @error('password_confirmation')
        <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Crear</button>
</form>
