<ul>
    @foreach($citas as $cita)
        <li>{{ $cita->fecha }} - {{ $cita->hora_inicio }} - Usuario ID: {{ $cita->id_usuario }} - Empleado ID: {{ $cita->id_empleado }} - Servicio ID: {{ $cita->id_servicio }} - Estado ID: {{ $cita->id_estado }}</li>
    @endforeach
</ul>