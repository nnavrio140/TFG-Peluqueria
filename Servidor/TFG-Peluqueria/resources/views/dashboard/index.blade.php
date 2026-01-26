@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>DASHBOARD</h1>
    <h2>Lista de Servicios</h2>
    @include('servicios._list')
    <p><a href="{{ route('servicios.index') }}">Lista completa de servicios</a></p>
    <h2>Lista de citas</h2>
    @include('citas._list')
    <p><a href="{{ route('citas.index') }}">Lista completa de citas</a></p>
@endsection