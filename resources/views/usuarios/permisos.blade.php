@extends('adminlte::page')

@section('title', 'Permisos de Roles')

@section('content_header')
    <h1 class="text-center mb-4">Permisos de Roles</h1>
@endsection

@section('content')
<div class="container py-4">
    <div class="row gx-3 align-items-center justify-content-center roles-container">

        <!-- Puedes reemplazar estos divs por tus datos dinámicos -->
        <div class="col-md-4 col-sm-6">
            <div class="role-card">
                <i class="fas fa-user-shield role-icon"></i>
                <h5>Administrador</h5>
                <p>Acceso total al sistema y gestión de usuarios.</p>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="role-card">
                <i class="fas fa-user-tie role-icon"></i>
                <h5>Empleado</h5>
                <p>Acceso a funciones operativas del sistema.</p>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="role-card">
                <i class="fas fa-user role-icon"></i>
                <h5>Cliente</h5>
                <p>Acceso limitado a servicios y consultas.</p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('css')
    {{-- Importar el archivo externo de estilos --}}
    <link rel="stylesheet" href="{{ asset('css/permisos.css') }}">
@endsection

@section('js')
<script>
    console.log("Vista de roles cargada correctamente.");
</script>
@endsection
