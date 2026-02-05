   <h1>Editar Servicio</h1>
    <form action="{{ route('servicios.update', $servicio) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre</label>
        <input type="text" name="nombre_servicio" value="{{ $servicio->nombre_servicio }}"/><br/><br/>
        @error('nombre_servicio')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Descripcion</label>
        <textarea type="text" name="descripcion">{{ $servicio->descripcion }}</textarea><br/><br/>
        @error('descripcion')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Precio</label>
        <input type="number" name="precio" step="0.01" value="{{ $servicio->precio }}"/><br/><br/>
        @error('precio')
            <div class="error">{{ $message }}</div> 
        @enderror

        <label>Duracion (minutos)</label>
        <input type="number" name="duracion" min="1" value="{{ $servicio->duracion }}"/><br/><br/>
        @error('duracion')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('dashboard.index') }}">Volver</a>