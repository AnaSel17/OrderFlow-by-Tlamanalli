@extends('adminlte::page')

@section('title', isset($usuario) ? 'Editar Empleado' : 'Registrar Empleado')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/crearusuario.css') }}">
@endpush

@section('content')
    <div class="container-actividad py-4">

        {{-- 🧭 Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
            <div>
                <h1 class="m-0 text-dark">
                    {{ isset($usuario) ? 'Editar Empleado' : 'Registrar Empleado' }}
                </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user"></i> Gestión de personal
            </p>
            </div>

            <a href="{{ route('usuarios.index') }}" class="badge badge-warning px-3 py-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- 🔳 Card principal similar a Zonas --}}
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-body">

                {{-- Alertas de error pastel --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Corrige los siguientes errores:
                        </h6>
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Alertas de éxito --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <form action="{{ isset($usuario) ? route('usuarios.update', $usuario) : route('usuarios.store') }}"
                      method="POST">
                    @csrf
                    @if (isset($usuario))
                        @method('PUT')
                    @endif

                    {{-- NOMBRE / APELLIDOS --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control"
                                   name="name"
                                   value="{{ old('name', $usuario->name ?? '') }}"
                                   required minlength="2"
                                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control"
                                   name="apellido_paterno"
                                   value="{{ old('apellido_paterno', $usuario->apellido_paterno ?? '') }}"
                                   required minlength="2"
                                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control"
                                   name="apellido_materno"
                                   value="{{ old('apellido_materno', $usuario->apellido_materno ?? '') }}"
                                   minlength="2"
                                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
                        </div>
                    </div>

                    {{-- EMAIL / TELÉFONO --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control"
                                   name="email"
                                   value="{{ old('email', $usuario->email ?? '') }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono (opcional)</label>
                            <input type="tel" class="form-control"
                                   name="telefono"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   value="{{ old('telefono', $usuario->telefono ?? '') }}">
                        </div>
                    </div>

                    {{-- CONTRASEÑA + OJO --}}
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control"
                                       id="password"
                                       name="password"
                                       minlength="8"
                                       placeholder="{{ isset($usuario) ? 'Dejar en blanco para no cambiar' : '' }}">

                                <button type="button"
                                        class="btn btn-outline-secondary toggle-password"
                                        data-target="#password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation">

                                <button type="button"
                                        class="btn btn-outline-secondary toggle-password"
                                        data-target="#password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- SELECT DE ROL --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mx-auto">
                            <label class="form-label">Rol del Usuario</label>
                            <select class="form-control" name="id_rol" required>
                                <option value="" disabled
                                        {{ old('id_rol', $usuario->id_rol ?? '') ? '' : 'selected' }}>
                                    Selecciona un rol
                                </option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id_rol }}"
                                        {{ old('id_rol', $usuario->id_rol ?? '') == $rol->id_rol ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- BOTONES PASTEL --}}
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($usuario) ? 'Actualizar Empleado' : 'Guardar Usuario' }}
                        </button>

                        <a href="{{ route('usuarios.index') }}" class="btn btn-warning px-4 py-2">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = document.querySelector(btn.dataset.target);
            const icon = btn.querySelector('i');

            if (!target) return;

            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
});
</script>
@endpush
