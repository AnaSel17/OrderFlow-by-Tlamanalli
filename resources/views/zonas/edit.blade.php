@extends('adminlte::page')

@section('title', 'Editar Zona')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="m-0 text-dark">
            <i class="fas fa-map-marker-alt"></i> Editar Zona: {{ $zona->nombre }}
        </h1>
        <a href="{{ route('zonas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </div>

    {{-- Mensajes de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensaje de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('zonas.update', $zona) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control"
                            value="{{ old('nombre', $zona->nombre) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Color visual (hex)</label>
                        <input type="color" name="color_hex" class="form-control"
                            value="{{ old('color_hex', $zona->color_hex) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label><br>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="activa" value="1"
                                class="form-check-input" id="zonaActivaSwitch"
                                {{ old('activa', $zona->activa) ? 'checked' : '' }}>
                            <label class="form-check-label" for="zonaActivaSwitch">
                                Zona activa
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora de apertura</label>
                        <input type="time" name="hora_apertura" class="form-control"
                            value="{{ old('hora_apertura', $zona->hora_apertura) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora de cierre</label>
                        <input type="time" name="hora_cierre" class="form-control"
                            value="{{ old('hora_cierre', $zona->hora_cierre) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Días activos</label>
                    <div class="d-flex flex-wrap gap-3 border rounded p-3">
                        @php
                            $diasSeleccionados = old('dias_activos', $zona->dias_activos ?? []);
                        @endphp
                        @foreach(['Lun','Mar','Mie','Jue','Vie','Sab','Dom'] as $dia)
                            <div class="form-check me-3">
                                <input type="checkbox" class="form-check-input"
                                    name="dias_activos[]" value="{{ $dia }}"
                                    id="dia_{{ $dia }}"
                                    {{ in_array($dia, $diasSeleccionados) ? 'checked' : '' }}>
                                <label for="dia_{{ $dia }}" class="form-check-label">{{ $dia }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"
                        placeholder="Ejemplo: Área principal con aire acondicionado...">{{ old('descripcion', $zona->descripcion) }}</textarea>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('zonas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
