@extends('adminlte::page')
@section('title', 'Lista de Empleados')

{{-- AGREGAR ESTO PARA CARGAR TU ARCHIVO CSS PERSONALIZADO --}}
@push('css')
    <link rel="stylesheet" href="{{ asset('css/empleados.css') }}">
@endpush

@section('content')

<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    {{-- Sección para mostrar mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="controls-container">
        <h3 class="card-title-custom text-center">GESTIÓN DE EMPLEADOS</h3>

        <form action="{{ route('usuarios.index') }}" method="GET" id="filtersForm" class="mt-4">
            <div class="row gx-3 align-items-center justify-content-center">
                <div class="col-md-5">
                    <input type="search" name="search" id="searchInput" class="form-control-dark" placeholder="Buscar por nombre o apellido..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="rol" id="rolSelect" class="form-select-dark">
                        <option value=""> Todos los roles </option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id_rol }}" {{ request('rol') == $rol->id_rol ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-clear w-100">Limpiar</a>
                </div>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <a href="{{ route('usuarios.create') }}" class="btn btn-add-user" title="Agregar Empleado">
                <i class="bi bi-plus-lg"></i> Agregar Empleado
            </a>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive-custom">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col">Email</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                    <tr>
                        {{-- CORRECCIÓN: Usar los nombres de columna correctos del modelo User --}}
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }} {{ $usuario->apellido_paterno }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->rol->nombre }}</td>
                        <td>
                            {{-- CORRECCIÓN: La columna de estado es 'user_estado' --}}
                            @if ($usuario->user_estado == 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        {{-- CORRECCIÓN: Implementación de los botones de acción --}}
                        <td class="action-buttons d-flex justify-content-start">
                         <!-- Botón EDITAR -->
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-editar me-2" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                        <span>Editar</span>
                         </a>

                        <!-- Botón ELIMINAR -->
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" 
                             onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');"
                             style="display:inline;">
                             @csrf
                            @method('DELETE')
                        <button type="submit" class="btn btn-eliminar" title="Eliminar">
                        <i class="bi bi-trash-fill"></i>
                        <span>Eliminar</span>
                        </button>
                        </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron usuarios con los filtros aplicados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- NUEVO: Añadir los enlaces de paginación --}}
        <div class="pagination-container mt-3">
            {{ $usuarios->appends(request()->query())->links() }}
        </div>

    </div>

</div>
@endsection

@push('scripts')
{{-- El script de auto-filtrado se mantiene igual, es correcto --}}
<script>
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const form = document.getElementById('filtersForm');
    const searchInput = document.getElementById('searchInput');
    const rolSelect = document.getElementById('rolSelect');
    
    // El debounce es para esperar a que el usuario termine de escribir
    const debouncedSubmit = debounce(() => {
        form.submit();
    }, 500);

    searchInput.addEventListener('input', debouncedSubmit);
    rolSelect.addEventListener('change', () => {
        form.submit();
    });
</script>
@endpush
