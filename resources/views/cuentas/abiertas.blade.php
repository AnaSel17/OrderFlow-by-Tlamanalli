@extends('adminlte::page')

@section('title', 'Cuentas Abiertas')

@section('content')
<div class="container-fluid py-4">

    <h1 class="mb-4"><i class="fas fa-folder-open"></i> Cuentas Abiertas</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning">
            <h5 class="fw-bold text-white m-0">Cuentas sin finalizar</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#Cuenta</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Propina</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuentas as $cuenta)
                    <tr>
                        <td>{{ $cuenta->id }}</td>
                        <td>#{{ $cuenta->pedido->id }}</td>
                        <td>
                            @if($cuenta->tipo === 'comensal')
                                Persona {{ $cuenta->comensal->numero }}
                            @else
                                Completa
                            @endif
                        </td>
                        <td>${{ number_format($cuenta->subtotal, 2) }}</td>
                        <td>${{ number_format($cuenta->propina, 2) }}</td>
                        <td>${{ number_format($cuenta->total, 2) }}</td>
                        <td>{{ ucfirst($cuenta->estado) }}</td>
                        <td>{{ $cuenta->created_at->format('d/m/Y H:i') }}</td>

                        <td class="text-center">
                            <a href="{{ route('pedidos.cobrar', $cuenta->pedido) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-cash-register"></i> Continuar cobro
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-center">
            {{ $cuentas->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection
