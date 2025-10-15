@extends('adminlte::page')

@section('title', 'Detalles del Rol')

@section('content_header')
    <div class="d-flex align-items-center">
        <a href="{{ route('roles.index') }}" class="btn btn-link text-dark text-decoration-none mr-3">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Roles
        </a>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .role-icon-lg {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 32px;
    }
    .badge-custom {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: normal;
        padding: 5px 10px;
        border-radius: 20px;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }
    .category-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }
    .user-list-item {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .user-list-item:last-child {
        border-bottom: none;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex gap-3">
                <div class="role-icon-lg" style="background-color: #f0e6ff;">
                    <i class="bi bi-shield"></i>
                </div>
                <div>
                    <h1 class="mb-2">Gerente</h1>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary">Gerencial</span>
                        <span class="text-muted">2 usuarios asignados</span>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
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
        <!-- Información principal -->
        <div class="col-lg-8">
            <!-- Descripción -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información General</h5>
                    <p class="text-muted small mb-3">Detalles y descripción del rol</p>
                    <hr>
                    <h6>Descripción</h6>
                    <p class="text-muted">Supervisa todas las operaciones de la cafetería y toma decisiones estratégicas.</p>
                    
                    <hr>
                    
                    <h6>Permisos (4)</h6>
                    <div class="d-flex flex-wrap gap-2">
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
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar bg-dark text-white">
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
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar bg-dark text-white">
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

        <!-- Estadísticas -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas</h5>
                    <hr>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <i class="fas fa-users fa-fw text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Total Usuarios</p>
                        </div>
                        <div>
                            <h4 class="mb-0">2</h4>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <i class="fas fa-key fa-fw text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Permisos</p>
                        </div>
                        <div>
                            <h4 class="mb-0">4</h4>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-tag fa-fw text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Categoría</p>
                        </div>
                        <div>
                            <span class="badge bg-secondary">Gerencial</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actividad Reciente</h5>
                    <hr>
                    
                    <h6 class="mb-3">Últimos usuarios asignados:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-circle text-success mr-2" style="font-size: 8px;"></i>
                            María García
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle text-success mr-2" style="font-size: 8px;"></i>
                            Carlos Rodríguez
                        </li>
                        <li>
                            <i class="fas fa-circle text-success mr-2" style="font-size: 8px;"></i>
                            Ana Martínez
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection