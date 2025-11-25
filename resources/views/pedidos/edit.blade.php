@extends('adminlte::page')

@section('title', 'Levantar Pedido')

@section('content')
<div class="container-fluid py-4 px-4">

    <h1 class="mb-4">
        <i class="fas fa-utensils"></i> Pedido #{{ $pedido->id }}
    </h1>

    {{-- 🔹 Información del pedido --}}
    <div class="alert alert-info">
        <strong>Mesas:</strong> {{ $pedido->mesas_texto }} <br>
        <strong>Mesero:</strong> {{ $pedido->usuario->name }} <br>
        <strong>Comensales:</strong> {{ $comensales->count() }} <br>
        <strong>Estado:</strong> {!! $pedido->estado_texto !!}

        {{--  Botón cobrar cuando todo fue entregado --}}
        @if ($pedido->estado === 'listo_para_cobrar')
            <a href="{{ route('pedidos.cobrar', $pedido->id) }}" class="btn btn-success mt-3">
                <i class="fas fa-cash-register"></i> Cobrar pedido
            </a>
        @endif


       {{--  Botón para enviar pedido a cocina --}}
        @if ($pedido->detalles->where('estado', 'pendiente')->count() > 0)
            <form action="{{ route('comandas.store') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-concierge-bell"></i> Enviar {{ $pedido->estado === 'pendiente' ? 'a cocina' : 'nueva comanda' }}
                </button>
            </form>
        @endif
    </div>

    {{-- 🧾 Formulario para agregar un nuevo producto --}}
@if (in_array($pedido->estado, ['pendiente', 'enviado_cocina']))
<form action="{{ route('detalle_pedidos.store') }}" method="POST">
    @csrf
    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

    <div class="row mb-3">

    <div class="col-md-4">
            <label for="producto_id" class="form-label">Producto</label>
            <select name="producto_id" id="producto_id" class="form-select" required>
                <option value="">Selecciona un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }} — ${{ $producto->precio }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form w-auto" min="1" value="1">
        </div>
        <div class="col-md-4">
            <label for="comensal_id" class="form-label">Comensal</label>
            <select name="comensal_id" id="comensal_id" class="form-select">
                <option value="">General</option>
                @foreach($comensales as $c)
                    <option value="{{ $c->id }}">Persona {{ $c->numero }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="notas" class="form-label">Notas</label>
        <input type="text" name="notas" id="notas" class="form-control" placeholder="Ej. sin azúcar, poco picante...">
    </div>

    <button type="submit" class="btn btn-success">
        <i class="fas fa-plus-circle"></i> Agregar al pedido
    </button>
</form>
@endif

<hr>

<h4 class="mt-4">🧾 Detalles del pedido</h4>

<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Precio</th>
            <th>Notas</th>
            <th>Comensal</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($pedido->detalles as $detalle)
        <tr>

        {{-- PRODUCTO --}}
        <td style="width: 25%">
            <div class="d-flex align-items-center">

                <form action="{{ route('detalle_pedidos.update', $detalle->id) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PATCH')

                    <select name="producto_id"
                            class="form-select form-select-sm"
                            {{ !in_array($detalle->estado, ['pendiente','enviado_cocina']) ? 'disabled' : '' }}>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ $producto->id == $detalle->producto_id ? 'selected' : '' }}>
                                {{ $producto->nombre }} — ${{ $producto->precio }}
                            </option>
                        @endforeach
                    </select>

                    @if (in_array($detalle->estado, ['pendiente','enviado_cocina']))
                        <button type="submit" class="btn btn-success btn-sm ms-1">
                            <i class="fas fa-save"></i>
                        </button>
                    @endif
                </form>

            </div>
        </td>


        {{-- CANTIDAD --}}
        <td style="width: 8%">
            <div class="d-flex align-items-center">

                <form action="{{ route('detalle_pedidos.update', $detalle->id) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PATCH')

                    <input type="number"
                        name="cantidad"
                        value="{{ $detalle->cantidad }}"
                        class="form-control form-control-sm text-center"
                        min="1"
                        {{ !in_array($detalle->estado, ['pendiente','enviado_cocina']) ? 'readonly' : '' }}>

                    @if (in_array($detalle->estado, ['pendiente','enviado_cocina']))
                        <button type="submit" class="btn btn-success btn-sm ms-1">
                            <i class="fas fa-save"></i>
                        </button>
                    @endif

                </form>
            </div>
        </td>


        {{-- PRECIO --}}
        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>


        {{-- NOTAS --}}
        <td style="width: 20%">
            <div class="d-flex align-items-center">

                <form action="{{ route('detalle_pedidos.update', $detalle->id) }}" method="POST" class="d-flex w-100">
                    @csrf
                    @method('PATCH')

                    <input type="text"
                        name="notas"
                        value="{{ $detalle->notas }}"
                        class="form-control form-control-sm"
                        {{ !in_array($detalle->estado, ['pendiente','enviado_cocina']) ? 'readonly' : '' }}>

                    @if (in_array($detalle->estado, ['pendiente','enviado_cocina']))
                        <button type="submit" class="btn btn-success btn-sm ms-1">
                            <i class="fas fa-save"></i>
                        </button>
                    @endif
                </form>

            </div>
        </td>


        {{-- COMENSAL --}}
        <td style="width: 10%">
            {{ $detalle->comensal ? 'Persona '.$detalle->comensal->numero : 'General' }}
        </td>


        {{-- ESTADO --}}
        <td style="width: 12%">
            @switch($detalle->estado)
                @case('pendiente')       <span class="badge bg-secondary">🕓 Pendiente</span> @break
                @case('enviado_cocina')  <span class="badge bg-info text-dark">📤 Enviado</span> @break
                @case('en_preparacion')  <span class="badge bg-warning text-dark">👨‍🍳 En preparación</span> @break
                @case('listo')           <span class="badge bg-success">✅ Listo</span> @break
                @case('entregado')       <span class="badge bg-primary">💰 Entregado</span> @break
                @case('cancelado')       <span class="badge bg-dark">❌ Cancelado</span> @break
            @endswitch
        </td>


        {{-- ACCIONES --}}
        <td class="text-center">

            {{-- ELIMINAR (pendiente o enviado) --}}
            @if (in_array($detalle->estado, ['pendiente','enviado_cocina']))
                <form action="{{ route('detallePedido.cancelar', $detalle->id) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            @endif


            {{-- ENTREGAR (solo si listo) --}}
            @if ($detalle->estado === 'listo')
                <form action="{{ route('detalles.entregar', $detalle->id) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-hand-holding-usd"></i>
                    </button>
                </form>
            @endif

        </td>

        </tr>

    @empty
        <tr>
            <td colspan="7" class="text-center text-muted">
                Aún no hay productos en este pedido.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>


</div>
@endsection
