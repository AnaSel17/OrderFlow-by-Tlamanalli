@extends('adminlte::page')

@section('title', 'Editar Zona')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="m-0 text-dark">
            <i class="fas fa-map-marker-alt"></i> Editar Zona: {{ $zona->nombre }}
        </h1>
        <a href="{{ route('zonas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Errores --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- CARD --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('zonas.update', $zona) }}">
                @csrf
                @method('PUT')

                {{-- Nombre + Estado --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" 
                               name="nombre" 
                               class="form-control"
                               value="{{ old('nombre', $zona->nombre) }}" 
                               required>
                    </div>

                    <div class="col-md-6 d-flex align-items-center mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="zonaActivaSwitch"
                                   name="activa"
                                   value="1"
                                   {{ old('activa', $zona->activa) ? 'checked' : '' }}>

                            <label class="form-check-label ms-2" for="zonaActivaSwitch">
                                Zona activa
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Horarios --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora de apertura</label>
                        <input type="time" 
                               name="hora_apertura"
                               class="form-control"
                               value="{{ old('hora_apertura', $zona->hora_apertura) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora de cierre</label>
                        <input type="time" 
                               name="hora_cierre"
                               class="form-control"
                               value="{{ old('hora_cierre', $zona->hora_cierre) }}">
                    </div>
                </div>

                {{-- Días activos --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Días activos</label>

                    <div class="d-flex flex-wrap gap-3 dias-activos-group border rounded p-3">

                        @php
                            $diasSeleccionados = old('dias_activos', $zona->dias_activos ?? []);
                        @endphp

                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                            <div class="form-check me-3 d-flex align-items-center">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="dias_activos[]"
                                       value="{{ $dia }}"
                                       id="dia_{{ $dia }}"
                                       {{ in_array($dia, $diasSeleccionados) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="dia_{{ $dia }}">
                                    {{ $dia }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" 
                              class="form-control"
                              rows="3">{{ old('descripcion', $zona->descripcion) }}</textarea>
                </div>

                {{-- Botones --}}
                <div class="text-end mt-4">
                    <a href="{{ route('zonas.index') }}" class="btn btn-secondary me-2">
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
