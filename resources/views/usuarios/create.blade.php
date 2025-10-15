@extends('adminlte::page')

{{-- Se revierte la variable a $usuario --}}
@section('title', isset($usuario) ? 'Editar Empleado' : 'Registrar Empleado')
 <link rel="stylesheet" href="{{ asset('css/crearusuario.css') }}">

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="main-content-card">

        <h3 class="card-title-custom text-center mb-4">
            {{-- Se revierte la variable a $usuario --}}
            {{ isset($usuario) ? 'EDITAR EMPLEADO' : 'REGISTRAR NUEVO EMPLEADO' }}
        </h3>

        <div class="card-body p-0">

            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            {{-- La acción del formulario usa $usuario --}}
            {{-- ... --}}
<form action="{{ isset($usuario) ? route('usuarios.update', $usuario) : route('usuarios.store') }}" method="POST">
    @csrf
    @if(isset($usuario))
        @method('PUT')
    @endif

    {{-- ========================================================= --}}
    {{-- AÑADE ESTE BLOQUE DE CÓDIGO AQUÍ --}}
    {{-- ========================================================= --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h6 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i> Por favor, corrige los siguientes errores:
            </h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ========================================================= --}}
    {{-- FIN DEL BLOQUE DE CÓDIGO --}}
    {{-- ========================================================= --}}


    <div class="row mb-3">
        {{-- ... resto de tu formulario ... --}}

                    <div class="col-md-4">
                        <label for="name" class="form-label">Nombre</label>
                        {{-- Se revierte la variable a $usuario --}}
                        <input type="text" class="form-control-dark" id="name" name="name" value="{{ old('name', $usuario->name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control-dark" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno', $usuario->apellido_paterno ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control-dark" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno', $usuario->apellido_materno ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control-dark" id="email" name="email" value="{{ old('email', $usuario->email ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono (Opcional)</label>
                        <input type="tel" class="form-control-dark" id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control-dark" id="password" name="password" placeholder="{{ isset($usuario) ? 'Dejar en blanco para no cambiar' : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control-dark" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                {{-- CÓDIGO CORRECTO --}}
<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        
        <label for="id_rol" class="form-label">Rol del Usuario</label>
        <select class="select-rol-estilizado" id="id_rol" name="id_rol" required>
            <option value="" disabled {{ old('id_rol', $usuario->id_rol ?? '') ? '' : 'selected' }}> Selecciona un rol</option>
            @foreach ($roles as $rol)
                {{-- La corrección está aquí: se usa id_rol en lugar de id_rol --}}
                <option value="{{ $rol->id_rol }}" {{ old('id_rol', $usuario->id_rol ?? '') == $rol->id_rol ? 'selected' : '' }}>
                    {{ $rol->nombre }}
                </option>
            @endforeach
        </select>
    </div>
</div>

                <div class="mt-4 d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-submit-custom">
                        <i class="fas fa-save me-2"></i> {{ isset($usuario) ? 'Actualizar Empleado' : 'Guardar Usuario' }}
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-clear">
                       <i class="fas fa-arrow-left me-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection