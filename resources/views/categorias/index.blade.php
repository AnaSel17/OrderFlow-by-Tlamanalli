@extends('adminlte::page')

@section('title', 'Categorías')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="controls-container">
        <h3 class="card-title-custom text-center">GESTIÓN DE CATEGORÍAS</h3>

        <div class="text-center mt-4">
            <a href="{{ route('categorias.create') }}" class="btn btn-tonalli">
                <i class="bi bi-plus-lg"></i> Agregar Categoría
            </a>
        </div>
    </div>

    <div class="table-card">
        <table class="table-tonalli">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion ?? '-' }}</td>
                    <td>
                        <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-tonalli me-2">Editar</a>
                        @if ($categoria->productos()->count() === 0)
                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-tonalli-danger" onclick="return confirm('¿Eliminar categoría?')">Eliminar</button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Eliminar</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay categorías registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@stop
