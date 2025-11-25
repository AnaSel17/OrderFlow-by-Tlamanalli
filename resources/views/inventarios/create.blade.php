@extends('adminlte::page')

@section('title', 'Registrar Inventario')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-fluid px-5 py-4">
    <h3 class="card-title-custom text-center mb-4">REGISTRAR NUEVO INVENTARIO</h3>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('inventarios.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="producto_id" class="form-label">Producto</label>
                <select name="producto_id" id="producto_id" class="form-control-dark" required>
                    <option value="">-- Selecciona un producto --</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>
                @error('producto_id')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stock_actual" class="form-label">Stock Actual</label>
                <input type="number" name="stock_actual" id="stock_actual" class="form-control-dark" min="0" required>
                @error('stock_actual')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="punto_reorden" class="form-label">Punto de Reorden</label>
                <input type="number" name="punto_reorden" id="punto_reorden" class="form-control-dark" min="0" required>
                @error('punto_reorden')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('inventarios.index') }}" class="btn btn-clear">
                    <i class="bi bi-arrow-left-circle"></i> Volver
                </a>
                <button type="submit" class="btn btn-tonalli">
                    <i class="bi bi-save2"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
