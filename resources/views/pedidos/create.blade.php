@extends('adminlte::page')
@section('title', 'Nuevo Pedido')

@push('css')
<link rel="stylesheet" href="{{ asset('css/pedidos.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">
    <h1 class="fw-bold mb-4">Registrar nuevo pedido</h1>

    <form method="POST" action="{{ route('pedidos.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="mesa_id" class="form-label">Mesa</label>
                <select name="mesa_id" class="form-select" required>
                    <option value="">Seleccionar mesa</option>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->id }}">{{ $mesa->codigo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="usuario_id" class="form-label">Mesero</label>
                <select name="usuario_id" class="form-select" required>
                    <option value="">Seleccionar mesero</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_preparacion">En preparación</option>
                    <option value="listo">Listo</option>
                    <option value="entregado">Entregado</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total inicial</label>
            <input type="number" step="0.01" name="total" class="form-control" placeholder="0.00">
        </div>

        <div class="text-end">
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar Pedido</button>
        </div>
    </form>
</div>
@endsection
