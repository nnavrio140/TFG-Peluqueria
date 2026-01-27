 <h1>Ver Servicio</h1>
   Nombre</br>
    <input type="text" value="{{ $servicio->nombre_servicio }}" disabled></br></br>
   Descripción</br>
    <input type="text" value="{{ $servicio->descripcion }}" disabled></br></br>
   Precio</br>
    <input type="text" value="{{ $servicio->precio }}€" disabled></br></br>
   Duración</br>
    <input type="text" value="{{ $servicio->duracion }} minutos" disabled></br></br>

    <a href="{{ route('servicios.index') }}">Volver</a>