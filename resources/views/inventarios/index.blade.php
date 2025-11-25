@extends('adminlte::page')

@section('title', 'Inventario de Productos')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    {{-- Mensajes de éxito o error --}}
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

    <div class="controls-container text-center">
        <h3 class="card-title-custom">CONTROL DE INVENTARIO</h3>
        <a href="{{ route('inventarios.create') }}" class="btn btn-tonalli mt-3">
            <i class="bi bi-plus-circle"></i> Agregar Registro
        </a>
    </div>

    <div class="table-card mt-4">
        <div class="table-responsive-custom">
            <table class="table table-tonalli">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Punto de Reorden</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventarios as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->producto->nombre }}</td>
                        <td>{{ $item->stock_actual }}</td>
                        <td>{{ $item->punto_reorden }}</td>
                        <td>
                            @if ($item->estado == 'Agotado')
                                <span class="badge bg-danger">Agotado</span>
                            @elseif ($item->estado == 'Bajo')
                                <span class="badge bg-warning text-dark">Bajo</span>
                            @else
                                <span class="badge bg-success">Suficiente</span>
                            @endif
                        </td>
                        <td class="d-flex">
                            <a href="{{ route('inventarios.edit', $item) }}" class="btn btn-sm btn-tonalli me-2">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            <form action="{{ route('inventarios.destroy', $item) }}" method="POST"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-tonalli-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay registros en el inventario.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            {{ $inventarios->links() }}
        </div>
    </div>
</div>

@endsection
