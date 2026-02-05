<h1>Lista de Usuarios</h1>
 @if (auth()->user()->isAdminOrEmploye())
<p><a href="{{ route('usuarios.create') }}">Crear nuevo usuario</a></p>
@endif
@include('usuarios._list')
<a href="{{ route('dashboard.index') }}">Volver al inicio</a>