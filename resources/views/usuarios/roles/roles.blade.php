@extends('adminlte::page')

@section('title', 'Roles de Cafetería')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="m-0">Roles de Cafetería</h1>
            <p class="text-muted">Gestiona los roles y permisos del personal</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">+ Crear Nuevo Rol</a>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        margin-bottom: 20px;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .role-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
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
    .gerencial {
        background-color: #e9ecef;
    }
    .operativo {
        background-color: #e9ecef;
    }
    .btn-details {
        background-color: #f8f9fa;
        color: #495057;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        width: 100%;
        text-align: center;
        transition: all 0.3s;
    }
    .btn-details:hover {
        background-color: #e9ecef;
    }
    .stats-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

<div class="container-fluid">
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Roles</p>
                            <h2 class="mb-0 fw-bold">6</h2>
                        </div>
                        <div class="bg-dark text-white rounded-circle stats-icon">
                            <i class="bi bi-shield fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Personal Activo</p>
                            <h2 class="mb-0 fw-bold">30</h2>
                        </div>
                        <div class="rounded-circle stats-icon text-white" style="background-color: #a68a7b;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Categorías</p>
                            <h2 class="mb-0 fw-bold">3</h2>
                        </div>
                        <div class="rounded-circle stats-icon text-white" style="background-color: #a68a7b;">
                            <i class="bi bi-gear fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de Roles -->
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="role-icon" style="background-color: #f0e6ff;">
                            <i class="bi bi-shield"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm" type="button" data-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Editar</a>
                                <a class="dropdown-item text-danger" href="#">Eliminar</a>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="card-title mb-2">Gerente</h5>
                    <p class="card-text text-muted mb-3">Supervisa todas las operaciones de la cafetería y toma decisiones estratégicas.</p>
                    
                    <div class="mb-3">
                        <span class="category-badge gerencial">Gerencial</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge-custom">Gestión completa</span>
                        <span class="badge-custom">Reportes</span>
                        <span class="badge-custom">Administración</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">2 usuarios</small>
                        <a href="{{ route('roles.show', 1) }}" class="btn-details">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="role-icon" style="background-color: #fff8e6;">
                            <i class="bi bi-cup-hot"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm" type="button" data-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Editar</a>
                                <a class="dropdown-item text-danger" href="#">Eliminar</a>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="card-title mb-2">Barista</h5>
                    <p class="card-text text-muted mb-3">Prepara bebidas de café y otras bebidas especializadas según los estándares.</p>
                    
                    <div class="mb-3">
                        <span class="category-badge operativo">Operativo</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge-custom">Preparar bebidas</span>
                        <span class="badge-custom">Gestión de inventario de café</span>
                        <span class="badge-custom">Atención al cliente</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">8 usuarios</small>
                        <a href="{{ route('roles.show', 2) }}" class="btn-details">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="role-icon" style="background-color: #ffe6e6;">
                            <i class="bi bi-cake2"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm" type="button" data-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Editar</a>
                                <a class="dropdown-item text-danger" href="#">Eliminar</a>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="card-title mb-2">Chef Pastelero</h5>
                    <p class="card-text text-muted mb-3">Responsable de crear y preparar todos los productos de panadería y repostería.</p>
                    
                    <div class="mb-3">
                        <span class="category-badge operativo">Operativo</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge-custom">Preparar productos</span>
                        <span class="badge-custom">Gestión de ingredientes</span>
                        <span class="badge-custom">Control de calidad</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">3 usuarios</small>
                        <a href="#" class="btn-details">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
