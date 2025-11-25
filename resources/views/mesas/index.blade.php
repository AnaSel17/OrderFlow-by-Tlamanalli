@extends('adminlte::page')
@section('title', 'Gestión de Mesas')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/empleados.css') }}">
@endpush

@section('content')
<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    {{-- Mensajes de éxito o error --}}
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

    {{-- Encabezado --}}
    <div class="controls-container">
        <h3 class="card-title-custom text-center">GESTIÓN DE MESAS</h3>

        <form action="{{ route('mesas.index') }}" method="GET" id="filtersForm" class="mt-4">
            <div class="row gx-3 align-items-center justify-content-center">
                <div class="col-md-5">
                    <input type="search" name="search" id="searchInput" class="form-control-dark"
                        placeholder="Buscar por código o estado..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="estado" id="estadoSelect" class="form-select-dark">
                        <option value="">Todos los estados</option>
                        <option value="disponible" {{ request('estado')=='disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="ocupada" {{ request('estado')=='ocupada' ? 'selected' : '' }}>Ocupada</option>
                        <option value="reservada" {{ request('estado')=='reservada' ? 'selected' : '' }}>Reservada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('mesas.index') }}" class="btn btn-clear w-100">Limpiar</a>
                </div>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('mesas.create') }}" class="btn btn-add-user" title="Agregar Mesa">
                <i class="bi bi-plus-lg"></i> Agregar Mesa
            </a>
        </div>
    </div>

    {{-- Tabla de Mesas --}}
    <div class="table-card">
        <div class="table-responsive-custom">
            <table class="table-tonalli">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mesas as $mesa)
                    <tr>
                        <td>{{ $mesa->id }}</td>
                        <td>{{ $mesa->codigo }}</td>
                        <td>{{ $mesa->capacidad }}</td>
                        <td>
                            @if($mesa->estado === 'disponible')
                                <span class="badge bg-success">Disponible</span>
                            @elseif($mesa->estado === 'ocupada')
                                <span class="badge bg-warning text-dark">Ocupada</span>
                            @else
                                <span class="badge bg-info text-dark">Reservada</span>
                            @endif
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('mesas.edit', $mesa) }}" class="btn-tonalli btn-sm">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            <form action="{{ route('mesas.destroy', $mesa) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta mesa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tonalli-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron mesas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const form = document.getElementById('filtersForm');
const searchInput = document.getElementById('searchInput');
const estadoSelect = document.getElementById('estadoSelect');

function debounce(func, delay) {
  let timeout;
  return (...args) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}

function safeSubmit() {
  form.requestSubmit();
}

searchInput.addEventListener('input', debounce(safeSubmit, 500));
estadoSelect.addEventListener('change', safeSubmit);
</script>
@endpush
