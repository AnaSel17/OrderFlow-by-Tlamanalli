@extends('adminlte::page')

@section('title', 'Permisos de Roles')

@section('content_header')
    <h1 class="text-center mb-4" style="color: var(--ton-primary-dark);">Permisos de Roles</h1>
@endsection

@section('content')
<!-- Se usa Font Awesome (fas) para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container py-4">
    <div class="row gx-3 align-items-stretch justify-content-center roles-container">

        <!-- Tarjeta: Administrador -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="role-card">
                <i class="fas fa-user-shield role-icon"></i>
                <h5>Administrador</h5>
                <p>Acceso total al sistema y gestión de usuarios, productos e inventario.</p>
                
                {{-- Contenedor de botones de acción --}}
                <div class="card-actions">
                    <a href="#" class="btn-action btn-add">
                        <i class="fas fa-plus-circle me-2"></i> Agregar Permiso
                    </a>
                    <a href="#" class="btn-action btn-edit">
                        <i class="fas fa-edit me-2"></i> Editar Permiso
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Empleado -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="role-card">
                <i class="fas fa-user-tie role-icon"></i>
                <h5>Empleado</h5>
                <p>Acceso a funciones operativas del sistema como ventas y caja.</p>
                
                {{-- Contenedor de botones de acción --}}
                <div class="card-actions">
                    <a href="#" class="btn-action btn-add">
                        <i class="fas fa-plus-circle me-2"></i> Agregar Permiso
                    </a>
                    <a href="#" class="btn-action btn-edit">
                        <i class="fas fa-edit me-2"></i> Editar Permiso
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Cliente -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="role-card">
                <i class="fas fa-user role-icon"></i>
                <h5>Cliente</h5>
                <p>Acceso limitado a servicios, consultas de pedidos y perfil.</p>
                
                {{-- Contenedor de botones de acción --}}
                <div class="card-actions">
                    <a href="#" class="btn-action btn-add">
                        <i class="fas fa-plus-circle me-2"></i> Agregar Permiso
                    </a>
                    <a href="#" class="btn-action btn-edit">
                        <i class="fas fa-edit me-2"></i> Editar Permiso
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('css')
    {{-- Importar el archivo de estilos (asume que css/permisos.css tiene los estilos de roles.css) --}}
    <link rel="stylesheet" href="{{ asset('css/permisos.css') }}">
@endsection

@section('js')
<script>
    console.log("Vista de roles cargada correctamente.");
</script>
@endsection
