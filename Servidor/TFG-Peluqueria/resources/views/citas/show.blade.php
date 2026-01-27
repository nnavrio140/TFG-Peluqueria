 <h1>Ver Cita</h1>

   Fecha: </br>
    <input type="text" value="{{$cita->fecha }}" disabled></br></br>
   Hora: </br>
    <input type="text" value="{{$cita->hora_inicio }}" disabled></br></br>
   Usuario: </br>
    <input type="text" value="{{$cita->usuario->nombre }}" disabled></br></br>
   Empleado: </br>
    <input type="text" value="{{$cita->empleado->usuario->nombre }}" disabled></br></br>
   Servicio: </br>
    <input type="text" value="{{$cita->servicio->nombre_servicio }}" disabled></br></br>
   Estado: </br>
    <input type="text" value="{{$cita->estado->nombre_estado }}" disabled></br></br>

    <a href="{{ route('citas.index') }}">Volver</a>