 <h1>Ver Usuario</h1>
   Nombre</br>
    <input type="text" value="{{ $usuario->nombre }}" disabled></br></br>
   Rol</br>
    <input type="text" value="{{ $usuario->rol->slug }}" disabled></br></br>
   Email</br>
    <input type="text" value="{{ $usuario->email }}" disabled></br></br>
    
    <a href="{{ route('dashboard.index') }}">Volver</a>