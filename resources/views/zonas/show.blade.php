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
        <span class="badge {{ $zona->estaAbierta() ? 'bg-success' : 'bg-danger' }}">
            {{ $zona->estado_texto }}
        </span>
    </p>
    <p><strong>Color visual:</strong> <span class="badge" style="background: {{ $zona->color_hex }}">{{ $zona->color_hex }}</span></p>

    <a href="{{ route('zonas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
