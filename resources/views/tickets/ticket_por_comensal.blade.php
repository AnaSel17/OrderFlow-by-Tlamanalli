@extends('adminlte::page')

@section('content')
<div class="container py-3">

@foreach($cuentas as $cuenta)
<div class="ticket mb-5">

    <h3>🍽 {{ config('app.name') }}</h3>
    <h5>Ticket por Comensal</h5>

    <div class="line"></div>

    <p>
        <strong>Pedido:</strong> #{{ $pedido->id }} <br>
        <strong>Persona:</strong> 
        {{ $cuenta->comensal ? 'Persona '.$cuenta->comensal->numero : 'General' }} <br>
        <strong>Fecha:</strong> {{ $cuenta->created_at->format('d/m/Y H:i') }}
    </p>

    <div class="line"></div>

    <h5>🧾 Detalles</h5>

    <table>
        @foreach ($cuenta->detalles as $d)
            @if ($d->comensal_id == $cuenta->comensal_id)
                <tr>
                    <td class="desc">{{ $d->detalle->producto->nombre }}</td>
                    <td class="total">${{ number_format($d->subtotal_asignado, 2) }}</td>
                </tr>
            @endif
        @endforeach
    </table>

    <div class="line"></div>

    <p>
        Subtotal: <span class="text-right">${{ number_format($cuenta->subtotal, 2) }}</span><br>
        Propina: <span class="text-right">${{ number_format($cuenta->propina, 2) }}</span><br>
        <strong>Total:</strong> <span class="text-right">${{ number_format($cuenta->total, 2) }}</span>
    </p>

    <div class="line"></div>

    <h5>💳 Pagos</h5>
    @foreach ($cuenta->pagos as $p)
        <p>
            {{ ucfirst($p->metodo) }} —
            ${{ number_format($p->monto, 2) }} <br>
            @if($p->referencia)
                <small class="small">Ref: {{ $p->referencia }}</small><br>
            @endif
        </p>
    @endforeach

    <div class="line"></div>

    <p class="text-center small">
        Gracias por su visita 🙌  
    </p>

</div>
@endforeach

<button onclick="window.print()" class="btn btn-primary w-100 no-print mb-5">
    Imprimir Todos los Tickets
</button>

</div>
@endsection
