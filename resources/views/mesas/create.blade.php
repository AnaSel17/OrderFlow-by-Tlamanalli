@extends('adminlte::page')
@section('title', 'Agregar Mesa')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    <h3 class="card-title-custom text-center">AGREGAR MESA</h3>

    <div class="card p-4 shadow-sm">

        {{-- ERRORES GENERALES --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('mesas.store') }}">
            @csrf

            {{-- CÓDIGO --}}
            <div class="mb-3">
                <label for="codigo" class="form-label">Código de Mesa</label>
                <input type="text" name="codigo" id="codigo" 
                    class="form-control" required
                    placeholder="Ejemplo: M01" 
                    value="{{ old('codigo') }}">
            </div>

            {{-- CAPACIDAD --}}
            <div class="mb-3">
                <label for="capacidad" class="form-label">Capacidad</label>
                <input type="number" name="capacidad" id="capacidad" 
                    class="form-control" min="1" max="20" required
                    placeholder="Número de personas"
                    value="{{ old('capacidad') }}">
            </div>

            {{-- ESTADO --}}
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="disponible" {{ old('estado')=='disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="ocupada" {{ old('estado')=='ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="reservada" {{ old('estado')=='reservada' ? 'selected' : '' }}>Reservada</option>
                    <option value="mantenimiento" {{ old('estado')=='mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
            </div>

            {{-- TIPO --}}
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Mesa</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="mesa" {{ old('tipo')=='mesa' ? 'selected' : '' }}>Mesa</option>
                    <option value="barra" {{ old('tipo')=='barra' ? 'selected' : '' }}>Barra</option>
                    <option value="terraza" {{ old('tipo')=='terraza' ? 'selected' : '' }}>Terraza</option>
                </select>
            </div>

            {{-- ZONA --}}
            <div class="mb-3">
                <label for="zona_id" class="form-label">Zona</label>
                <select name="zona_id" id="zona_id" class="form-select" required>
                    <option value="">-- Selecciona una zona --</option>
                    @foreach ($zonas as $zona)
                        <option value="{{ $zona->id }}" 
                            {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nombre }} ({{ $zona->activa ? 'Activa' : 'Cerrada' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('mesas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>

</div>
@endsection

