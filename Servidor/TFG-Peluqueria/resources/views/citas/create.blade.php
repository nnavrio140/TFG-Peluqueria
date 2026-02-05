<h1>Crear Cita</h1>
<form action="{{ route('citas.store') }}" method="POST">
    @csrf

    <label>Fecha</label>
    <input type="date" name="fecha" value="{{ old('fecha') }}"></br></br>
    @error('fecha')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Hora de inicio</label>
    <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}"></br></br>
    @error('hora_inicio')
        <div class="error">{{ $message }}</div>
    @enderror
    
    @if(auth()->user()->isAdminOrEmploye())
    <label>Usuario</label>
    <select name="user_id">
        <option value="">Seleccione un usuario</option>
        @foreach($usuarios as $usuario)
            <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                {{ $usuario->nombre }}
            </option>
        @endforeach
    </select><br><br>

    @error('user_id')
        <div class="error">{{ $message }}</div>
    @enderror
    @else
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
    @endif

    
    <label>Empleado</label>
    <select name="id_empleado">
        <option value="">Seleccione un empleado</option>
        @foreach($empleados as $empleado)
            <option value="{{ $empleado->id }}" {{ old('id_empleado') == $empleado->id ? 'selected' : '' }}>
                {{ $empleado->usuario->nombre }}
            </option>
        @endforeach
    </select></br></br>
    @error('id_empleado')
        <div class="error">{{ $message }}</div>
    @enderror

     <label>Servicio</label>
    <select name="id_servicio">
        <option value="">Seleccione un servicio</option>
        @foreach($servicios as $servicio)
            <option value="{{ $servicio->id }}" {{ old('id_servicio') == $servicio->id ? 'selected' : '' }}>
                {{ $servicio->nombre_servicio }}
            </option>
        @endforeach
    </select></br></br>
    @error('id_servicio')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Estado</label>
    <select name="id_estado">
        <option value="">Seleccione un estado</option>
        @foreach($estados as $estado)
            <option value="{{ $estado->id }}" {{ old('id_estado') == $estado->id ? 'selected' : '' }}>
                {{ $estado->nombre_estado }}
            </option>
        @endforeach
    </select></br></br>
    @error('id_estado')
        <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Crear</button>
</form>
