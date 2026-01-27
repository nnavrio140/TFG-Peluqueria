 <h1>Editar Cita</h1>
    <form action="{{ route('citas.update', $cita) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Fecha</label>
        <input type="date" name="fecha" value="{{ old('fecha', $cita->fecha) }}"><br><br>
        @error('fecha')
        <div class="error">{{ $message }}</div>
        @enderror

        <label>Hora de inicio</label>
        <input type="time" name="hora_inicio" value="{{ old('hora_inicio', $cita->hora_inicio) }}"><br><br>
        @error('hora_inicio')
            <div class="error">{{ $message }}</div>
        @enderror
        
        @if(auth()->user()->rol->slug === 'admin' || auth()->user()->rol->slug === 'empleado')
        <label>Usuario</label>
        <select name="user_id">
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
            @foreach($estados as $estado)
                <option value="{{ $estado->id }}" {{ old('id_estado') == $estado->id ? 'selected' : '' }}>
                    {{ $estado->nombre_estado }}
                </option>
            @endforeach
        </select></br></br>
        @error('id_estado')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Editar</button>
</form>
