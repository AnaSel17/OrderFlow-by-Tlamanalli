@extends('adminlte::page')

@section('title', 'Productos')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid d-flex flex-column gap-4 px-5 py-4">

    {{-- Alertas Tonalli --}}
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
        <h3 class="card-title-custom text-center">GESTIÓN DE PRODUCTOS</h3>

        <div class="text-center mt-3">
            <a href="{{ route('productos.create') }}" class="btn btn-primary px-4 py-2">
                <i class="fas fa-plus"></i> Nuevo producto
            </a>
        </div>
    </div>



    {{-- Tabla --}}
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th class="text-center">Activo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($productos as $producto)
                    <tr>
                        <td class="fw-semibold">{{ $producto->nombre }}</td>
                        <td>{{ $producto->sku }}</td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>{{ $producto->categoria->nombre ?? '-' }}</td>

                        {{-- Badge de activo --}}
                        <td class="text-center">
                            @if ($producto->activo)
                                <span class="badge badge-success px-3 py-2">Activo</span>
                            @else
                                <span class="badge badge-danger px-3 py-2">Inactivo</span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="text-center">

                            {{-- Editar --}}
                            <a href="{{ route('productos.edit', $producto) }}"
                               class="btn btn-info btn-sm px-3 me-2">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('productos.destroy', $producto) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm px-3"
                                    onclick="return confirm('¿Eliminar producto?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No hay productos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@stop
