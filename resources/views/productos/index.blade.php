@extends('adminlte::page')

@section('title', 'Productos')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="controls-container">
        <h3 class="card-title-custom text-center">GESTIÓN DE PRODUCTOS</h3>

        <div class="text-center mt-4">
            <a href="{{ route('productos.create') }}" class="btn btn-tonalli">
                <i class="bi bi-plus-lg"></i> Agregar Producto
            </a>
        </div>
    </div>

    <div class="table-card">
        <table class="table-tonalli">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>SKU</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->sku }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $producto->activo ? 'bg-success' : 'bg-danger' }}">
                            {{ $producto->activo ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-tonalli me-2">Editar</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-tonalli-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay productos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@stop
