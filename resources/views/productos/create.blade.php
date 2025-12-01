@extends('adminlte::page')

@section('title', 'Nuevo Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
    <form action="{{ route('productos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-control" required>
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Activo</label> <br>

            <!-- Este hidden SIEMPRE manda 0 cuando el checkbox está apagado -->
            <input type="hidden" name="activo" value="0">

            <!-- Este es el checkbox real -->
            <input type="checkbox" name="activo" value="1" checked>
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
