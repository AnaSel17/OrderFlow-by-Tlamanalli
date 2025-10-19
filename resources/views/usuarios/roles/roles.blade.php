@extends('adminlte::page')

@section('title', 'Roles de Cafetería')

{{-- DEJAMOS ESTA SECCIÓN VACÍA para que no haya un contenedor extra --}}
@section('content_header')
@stop

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="{{ asset('css/roles.css') }}">

<div class="container-fluid">
    
    {{-- === Cabecera (MOVIDA AQUÍ desde content_header) === --}}
    <div class="header-content p-0 pb-4 mb-4" style="border-bottom: 1px solid var(--ton-border);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-semibold mb-1" style="color: var(--ton-black);">Roles de Cafetería</h1>
                <p class="text-muted mb-0" style="font-size: 1.1rem; color: #594a40 !important;">Gestiona los roles y permisos del personal</p>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-dark rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>Crear Nuevo Rol
            </a>
        </div>
    </div>
    {{-- ================================================= --}}


    <div class="row align-items-center mb-4 g-2">
        <div class="col-md-8">
            <div class="search-box">
                <input id="role-search" type="text" placeholder="🔍 Buscar roles...">
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-block category-filter">
                <div class="dropdown d-inline-block">
                    <button class="btn dropdown-toggle" type="button" id="categoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Todas las categorías
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoriesDropdown" style="min-width: 220px;">
                        <li><a class="dropdown-item" href="#">Todas las categorías</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Gerencial</a></li>
                        <li><a class="dropdown-item" href="#">Operativo</a></li>
                        <li><a class="dropdown-item" href="#">Administrativo</a></li>
                        </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Total Roles --}}
        <div class="col-md-4">
            <div class="stats-card">
                {{-- Ícono/Símbolo de 'Roles' (Círculo marrón en la imagen) --}}
                <div class="stats-icon text-dark" style="background-color: #e9e3e3;">
                    <i class="bi bi-shield"></i>
                </div>
                <h5 class="fw-semibold mb-0">6</h5>
                <small class="text-muted">Total Roles</small>
            </div>
        </div>
        {{-- Personal Activo --}}
        <div class="col-md-4">
            <div class="stats-card">
                {{-- Ícono/Símbolo de 'Personal Activo' (Círculo beige en la imagen) --}}
                <div class="stats-icon text-dark" style="background-color: #f7e4d2;">
                    <i class="bi bi-people"></i>
                </div>
                <h5 class="fw-semibold mb-0">30</h5>
                <small class="text-muted">Personal Activo</small>
            </div>
        </div>
        {{-- Categorías --}}
        <div class="col-md-4">
            <div class="stats-card">
                {{-- Ícono/Símbolo de 'Categorías' (Círculo gris en la imagen) --}}
                <div class="stats-icon text-dark" style="background-color: #e6e6e6;">
                    <i class="bi bi-gear"></i>
                </div>
                <h5 class="fw-semibold mb-0">3</h5>
                <small class="text-muted">Categorías</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="role-icon"><i class="bi bi-shield"></i></div>
                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
                <h5 class="fw-semibold">Gerente</h5>
                <p class="text-muted-small">Supervisa todas las operaciones de la cafetería y toma decisiones estratégicas.</p>
                <p class="mb-1"><strong>Categoría:</strong> <span class="category-badge">Gerencial</span></p>
                <p class="mb-2"><strong>Personal asignado:</strong> 2 usuarios</p>
                <p class="mb-1"><strong>Permisos principales:</strong></p>
                <div class="mb-3">
                    <span class="badge-custom">Gestión completa</span>
                    <span class="badge-custom">Reportes</span>
                    <span class="badge-custom">Administración de personal</span>
                    <span class="badge-custom">+1</span>
                </div>
                <a href="{{ route('roles.show', 1) }}" class="btn-details">Ver detalles</a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="role-icon"><i class="bi bi-cup-hot"></i></div>
                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
                <h5 class="fw-semibold">Barista</h5>
                <p class="text-muted-small">Prepara bebidas de café y otras bebidas especializadas según los estándares.</p>
                <p class="mb-1"><strong>Categoría:</strong> <span class="category-badge">Operativo</span></p>
                <p class="mb-2"><strong>Personal asignado:</strong> 8 usuarios</p>
                <p class="mb-1"><strong>Permisos principales:</strong></p>
                <div class="mb-3">
                    <span class="badge-custom">Preparar bebidas</span>
                    <span class="badge-custom">Gestión de inventario de café</span>
                    <span class="badge-custom">Atención al cliente</span>
                </div>
                <a href="{{ route('roles.show', 2) }}" class="btn-details">Ver detalles</a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="role-icon"><i class="bi bi-cake2"></i></div>
                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
                <h5 class="fw-semibold">Chef Pastelero</h5>
                <p class="text-muted-small">Responsable de crear y preparar todos los productos de panadería y repostería.</p>
                <p class="mb-1"><strong>Categoría:</strong> <span class="category-badge">Operativo</span></p>
                <p class="mb-2"><strong>Personal asignado:</strong> 3 usuarios</p>
                <p class="mb-1"><strong>Permisos principales:</strong></p>
                <div class="mb-3">
                    <span class="badge-custom">Preparar productos</span>
                    <span class="badge-custom">Gestión de ingredientes</span>
                    <span class="badge-custom">Control de calidad</span>
                </div>
                <a href="#" class="btn-details">Ver detalles</a>
            </div>
        </div>
    </div>
</div>
@endsection