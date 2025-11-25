<div class="card mb-3 shadow-sm border">
    <div class="card-header fw-bold bg-light">
        Mesa(s): {{ $comanda->pedido->mesas_texto }}
        <span class="float-end text-muted">
            🧾 Comanda #{{ $comanda->id }}
        </span>
    </div>

    <div class="card-body">
        <ul>
            @foreach ($comanda->detalles as $detalle)
                <li>
                    {{ $detalle->producto->nombre }} × {{ $detalle->cantidad }}
                    <span class="text-muted">(${{ number_format($detalle->precio_unitario, 2) }})</span>
                </li>
            @endforeach
        </ul>

        {{-- 🔘 Botones según estado --}}
        @if($comanda->estado === 'enviado_cocina')
            <form action="{{ route('comandas.start', $comanda->id) }}" method="POST">
                @csrf
                <button class="btn btn-warning btn-sm w-100">
                    🔥 Comenzar preparación
                </button>
            </form>
        @elseif($comanda->estado === 'en_preparacion')
            <form action="{{ route('comandas.finish', $comanda->id) }}" method="POST">
                @csrf
                <button class="btn btn-success btn-sm w-100">
                    ✅ Marcar como listo
                </button>
            </form>
        @endif
    </div>
</div>
