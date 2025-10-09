@extends('adminlte::page')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
@endpush

@section('title', 'Lista de Empleados')

@section('content')

<div class="container-fluid py-4 px-4">

    <!-- 1. El contenedor principal que crea la tarjeta blanca -->
    <div class="main-content-card">
        
        <!-- INICIO: SECCIÓN PARA MOSTRAR MENSAJES DE ÉXITO -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <!-- FIN: SECCIÓN DE MENSAJES -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="card-title-custom mb-0">Empleados</h3>
            
            <a href="{{ route('usuarios.create') }}" class="btn btn-add-user">
                <i class="fas fa-plus me-2"></i> Crear Nuevo
            </a>
        </div>

        <div class="controls-container my-4">
            <form action="{{ route('usuarios.index') }}" method="GET" id="filtersForm">
                
                <div class="row gx-3 gy-2 justify-content-center align-items-center">

                    <div class="col-12 col-md-5">
                        <input type="search" name="search" id="searchInput" class="form-control-dark" placeholder="Buscar por nombre..." value="{{ request('search') }}">
                    </div>

                    <div class="col-12 col-md-4">
                        <select name="rol" id="rolSelect" class="form-select-dark">
                            <option value="">-- Todos los roles --</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id_rol }}" {{ request('rol') == $rol->id_rol ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3 text-center">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-clear w-100">Limpiar Filtros</a>
                    </div>

                </div>
            </form>
        </div>

        <div class="table-responsive-custom">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-center">Operaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                    <tr>
                        <td><strong>#{{ $usuario->id_usuario }}</strong></td>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellido_paterno }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->rol->nombre ?? 'Sin rol' }}</td>
                        <td>
                            @if ($usuario->status == 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="action-buttons text-center">
                            <!-- Enlace de EDITAR -->
                            <a href="{{ route('usuarios.edit', $usuario) }}" title="Editar"><i class="fas fa-pencil-alt"></i></a>

                            <!-- Formulario para ELIMINAR -->
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este empleado?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar" style="background:none; border:none; padding:0; color:var(--color-texto-secundario); cursor:pointer;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No se encontraron empleados que coincidan con los filtros.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- INICIO: SECCIÓN PARA MOSTRAR LA PAGINACIÓN -->
        <div class="mt-4 d-flex justify-content-end">
            {{ $usuarios->appends(request()->query())->links() }}
        </div>
        <!-- FIN: SECCIÓN DE PAGINACIÓN -->

    </div>
</div>
@endsection

@push('scripts')
{{-- El script no cambia, funciona correctamente --}}
<script>
    function debounce(func, delay) { let timeout; return function(...args) { clearTimeout(timeout); timeout = setTimeout(() => func.apply(this, args), delay); }; }
    const form = document.getElementById('filtersForm');
    const searchInput = document.getElementById('searchInput');
    const rolSelect = document.getElementById('rolSelect');
    const debouncedSubmit = debounce(() => { form.submit(); }, 500);
    searchInput.addEventListener('input', debouncedSubmit);
    rolSelect.addEventListener('change', () => { form.submit(); });
</script>
@endpush
