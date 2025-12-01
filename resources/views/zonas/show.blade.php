@extends('adminlte::page')
@section('title', 'Detalles de Zona')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">
    <h1>Zona: {{ $zona->nombre }}</h1>
    <p><strong>Descripción:</strong> {{ $zona->descripcion }}</p>
    <p><strong>Horario:</strong> {{ $zona->hora_apertura }} - {{ $zona->hora_cierre }}</p>
    <p><strong>Días activos:</strong> {{ implode(', ', $zona->dias_activos ?? []) }}</p>
<p><strong>Estado actual:</strong>
    <span class="badge px-3 py-2 {{ $zona->esta_abierta ? 'badge-success' : 'badge-danger' }}">
        {{ $zona->esta_abierta ? 'Abierta' : 'Cerrada' }}
    </span>
</p>



    <a href="{{ route('zonas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
