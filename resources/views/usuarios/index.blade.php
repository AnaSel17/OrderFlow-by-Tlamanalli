@extends('adminlte::page')

@section('title', 'Empleados')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">

    {{-- 🧭 Encabezado principal --}}
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <div>
            <h1 class="m-0 text-dark">Empleados</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-clock"></i> Hora del sistema:
                <strong>{{ now('America/Mexico_City')->format('H:i:s') }}</strong>
            </p>
        </div>

        {{-- Botón agregar empleado (ESTILO ZONAS) --}}
        <a href="{{ route('usuarios.create') }}" class="badge badge-success px-3 py-2">
            <i class="fas fa-plus"></i> Agregar Empleado
        </a>
    </div>

    {{-- 🟢 Mensajes --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- FILTROS (mantenemos tu lógica, estilo Zonas) --}}
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body">

            <form action="{{ route('usuarios.index') }}" method="GET" id="filtersForm">
                <div class="row gx-3 align-items-center">

                    {{-- Buscador --}}
                    <div class="col-md-5">
                        <input type="search"
                               name="search"
                               id="searchInput"
                               class="form-control"
                               placeholder="Buscar por nombre o apellido..."
                               value="{{ request('search') }}">
                    </div>

                    {{-- Rol --}}
                    <div class="col-md-4">
                        <select name="rol" id="rolSelect" class="form-control">
                            <option value="">Todos los roles</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id_rol }}"
                                    {{ request('rol') == $rol->id_rol ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Limpiar --}}
                    <div class="col-md-3">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-warning w-100">
                            Limpiar
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>


    {{-- 📋 Tabla principal (COPIADA DEL ESTILO DE ZONAS) --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body table-responsive p-0">

            <table class="table table-hover text-center align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>

                            {{-- Nombre --}}
                            <td class="fw-semibold">
                                {{ $usuario->name }} {{ $usuario->apellido_paterno }}
                            </td>

                            {{-- Email --}}
                            <td>{{ $usuario->email }}</td>

                            {{-- Rol --}}
                            <td>{{ $usuario->rol->nombre }}</td>

                            {{-- Estado (Badge ZONAS) --}}
                            <td>
                                <span class="badge px-3 py-2 {{ $usuario->user_estado == 'activo' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $usuario->user_estado == 'activo' ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            {{-- Acciones mismas que ZONAS --}}
                            <td>

                                {{-- VER (si quieres, lo dejo opcional) --}}
                                <a href="{{ route('usuarios.show', $usuario) }}"
                                   class="btn btn-sm btn-primary"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- EDITAR --}}
                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar empleado">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- ELIMINAR --}}
                                <form action="{{ route('usuarios.destroy', $usuario) }}"
                                      method="POST"
                                      class="d-inline eliminar-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-sm btn-danger btn-eliminar"
                                            data-nombre="{{ $usuario->name }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                <i class="fas fa-info-circle"></i> No hay empleados registrados.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>


    {{-- 📄 Paginación --}}
    <div class="mt-3">
        {{ $usuarios->links() }}
    </div>

</div>
@endsection


@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Activar tooltips por si los usas
    document.addEventListener("DOMContentLoaded", function() {
        const tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipList.map(el => new bootstrap.Tooltip(el));
    });

    // Confirmación pastel para eliminar (idéntica a ZONAS)
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.btn-eliminar').forEach(btn => {

            btn.addEventListener('click', function() {

                let form = this.closest('form');
                let nombre = this.dataset.nombre;

                Swal.fire({
                    title: "¿Eliminar empleado?",
                    html: "Estás por eliminar <b>" + nombre + "</b>.",
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

            });
        });
    });
</script>
@endpush
