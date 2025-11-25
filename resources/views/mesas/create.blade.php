@extends('adminlte::page')
@section('title', 'Agregar Mesa')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/empleados.css') }}">
@endpush

@section('content')
<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    <h3 class="card-title-custom text-center">AGREGAR MESA</h3>

    <div class="card p-4 shadow-sm">
        <form method="POST" action="{{ route('mesas.store') }}">
            @csrf

            <div class="mb-3">
                <label for="codigo" class="form-label">Código de Mesa</label>
                <input type="text" name="codigo" id="codigo" class="form-control" required
                       placeholder="Ejemplo: M01" value="{{ old('codigo') }}">
                @error('codigo')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="capacidad" class="form-label">Capacidad</label>
                <input type="number" name="capacidad" id="capacidad" class="form-control" min="1" max="20" required
                       placeholder="Número de personas" value="{{ old('capacidad') }}">
                @error('capacidad')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="disponible" selected>Disponible</option>
                    <option value="ocupada">Ocupada</option>
                    <option value="reservada">Reservada</option>
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn-tonalli">Guardar Mesa</button>
                <a href="{{ route('mesas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

</div>
@endsection
