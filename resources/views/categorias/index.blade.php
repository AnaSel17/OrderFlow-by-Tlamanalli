@extends('adminlte::page')

@section('title', 'Categorías')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif



    {{-- Encabezado --}}
    <div class="controls-container">
        <h3 class="card-title-custom text-center">GESTIÓN DE CATEGORÍAS</h3>

        <div class="text-center mt-3">
            <a href="{{ route('categorias.create') }}" class="btn btn-primary px-4 py-2">
                <i class="fas fa-plus"></i> Nueva categoría
            </a>
        </div>
    </div>



    {{-- Tabla --}}
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td class="fw-semibold">{{ $categoria->nombre }}</td>
                        <td>{{ $categoria->descripcion ?? '-' }}</td>

                        <td class="text-center">

                            {{-- Editar --}}
                            <a href="{{ route('categorias.edit', $categoria) }}"
                                class="btn btn-info btn-sm px-3 me-2">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Eliminar --}}
                            @if ($categoria->productos()->count() === 0)
                                <form action="{{ route('categorias.destroy', $categoria) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm px-3"
                                        onclick="return confirm('¿Eliminar categoría?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm px-3" disabled>
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            No hay categorías registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@stop
