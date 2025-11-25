@extends('adminlte::page')

@section('title', 'Editar Detalle de Pedido')

@section('content')
<div class="container py-4 px-4">

    <h1 class="mb-4"><i class="fas fa-edit"></i> Editar Detalle de Pedido</h1>

    <form action="{{ route('detalle_pedidos.update', $detalle->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="producto_id" class="form-label">Producto</label>
                <select name="producto_id" id="producto_id" class="form-select" required>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}"
                            {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }} — ${{ $producto->precio }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control"
                       value="{{ $detalle->cantidad }}" min="1" required>
            </div>

            <div class="col-md-3">
                <label for="notas" class="form-label">Notas</label>
                <input type="text" name="notas" id="notas" class="form-control"
                       value="{{ $detalle->notas }}" placeholder="Ej. sin azúcar...">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar cambios
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </form>

</div>
@endsection
