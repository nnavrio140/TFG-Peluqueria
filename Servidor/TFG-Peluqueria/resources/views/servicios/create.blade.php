 <h1>Ver Crear Servicio</h1>
<form action="{{ route('servicios.store') }}" method="POST">
    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre_servicio" value="{{ old('nombre_servicio') }}"></br></br>
    @error('nombre_servicio')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Descripción</label>
    <textarea name="descripcion">{{ old('descripcion') }}</textarea></br></br>
    @error('descripcion')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Precio</label>
    <input type="number" name="precio" step="0.01" value="{{ old('precio') }}"></br></br>
    @error('precio')
        <div class="error">{{ $message }}</div>
    @enderror

    <label>Duración (minutos)</label>
    <input type="number" name="duracion" min="1" value="{{ old('duracion') }}"></br></br>
    @error('duracion')
        <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Crear</button>
</form>
