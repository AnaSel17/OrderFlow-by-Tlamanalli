@extends('adminlte::page')

@section('title', 'Editar Rol')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-actividad py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <div>
            <h1 class="m-0 text-dark">Editar Rol</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-shield"></i> Modificar información del rol
            </p>
        </div>

        <a href="{{ route('roles.index') }}" class="badge badge-warning px-3 py-2">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    {{-- CARD --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body">

            <form action="{{ route('roles.update', $rol->id_rol) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- NOMBRE --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del Rol *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $rol->nombre) }}" required>
                </div>

                {{-- DESCRIPCIÓN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción *</label>
                    <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $rol->descripcion) }}</textarea>
                </div>

                {{-- CATEGORÍA --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Categoría *</label>
                    <select name="categoria" class="form-control" required>
                        <option value="">Selecciona una categoría</option>
                        <option {{ $rol->categoria == 'General' ? 'selected' : '' }}>General</option>
                        <option {{ $rol->categoria == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                        <option {{ $rol->categoria == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                    </select>
                </div>

                {{-- PERMISOS --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Permisos *</label>

                    <div id="chips-container" class="d-flex flex-wrap gap-2">

                        @php
                            $permisosGuardados = $rol->permisos ?? [];
                        @endphp

                        @foreach ([
                            'Gestión completa', 'Reportes', 'Administración de personal', 'Finanzas',
                            'Inventario', 'Atención al cliente', 'Tomar pedidos', 'Procesar pagos',
                            'Gestión de mesas', 'Supervisión', 'Gestión de turnos', 'Configuración del sistema'
                        ] as $permiso)

                            <span class="chip permiso-chip {{ in_array($permiso, $permisosGuardados) ? 'selected' : '' }}"
                                  data-permiso="{{ $permiso }}">
                                {{ $permiso }}
                            </span>

                        @endforeach
                    </div>

                    {{-- inputs ocultos --}}
                    <div id="permisos-hidden">
                        @if ($permisosGuardados)
                            @foreach ($permisosGuardados as $p)
                                <input type="hidden" name="permisos[]" value="{{ $p }}">
                            @endforeach
                        @endif
                    </div>

                    {{-- Permiso personalizado --}}
                    <div class="input-group mt-3">
                        <input type="text" id="permiso_custom" class="form-control"
                               placeholder="Agregar permiso personalizado...">
                        <button type="button" id="btn-add-permiso"
                                class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="mt-4 d-flex justify-content-center gap-3">

                    <a href="{{ route('roles.index') }}"
                       class="btn btn-warning px-4 py-2">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>

                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="fas fa-save me-2"></i> Actualizar Rol
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection


@push('js')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const permisosHidden = document.getElementById("permisos-hidden");

    function togglePermiso(chip) {
        let permiso = chip.dataset.permiso;

        if (chip.classList.contains("selected")) {
            chip.classList.remove("selected");

            let input = permisosHidden.querySelector(`input[value="${permiso}"]`);
            if (input) input.remove();

        } else {
            chip.classList.add("selected");

            let hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "permisos[]";
            hidden.value = permiso;
            permisosHidden.appendChild(hidden);
        }
    }

    // ACTUALIZAR PERMISOS EXISTENTES
    document.querySelectorAll(".permiso-chip").forEach(chip => {
        chip.addEventListener("click", () => togglePermiso(chip));
    });

    // AGREGAR PERMISO NUEVO
    document.getElementById("btn-add-permiso").addEventListener("click", function () {
        const campo = document.getElementById("permiso_custom");
        const valor = campo.value.trim();
        if (valor === "") return;

        // Crear chip
        const chip = document.createElement("span");
        chip.className = "chip permiso-chip selected";
        chip.dataset.permiso = valor;
        chip.textContent = valor;

        document.getElementById("chips-container").appendChild(chip);

        // Crear input hidden
        let hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "permisos[]";
        hidden.value = valor;
        permisosHidden.appendChild(hidden);

        campo.value = "";

        chip.addEventListener("click", () => togglePermiso(chip));
    });

});
</script>
@endpush
