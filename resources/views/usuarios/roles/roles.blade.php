@extends('adminlte::page')

@section('title', 'Roles de Cafetería')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid py-4 px-5">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h1 class="m-0 text-dark">Roles de Cafetería</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-shield"></i> Gestión de roles y permisos
            </p>
        </div>

        <a href="{{ route('roles.create') }}" class="badge badge-success px-3 py-2">
            <i class="fas fa-plus"></i> Nuevo Rol
        </a>
    </div>

    {{-- BUSCADOR --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="role-search" class="form-control"
                   placeholder="Buscar roles...">
        </div>
    </div>

    {{-- TABLA PRINCIPAL (TONALLI STYLE) --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body table-responsive p-0">

            <table class="table table-hover text-center align-middle mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Usuarios Asignados</th>
                    <th>Acciones</th>
                </tr>
                </thead>

                <tbody id="roles-table">

                @forelse($roles as $rol)
                    <tr>
                        <td>{{ $rol->id_rol }}</td>

                        {{-- NOMBRE --}}
                        <td class="fw-semibold">{{ $rol->nombre }}</td>

                        {{-- CATEGORÍA --}}
                        <td>
                            <span class="badge badge-primary px-3 py-1">
                                {{ $rol->categoria ?? 'Sin categoría' }}
                            </span>
                        </td>

                        {{-- DESCRIPCION --}}
                        <td class="text-muted">
                            {{ Str::limit($rol->descripcion, 40, '...') }}
                        </td>

                        {{-- NUMERO DE USUARIOS --}}
                        <td>
                            <span class="badge badge-info px-3 py-2">
                                {{ $rol->usuarios->count() }} usuarios
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td>

                            {{-- VER --}}
                            <a href="{{ route('roles.show', $rol->id_rol) }}"
                               class="btn btn-sm btn-primary" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('roles.edit', $rol->id_rol) }}"
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- ELIMINAR --}}
                            <form action="{{ route('roles.destroy', $rol->id_rol) }}"
                                  method="POST" class="d-inline eliminar-form">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-sm btn-danger btn-eliminar"
                                        data-nombre="{{ $rol->nombre }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-muted py-4">
                            <i class="fas fa-info-circle"></i> No hay roles registrados.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // FILTRO DE BUSQUEDA
    const searchInput = document.getElementById("role-search");

    searchInput.addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        document.querySelectorAll("#roles-table tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });

    // CONFIRMACIÓN DE ELIMINAR
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function () {

            let form = this.closest('form');
            let nombre = this.dataset.nombre;

            Swal.fire({
                title: "¿Eliminar rol?",
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
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });

        });
    });

});
</script>
@endpush
