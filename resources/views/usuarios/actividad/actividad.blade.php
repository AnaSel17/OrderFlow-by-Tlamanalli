@extends('adminlte::page')

@section('title', 'Actividad')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/actividad.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 roles-container">
    <h2 class="section-title text-center mb-4">Historial de Actividad</h2>

    {{-- Filtro de búsqueda y fechas --}}
    <div class="search-form mb-4">
        <input type="text" class="search-input" placeholder="Buscar usuario, acción o módulo...">
        <input type="date" class="search-input" style="max-width: 200px;">
        <button class="btn-clear">
            <i class="bi bi-filter-circle"></i> Filtrar
        </button>
    </div>

    {{-- Tabla de historial --}}
    <div class="table-wrapper">
        <table class="roles-table table table-borderless align-middle text-center">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                {{-- Ejemplos de registros --}}
                <tr>
                    <td>Monserrat García</td>
                    <td><span class="badge bg-success">Ingreso</span></td>
                    <td>Sistema</td>
                    <td>2025-10-12 15:23</td>
                </tr>
                <tr>
                    <td>Juan Pérez</td>
                    <td><span class="badge bg-primary">Registro</span></td>
                    <td>Usuarios</td>
                    <td>2025-10-12 14:58</td>
                </tr>
                <tr>
                    <td>Laura Mendoza</td>
                    <td><span class="badge bg-warning text-dark">Actualización</span></td>
                    <td>Roles</td>
                    <td>2025-10-12 13:41</td>
                </tr>
                <tr>
                    <td>Admin</td>
                    <td><span class="badge bg-danger">Eliminación</span></td>
                    <td>Usuarios</td>
                    <td>2025-10-12 12:17</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="pagination-wrapper text-center">
        <button class="btn-pagination">Anterior</button>
        <button class="btn-pagination">Siguiente</button>
    </div>
</div>
@endsection
