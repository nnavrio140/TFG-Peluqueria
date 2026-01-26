<ul>
    @foreach($servicios as $servicio)
        <li>{{ $servicio->nombre_servicio }} - {{ $servicio->descripcion }} - {{ $servicio->precio }}€ - {{ $servicio->duracion }} min</li>
    @endforeach
</ul>