@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1>Editar Producto</h1>
@stop

@section('content')
    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ $producto->nombre }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" value="{{ $producto->sku }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio" step="0.01" value="{{ $producto->precio }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-control" required>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected($categoria->id == $producto->categoria_id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Activo</label>
            <input type="checkbox" name="activo" value="1" {{ $producto->activo ? 'checked' : '' }}>
        </div>
        <button class="btn btn-primary">Actualizar</button>
    </form>
@stop
