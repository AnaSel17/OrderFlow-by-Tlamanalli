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
    <label class="form-label fw-bold">Activo</label>
    <br>

    <input type="hidden" name="activo" value="0">

    <div class="form-check form-switch">
        <input class="form-check-input"
               type="checkbox"
               name="activo"
               value="1"
               id="activoSwitch"
               {{ $producto->activo ? 'checked' : '' }}>
        <label class="form-check-label" for="activoSwitch">¿Producto activo?</label>
    </div>
</div>

        <button class="btn btn-primary">Actualizar</button>
    </form>
@stop
