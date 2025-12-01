@extends('adminlte::page')

@section('title', 'Comandas en cocina')

@section('content')
<div class="container-fluid py-4 px-4">

    <h1 class="mb-4 text-dark">
        <i class="fas fa-concierge-bell"></i> Comandas en Cocina
    </h1>

    {{-- Mensajes --}}
    @foreach (['success','info','error'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- Sin comandas --}}
    @if ($comandas->isEmpty())
        <div class="alert alert-warning text-center fw-bold">
            ⚠️ No hay comandas activas en cocina por el momento.
        </div>
    @endif

    <div class="row">
        @foreach ($comandas as $comanda)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 mb-3">

                    {{-- ENCABEZADO --}}
                    <div class="card-header fw-bold bg-gradient
                        @if($comanda->estado === 'enviado_cocina') bg-info text-white
                        @elseif($comanda->estado === 'en_preparacion') bg-warning text-dark
                        @elseif($comanda->estado === 'listo') bg-success text-white
                        @elseif($comanda->estado === 'entregada') bg-secondary text-white
                        @endif">

                        <div class="d-flex justify-content-between align-items-center">
                            <span>🧾 Comanda #{{ $comanda->id }}</span>
                            <small>{{ ucfirst(str_replace('_',' ',$comanda->estado)) }}</small>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 12px !important;">

                        {{-- INFO GENERAL --}}
                        <p class="mb-1"><strong>Mesas:</strong> {{ $comanda->pedido->mesas_texto ?? 'Sin mesa' }}</p>
                        <p class="mb-2"><strong>Mesero:</strong> {{ $comanda->pedido->usuario->name ?? '—' }}</p>

                        {{-- PRODUCTOS AGRUPADOS --}}
                        @php
                            $detalles = $comanda->pedido->detalles->where('comanda_id', $comanda->id);
                            $grupos   = $detalles->groupBy('producto_id');
                        @endphp

                        @foreach ($grupos as $items)

                            <div class="p-2 border rounded bg-light mb-2" style="padding: 8px !important;">

                                {{-- ENCABEZADO DEL PRODUCTO --}}
                                <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 4px;">

                                    <div>
                                        <strong>{{ $items->first()->producto->nombre }}</strong>

                                        {{-- NOTAS AGRUPADAS COMPACTAS --}}
                                        @php
                                            $notasAgrupadas = $items->groupBy('notas')
                                                ->map->sum('cantidad')
                                                ->filter(fn($count, $nota) => trim($nota) !== '');
                                        @endphp

                                        <small class="text-muted d-block mt-1">
                                            @if ($notasAgrupadas->isEmpty())
                                                Notas: —
                                            @else
                                                @foreach ($notasAgrupadas as $nota => $cantidad)
                                                    <span class="badge bg-secondary me-1">
                                                        {{ $cantidad }} × {{ $nota }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </small>
                                    </div>

                                    <span class="badge bg-primary">
                                        Total: {{ $items->sum('cantidad') }}
                                    </span>
                                </div>

                                {{-- BOTONES DEL GRUPO --}}
                                @php
                                    $hayEnviado    = $items->contains('estado', 'enviado_cocina');
                                    $hayPreparando = $items->contains('estado', 'en_preparacion');
                                    $todosListos   = $items->every(fn($d) => $d->estado === 'listo');
                                @endphp

                                <div class="d-flex gap-1 mb-2">

                                    @if($hayEnviado && !$todosListos)
                                        <form action="{{ route('detalles.preparar.grupo') }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="ids" value="{{ $items->pluck('id')->join(',') }}">
                                            <button class="btn btn-sm btn-warning w-100">🔥 Preparar todo</button>
                                        </form>
                                    @endif

                                    @if($hayPreparando)
                                        <form action="{{ route('detalles.listo.grupo') }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="ids" value="{{ $items->pluck('id')->join(',') }}">
                                            <button class="btn btn-sm btn-success w-100">✔ Listo todo</button>
                                        </form>
                                    @endif

                                </div>

                                {{-- DETALLE INDIVIDUAL --}}
                                @foreach ($items as $detalle)
                                    <div class="p-1 mb-1 border rounded d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">

                                        <small class="text-muted">
                                            Cantidad: {{ $detalle->cantidad }}
                                        </small>

                                        <span class="badge
                                            @if($detalle->estado === 'pendiente') bg-secondary
                                            @elseif($detalle->estado === 'en_preparacion') bg-warning
                                            @elseif($detalle->estado === 'listo') bg-success
                                            @endif">
                                            {{ ucfirst(str_replace('_',' ', $detalle->estado)) }}
                                        </span>

                                        <div>

                                            @if($detalle->estado === 'enviado_cocina')
                                                <form action="{{ route('detalles.preparar', $detalle->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-warning">🔥</button>
                                                </form>
                                            @endif

                                            @if($detalle->estado === 'en_preparacion')
                                                <form action="{{ route('detalles.listo', $detalle->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-success">✔</button>
                                                </form>
                                            @endif

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        @endforeach

                        {{-- ESTADO GLOBAL --}}
                        @php
                            $total  = $comanda->detalles->where('comanda_id', $comanda->id);
                            $prep   = $total->where('estado','en_preparacion')->count();
                            $listos = $total->where('estado','listo')->count();
                        @endphp

                        @if ($listos === $total->count())
                            <button class="btn btn-success w-100 fw-bold" disabled>🟢 Comanda lista para entregar</button>
                        @elseif ($prep > 0)
                            <button class="btn btn-warning w-100 fw-bold" disabled>🟡 En preparación…</button>
                        @else
                            <button class="btn btn-info w-100 fw-bold" disabled>🔵 Esperando inicio</button>
                        @endif

                    </div>

                    <div class="card-footer text-muted text-center small">
                        <i class="far fa-clock"></i>
                        Enviada: {{ $comanda->enviada_en ? $comanda->enviada_en->format('H:i') : '—' }}
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
