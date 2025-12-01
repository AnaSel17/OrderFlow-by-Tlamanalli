@extends('adminlte::page')
@section('title', 'Mesas')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">

    {{-- 🧭 Encabezado principal --}}
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <div>
            <h1 class="m-0 text-dark">Mesas del Restaurante</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-clock"></i> Hora del sistema:
                <strong>{{ now('America/Mexico_City')->format('H:i:s') }}</strong>
            </p>
        </div>

        {{-- Botón Nueva Mesa --}}
        <a href="{{ route('mesas.create') }}" class="badge badge-success px-3 py-2">
            <i class="fas fa-plus"></i> Nueva Mesa
        </a>
    </div>

    {{-- 🟢 Mensajes --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔎 Filtros --}}
    <div class="card card-outline card-primary shadow-sm px-4 py-3 mb-3">
        <form action="{{ route('mesas.index') }}" method="GET" id="filtersForm">
            <div class="row g-3 align-items-center">

                <div class="col-md-5">
                    <input type="search" name="search" id="searchInput"
                        class="form-control-tonalli"
                        placeholder="Buscar código o estado…"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-4">
                    <select name="estado" id="estadoSelect" class="form-select-tonalli">
                        <option value="">Todos los estados</option>
                        <option value="disponible" {{ request('estado')=='disponible'?'selected':'' }}>Disponible</option>
                        <option value="ocupada" {{ request('estado')=='ocupada'?'selected':'' }}>Ocupada</option>
                        <option value="reservada" {{ request('estado')=='reservada'?'selected':'' }}>Reservada</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <a href="{{ route('mesas.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>

            </div>
        </form>
    </div>

    {{-- 📋 Tabla de Mesas --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-center align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mesas as $mesa)
                        <tr>
                            <td>{{ $mesa->id }}</td>
                            <td class="fw-semibold">{{ $mesa->codigo }}</td>
                            <td>{{ $mesa->capacidad }}</td>

                            {{-- Badge Estado --}}
                            <td>
                                @if ($mesa->estado === 'disponible')
                                    <span class="badge badge-success px-3 py-2">Disponible</span>
                                @elseif($mesa->estado === 'ocupada')
                                    <span class="badge bg-warning text-dark px-3 py-2">Ocupada</span>
                                @else
                                    <span class="badge bg-info text-dark px-3 py-2">Reservada</span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td>
                                {{-- Editar --}}
                                <a href="{{ route('mesas.edit', $mesa) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Editar mesa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Eliminar --}}
                                <form action="{{ route('mesas.destroy', $mesa) }}" method="POST"
                                    class="d-inline eliminar-mesa-form">
                                    @csrf
                                    @method('DELETE')

                                    {{-- Botón SweetAlert --}}
                                    <button type="button"
                                        class="btn btn-sm btn-danger btn-eliminar-mesa"
                                        data-codigo="{{ $mesa->codigo }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-muted py-4">
                                <i class="fas fa-info-circle"></i> No hay mesas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         {{-- 📄 Paginación Tonalli --}}
    @if ($mesas->hasPages())
        <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center bg-white border-top">
            
            <small class="text-muted mb-2 mb-md-0">
                Mostrando
                <span class="fw-semibold">{{ $mesas->firstItem() }}</span>
                a
                <span class="fw-semibold">{{ $mesas->lastItem() }}</span>
                de
                <span class="fw-semibold">{{ $mesas->total() }}</span>
                mesas
            </small>

            <div>
                {{ $mesas->onEachSide(1)->links() }}
            </div>

        </div>
    @endif

    </div>


</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // Filtros automáticos
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

    const safeSubmit = () => form.requestSubmit();

    searchInput.addEventListener('input', debounce(safeSubmit, 500));
    estadoSelect.addEventListener('change', safeSubmit);


    // SweetAlert eliminar
    document.querySelectorAll('.btn-eliminar-mesa').forEach(btn => {
        btn.addEventListener('click', function () {
            let form = this.closest('form');
            let codigo = this.dataset.codigo;

            Swal.fire({
                title: "¿Eliminar mesa?",
                html: "Estás por eliminar la mesa <b>" + codigo + "</b>.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",

                background: "#F9F5F1",
                color: "#3B2C24",
                confirmButtonColor: "#E7A59A",
                cancelButtonColor: "#AFC8E4",
                customClass: {
                    popup: 'rounded-4 shadow-lg',
                    confirmButton: 'px-4 py-2 fw-bold',
                    cancelButton: 'px-4 py-2 fw-bold'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    });

});
</script>
@endpush

