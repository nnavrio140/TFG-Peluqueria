<h1>Lista de Usuarios</h1>
<p><a href="{{ route('usuarios.create') }}">Crear nuevo usuario</a></p>
@include('usuarios._list')
<a href="{{ route('dashboard.index') }}">Volver al inicio</a>