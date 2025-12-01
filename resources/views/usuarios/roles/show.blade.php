@extends('adminlte::page')

@section('title', 'Detalles del Rol')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

{{-- MENSAJES DE VALIDACIÓN (Errores del Request) --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        <h6 class="alert-heading mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            Debes corregir los siguientes errores:
        </h6>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- MENSAJE DE ÉXITO --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- MENSAJE DE ERROR PERSONALIZADO (como eliminar rol usado) --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        <i class="fas fa-times-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')

    <div class="container-actividad py-4">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
            <div>
                <h1 class="m-0 text-dark">{{ $rol->nombre }}</h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-user-shield"></i> Información detallada del rol
                </p>
            </div>

            <div class="d-flex gap-2">

                {{-- EDITAR --}}
                <a href="{{ route('roles.edit', $rol->id_rol) }}" class="btn btn-warning px-3">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>

                {{-- ELIMINAR --}}
                <form action="{{ route('roles.destroy', $rol->id_rol) }}" method="POST" class="d-inline eliminar-form">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-danger px-3 btn-eliminar" data-nombre="{{ $rol->nombre }}">
                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </button>
                </form>

                {{-- VOLVER --}}
                <a href="{{ route('roles.index') }}" class="btn btn-primary px-3">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>

            </div>
        </div>


        <div class="row g-4">

            {{-- INFORMACIÓN GENERAL --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h4 class="card-title mb-3">Información General</h4>

                        {{-- Categoria --}}
                        <p class="mb-1"><strong>Categoría:</strong></p>
                        <span class="badge badge-primary px-3 py-2">
                            {{ $rol->categoria }}
                        </span>

                        <hr>

                        {{-- Descripción --}}
                        <p class="mb-1"><strong>Descripción:</strong></p>
                        <p class="text-muted">{{ $rol->descripcion }}</p>

                        <hr>

                        {{-- Permisos --}}
                        <p class="mb-1"><strong>Permisos ({{ count($rol->permisos ?? []) }})</strong></p>

                        <div class="d-flex flex-wrap gap-2">

                            @forelse ($rol->permisos ?? [] as $permiso)
                                <span class="chip permiso-chip selected">
                                    {{ $permiso }}
                                </span>
                            @empty
                                <p class="text-muted">Este rol no tiene permisos asignados.</p>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- USUARIOS ASIGNADOS --}}
                <div class="card card-outline card-primary shadow-sm mt-4">
    <div class="card-body">

        <h4 class="card-title-custom mb-3">Usuarios Asignados</h4>

        @forelse ($rol->usuarios as $user)
            <div class="usuario-tonalli-row">

                <div class="d-flex align-items-center gap-3">

                    {{-- Avatar claro --}}
                    <div class="avatar-tonalli-light">
                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>

                    {{-- Información --}}
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark" style="font-size: 1.05rem;">
                            {{ $user->name }} {{ $user->apellido_paterno }}
                        </div>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>

                    {{-- Fecha --}}
                    <div class="text-end fecha-asignado">
                        <small>{{ $user->created_at->format('d M Y') }}</small>
                    </div>

                </div>

            </div>
        @empty
            <p class="text-muted">No hay usuarios asignados.</p>
        @endforelse

    </div>
</div>
>

            </div>


            {{-- LADO DERECHO: ESTADÍSTICAS --}}
            <div class="col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h4 class="card-title mb-3">Estadísticas</h4>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total Usuarios:</span>
                            <strong>{{ $rol->usuarios->count() }}</strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Permisos:</span>
                            <strong>{{ count($rol->permisos ?? []) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span>Categoría:</span>
                            <span class="badge badge-primary px-3 py-2">{{ $rol->categoria }}</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.btn-eliminar').forEach(btn => {

                btn.addEventListener('click', function() {

                    let form = this.closest('form');
                    let nombre = this.dataset.nombre;

                    Swal.fire({
                        title: "¿Eliminar rol?",
                        html: "Estás por eliminar <b>" + nombre + "</b>.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",

                        // ESTILO TONALLI PASTEL
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
