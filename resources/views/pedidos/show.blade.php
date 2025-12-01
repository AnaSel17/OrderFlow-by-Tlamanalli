@extends('adminlte::page')

@section('title', "Pedido #{$pedido->id}")

@section('content')

<div class="container-fluid px-4 py-4">

    {{-- Botón regresar --}}
    <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <h1 class="mb-4">
        <i class="fas fa-utensils"></i> Pedido #{{ $pedido->id }}
    </h1>

    {{-- MENSAJES --}}
    @foreach (['success','info','error'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach


    {{-- ============================================= --}}
    {{-- 🔴 SI EL PEDIDO ESTÁ CANCELADO → BLOQUEAR TODO --}}
    {{-- ============================================= --}}
    @if ($pedido->estado === 'cancelado')
        <div class="alert alert-danger text-center fw-bold">
            <i class="fas fa-ban"></i> Este pedido está CANCELADO.
            <br> No se puede ver cuentas ni cobrar.
        </div>

        <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>

       
    @endif



    {{-- ============================================= --}}
    {{-- INFORMACIÓN GENERAL --}}
    {{-- ============================================= --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light">
            <strong><i class="fas fa-info-circle"></i> Información del pedido</strong>
        </div>
        <div class="card-body">
            <strong>Mesas:</strong> {{ $pedido->mesas_texto }} <br>
            <strong>Mesero:</strong> {{ $pedido->usuario->name }} <br>
            <strong>Comensales:</strong> {{ $pedido->comensales->count() }} <br>
            <strong>Estado:</strong> {!! $pedido->estado_texto !!} <br>
            <strong>Total:</strong> ${{ number_format($pedido->total, 2) }}
        </div>
    </div>



    {{-- ============================================= --}}
    {{-- 🔍 CÁLCULO PARA MOSTRAR / OCULTAR IR A COBRAR --}}
    {{-- ============================================= --}}

    @php
        // detalles del pedido
        $detalles = $pedido->detalles;

        $hayDetalles = $detalles->count() > 0;
        $listos = $detalles->where('estado', 'listo')->count();
        $entregados = $detalles->where('estado', 'entregado')->count();
        $cancelados = $detalles->where('estado', 'cancelado')->count();

        // condición: ¿hay algo para cobrar?
        $puedeCobrar = $entregados > 0
                        || $pedido->estado === 'listo_para_cobrar';
    @endphp



    {{-- ============================================= --}}
    {{-- 🔵 SI NO HAY DETALLES → NO PERMITIR COBRAR --}}
    {{-- ============================================= --}}
    @if (!$hayDetalles || $cancelados === $detalles->count())
        <div class="alert alert-info text-center fw-bold">
            Este pedido no tiene productos activos.
            <br>No se puede cobrar.
        </div>

        <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>

        
    @endif


    {{-- ============================================= --}}
    {{-- 🔥 MOSTRAR BOTÓN IR A COBRAR SOLO SI ES POSIBLE --}}
    {{-- ============================================= --}}
    @if ($puedeCobrar)
        <div class="alert alert-success text-center fw-bold">
            <i class="fas fa-check-circle"></i> Este pedido está listo para cobrar.
            <br>
            <a href="{{ route('pedidos.cobrar', $pedido) }}" class="btn btn-primary mt-2">
                <i class="fas fa-cash-register"></i> Ir a cobrar
            </a>
        </div>
    @else
        <div class="alert alert-warning text-center fw-bold">
            <i class="fas fa-clock"></i> Aún no hay productos entregados para cobrar.
        </div>
    @endif



    {{-- ============================================= --}}
    {{-- CUENTAS GENERADAS --}}
    {{-- ============================================= --}}
    <h4 class="mb-3"><i class="fas fa-wallet"></i> Cuentas generadas</h4>

    @foreach ($cuentas as $cuenta)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <strong>
                    Cuenta 
                    @if($cuenta->tipo === 'completa')
                        Completa
                    @else
                        Persona {{ $cuenta->comensal->numero ?? '' }}
                    @endif
                </strong>
            </div>

            <div class="card-body">

                {{-- DATOS GENERALES --}}
                <p>
                    <strong>Subtotal:</strong> ${{ number_format($cuenta->subtotal, 2) }} <br>
                    <strong>Propina:</strong> ${{ number_format($cuenta->propina, 2) }} <br>
                    <strong>Total:</strong> <span class="text-success fw-bold">${{ number_format($cuenta->total, 2) }}</span><br>
                    <strong>Estado:</strong> {{ ucfirst($cuenta->estado) }}
                </p>

                {{-- LISTA DE DETALLES --}}
                <h6 class="fw-bold mt-3">Detalles:</h6>
                <ul class="list-group mb-3">
                    @foreach ($cuenta->detalles as $cd)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $cd->detalle->producto->nombre }}</span>
                            <span>${{ number_format($cd->subtotal_asignado, 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                {{-- PAGOS --}}
                <h6 class="fw-bold mt-3">Pagos:</h6>

                @if ($cuenta->pagos->isEmpty())
                    <p class="text-muted fst-italic">Sin pagos registrados.</p>
                @else
                    <ul class="list-group">
                        @foreach ($cuenta->pagos as $pago)
                            <li class="list-group-item">
                                <strong>{{ ucfirst($pago->metodo) }}</strong>
                                — ${{ number_format($pago->monto, 2) }}
                                @if ($pago->referencia)
                                    <br><small class="text-muted">Ref: {{ $pago->referencia }}</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                {{-- BOTÓN DE TICKET --}}
                <a href="{{ route('pedidos.ticket', $pedido) }}" class="btn btn-success mt-3">
                    <i class="fas fa-receipt"></i> Ver / Imprimir Ticket
                </a>

            </div>
        </div>
    @endforeach

    {{-- ============================================= --}}
{{-- 🔵 BOTÓN “SEGUIR COBRANDO” SI EL PEDIDO AÚN NO ESTÁ PAGADO --}}
{{-- ============================================= --}}
@if ($pedido->estado !== 'pagado' && $cuentas->count() > 0)
    <div class="text-center my-4">
        <a href="{{ route('pedidos.cobrar', $pedido) }}" class="btn btn-primary btn-lg">
            <i class="fas fa-cash-register"></i> Seguir cobrando
        </a>
    </div>
@endif


    {{-- Botón regresar al final --}}
    <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

</div>
@endsection
