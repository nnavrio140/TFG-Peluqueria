<h1>Lista de Servicios</h1>
@if (auth()->user()->rol->slug === 'admin' || auth()->user()->rol->slug === 'empleado')
<p><a href="{{ route('servicios.create') }}">Crear nuevo servicio</a></p>
@endif
@include('servicios._list')
<a href="{{ route('dashboard.index') }}">Volver al inicio</a>