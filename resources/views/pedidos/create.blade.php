@extends('adminlte::page')
@section('title', 'Nuevo Pedido')

@push('css')
<link rel="stylesheet" href="{{ asset('css/pedidos.css') }}">
@endpush

@section('content')
<div class="container-actividad py-4">
    <h1 class="fw-bold mb-4">Registrar nuevo pedido</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Corrige los siguientes errores:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <form method="POST" action="{{ route('pedidos.store') }}">
        @csrf

        {{-- Tipo de pedido --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Tipo de Pedido</label>
            <select id="tipo_pedido" name="tipo_pedido" class="form-select" required>
                <option value="mesa" selected>Pedido en mesa</option>
                <option value="llevar">Pedido para llevar</option>
            </select>
        </div>

        {{-- Mesa (solo visibles si es pedido normal) --}}
        <div id="seccion_mesa" class="mb-4">
            <label for="mesa_id" class="form-label fw-bold">Mesa</label>
            <select name="mesa_id" id="mesa_id" class="form-select">
                <option value="">Seleccionar mesa</option>
                @foreach ($mesas as $mesa)
                    <option value="{{ $mesa->id }}">{{ $mesa->codigo }}</option>
                @endforeach
            </select>
        </div>

        {{-- Mesero --}}
        <div class="mb-4">
            <label for="usuario_id" class="form-label fw-bold">Mesero</label>
            <select name="usuario_id" class="form-select" required>
                <option value="">Seleccionar mesero</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Estado inicial --}}
        <input type="hidden" name="estado" value="pendiente">

       
        <div class="text-end">
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Crear y Agregar Productos</button>
        </div>
    </form>
</div>

@endsection

@push('js')
<script>
    const tipoPedido = document.getElementById('tipo_pedido');
    const seccionMesa = document.getElementById('seccion_mesa');
    const mesaId = document.getElementById('mesa_id');

    tipoPedido.addEventListener('change', function() {
        if (this.value === 'llevar') {
            seccionMesa.classList.add('d-none');
            mesaId.value = ""; // limpiar mesa
        } else {
            seccionMesa.classList.remove('d-none');
        }
    });
</script>
@endpush
