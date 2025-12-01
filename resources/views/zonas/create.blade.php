@extends('adminlte::page')

@section('title', isset($zona) ? 'Editar Zona' : 'Nueva Zona')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">
    <h1 class="mb-4">{{ isset($zona) ? 'Editar Zona' : 'Registrar Nueva Zona' }}</h1>

    <form method="POST" action="{{ isset($zona) ? route('zonas.update', $zona) : route('zonas.store') }}">
        @csrf
        @if(isset($zona)) @method('PUT') @endif

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $zona->nombre ?? '') }}" required>
            </div>


            <div class="col-md-4">
                <label>Activa</label><br>
                <input type="checkbox" name="activa" value="1" {{ old('activa', $zona->activa ?? true) ? 'checked' : '' }}> Disponible
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Hora de apertura</label>
                <input type="time" name="hora_apertura" class="form-control" value="{{ old('hora_apertura', $zona->hora_apertura ?? '') }}">
            </div>

            <div class="col-md-6">
                <label>Hora de cierre</label>
                <input type="time" name="hora_cierre" class="form-control" value="{{ old('hora_cierre', $zona->hora_cierre ?? '') }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Días activos</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                    <div>
                        <input type="checkbox" name="dias_activos[]" value="{{ $dia }}"
                            {{ in_array($dia, old('dias_activos', $zona->dias_activos ?? [])) ? 'checked' : '' }}>
                        {{ $dia }}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion', $zona->descripcion ?? '') }}</textarea>
        </div>

        <div class="text-end">
            <a href="{{ route('zonas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>
@endsection
