@extends('adminlte::page')

@section('title', 'Editar Inventario')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-fluid px-5 py-4">
    <h3 class="card-title-custom text-center mb-4">EDITAR INVENTARIO</h3>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('inventarios.update', $inventario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="producto_id" class="form-label">Producto</label>
                <input type="text" class="form-control-dark" value="{{ $inventario->producto->nombre }}" readonly>
            </div>

            <div class="mb-3">
                <label for="stock_actual" class="form-label">Stock Actual</label>
                <input type="number" name="stock_actual" id="stock_actual" class="form-control-dark"
                       value="{{ old('stock_actual', $inventario->stock_actual) }}" min="0" required>
                @error('stock_actual')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="punto_reorden" class="form-label">Punto de Reorden</label>
                <input type="number" name="punto_reorden" id="punto_reorden" class="form-control-dark"
                       value="{{ old('punto_reorden', $inventario->punto_reorden) }}" min="0" required>
                @error('punto_reorden')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('inventarios.index') }}" class="btn btn-clear">
                    <i class="bi bi-arrow-left-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-tonalli">
                    <i class="bi bi-check-circle"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
