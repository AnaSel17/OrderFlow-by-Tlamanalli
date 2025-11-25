@extends('adminlte::page')

@section('title', 'Zonas')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">

    {{-- 🧭 Encabezado principal --}}
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <div>
            <h1 class="m-0 text-dark">Zonas del Restaurante</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-clock"></i> Hora del sistema: 
                <strong>{{ now('America/Mexico_City')->format('H:i:s') }}</strong>
            </p>
        </div>
        <a href="{{ route('zonas.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nueva Zona
        </a>
    </div>

    {{-- 🟢 Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 📋 Tabla principal --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Horario</th>
                        <th>Días activos</th>
                        <th>Estado</th>
                        <th>Color</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zonas as $zona)
                        <tr>
                            <td>{{ $zona->id }}</td>
                            <td class="fw-semibold">{{ $zona->nombre }}</td>

                            {{-- Horario --}}
                            <td>
                                {{ $zona->hora_apertura }} - {{ $zona->hora_cierre }}
                            </td>

                            {{-- Días activos --}}
                            <td>
                                @if($zona->dias_activos)
                                    {{ is_array($zona->dias_activos)
                                        ? implode(', ', $zona->dias_activos)
                                        : implode(', ', json_decode($zona->dias_activos, true) ?? []) }}
                                @else
                                    <span class="text-muted">No definidos</span>
                                @endif
                            </td>

                            {{-- Estado dinámico --}}
                            <td>
                                <span 
                                    class="badge px-3 py-2 {{ $zona->esta_abierta ? 'bg-success' : 'bg-danger' }}"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Horario: {{ $zona->hora_apertura }} - {{ $zona->hora_cierre }}">
                                    <i class="fas {{ $zona->esta_abierta ? 'fa-door-open' : 'fa-door-closed' }}"></i>
                                    {{ $zona->esta_abierta ? 'Abierta' : 'Cerrada' }}
                                </span>
                            </td>

                            {{-- Color visual --}}
                            <td>
                                <span 
                                    class="badge text-dark fw-bold"
                                    style="background-color: {{ $zona->color_hex }}; border: 1px solid #888;">
                                    {{ strtoupper($zona->color_hex) }}
                                </span>
                            </td>

                            {{-- Botones de acción --}}
                            <td>
                                <a href="{{ route('zonas.show', $zona) }}" class="btn btn-sm btn-primary" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('zonas.edit', $zona) }}" class="btn btn-sm btn-warning" title="Editar zona">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('zonas.destroy', $zona) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar zona?')" title="Eliminar zona">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Caso sin registros --}}
                        <tr>
                            <td colspan="7" class="text-muted py-4">
                                <i class="fas fa-info-circle"></i> No hay zonas registradas.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Mensaje cuando todas están cerradas --}}
                    @if($zonas->count() > 0 && $zonas->where('esta_abierta', true)->count() === 0)
                        <tr>
                            <td colspan="7" class="text-muted py-4">
                                <i class="fas fa-moon"></i> Todas las zonas están cerradas actualmente.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- 📄 Paginación --}}
    <div class="mt-3">
        {{ $zonas->links() }}
    </div>
</div>
@endsection

@push('js')
<script>
    // Activa tooltips de Bootstrap
    document.addEventListener("DOMContentLoaded", function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
