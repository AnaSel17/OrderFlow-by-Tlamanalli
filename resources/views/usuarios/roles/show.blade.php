@extends('adminlte::page')

@section('title', 'Detalles del Rol')

@push('css')
    {{-- Conexión al archivo de estilos específico para esta vista --}}
    <link rel="stylesheet" href="{{ asset('css/editar_roles.css') }}">
    {{-- Bootstrap Icons para el ícono del rol --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
@endpush

@section('content_header')
    <div class="d-flex align-items-center">
        <a href="{{ route('roles.index') }}" class="btn btn-link text-dark text-decoration-none mr-3">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Roles
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex align-items-center" style="gap: 15px;">
                <div class="role-icon-lg">
                    <i class="bi bi-shield"></i>
                </div>
                <div>
                    <h1 class="mb-2">Gerente</h1>
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <span class="category-badge">Gerencial</span>
                        <span class="text-muted small">2 usuarios asignados</span>
                    </div>
                </div>
            </div>

            <div class="d-flex" style="gap: 10px;">
                <a href="#" class="btn btn-outline-primary">
                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                </a>
                <form action="#" method="POST" onsubmit="return confirm('¿Eliminar este rol?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash mr-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda: Información y Usuarios -->
        <div class="col-lg-8">
            <!-- Descripción y Permisos -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información General</h5>
                    <p class="text-muted small mb-3">Detalles y descripción del rol</p>
                    <hr>
                    <h6>Descripción</h6>
                    <p class="text-muted">Supervisa todas las operaciones de la cafetería y toma decisiones estratégicas.</p>
                    
                    <hr>
                    
                    <h6>Permisos (4)</h6>
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        <span class="badge-custom">Gestión completa</span>
                        <span class="badge-custom">Reportes</span>
                        <span class="badge-custom">Administración de personal</span>
                        <span class="badge-custom">Finanzas</span>
                    </div>
                </div>
            </div>

            <!-- Usuarios asignados -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-1">Usuarios Asignados</h5>
                            <p class="text-muted small mb-0">5 miembros del equipo</p>
                        </div>
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus mr-1"></i> Asignar Usuarios
                        </button>
                    </div>

                    <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                        <div class="user-list-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center" style="gap: 15px;">
                                    <div class="user-avatar">
                                        <span>MG</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">María García</h6>
                                        <small class="text-muted">maria@cafepushkin.com</small>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    14 ene 2024
                                </small>
                            </div>
                        </div>
                        <div class="user-list-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center" style="gap: 15px;">
                                    <div class="user-avatar">
                                        <span>CR</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Carlos Rodríguez</h6>
                                        <small class="text-muted">carlos@cafepushkin.com</small>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    19 feb 2024
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Estadísticas y Actividad -->
        <div class="col-lg-4">
            <!-- Estadísticas -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas</h5>
                    <hr>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <i class="fas fa-users fa-fw text-muted" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Total Usuarios</p>
                        </div>
                        <div>
                            <h4 class="mb-0 text-dark">2</h4>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <i class="fas fa-key fa-fw text-muted" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Permisos</p>
                        </div>
                        <div>
                            <h4 class="mb-0 text-dark">4</h4>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-tag fa-fw text-muted" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Categoría</p>
                        </div>
                        <div>
                            <span class="category-badge">Gerencial</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actividad Reciente</h5>
                    <hr>
                    
                    <h6 class="mb-3 text-muted">Últimos usuarios asignados:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-circle text-success mr-2" style="font-size: 8px;"></i>
                            <span class="text-dark">María García</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-circle text-success mr-2" style="font-size: 8px;"></i>
                            <span class="text-dark">Carlos Rodríguez</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-circle text-success mr-2" style="font-size: 8px;"></i>
                            <span class="text-dark">Ana Martínez</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
