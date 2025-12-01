@extends('adminlte::page')

@section('title', 'Gestión de Pedidos')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
    <div class="container-actividad py-4">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h1 class="m-0 text-dark">Gestión de Pedidos</h1>
            <a href="{{ route('pedidos.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Pedido
            </a>

        </div>

        <!-- Barra de filtros -->
        <form method="GET" action="{{ route('pedidos.index') }}" class="row filter-bar-custom mb-4">
            <div class="col-md-3">
                <label for="mesa_id" class="form-label fw-bold">Mesa</label>
                <select name="mesa_id" id="mesa_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->id }}" {{ request('mesa_id') == $mesa->id ? 'selected' : '' }}>
                            {{ $mesa->codigo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="estado" class="form-label fw-bold">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $estado)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="desde" class="form-label fw-bold">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label for="hasta" class="form-label fw-bold">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-info w-100">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>

        <!-- Métricas -->
        <div class="row metrics-grid mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="info-box bg-primary elevation-2">
                    <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pedidos</span>
                        <span class="info-box-number">{{ $totalPedidos }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="info-box bg-warning elevation-2">
                    <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendientes</span>
                        <span class="info-box-number">{{ $pedidosPendientes }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="info-box bg-success elevation-2">
                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Completados</span>
                        <span class="info-box-number">{{ $pedidosCompletados }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="info-box bg-info elevation-2">
                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Ventas</span>
                        <span class="info-box-number">${{ number_format($totalVentas, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de pedidos -->
        <div class="card card-outline card-primary">
            <div class="card-header border-0">
                <h3 class="card-title">Listado de Pedidos</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Mesa</th>
                            <th>Mesero</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->id }}</td>
                                <td> {{ $pedido->mesas_texto }}</td>
                                <td>{{ $pedido->usuario->name ?? '—' }}</td>
                                <td>
                                    <span
                                        class="badge 
                                    @if ($pedido->estado === 'pendiente') bg-warning
                                    @elseif($pedido->estado === 'en_preparacion') bg-primary
                                    @elseif($pedido->estado === 'listo') bg-info
                                    @elseif($pedido->estado === 'listo_para_cobrar') bg-success
                                    @elseif($pedido->estado === 'pagado') bg-dark
                                    @elseif($pedido->estado === 'cancelado') bg-danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                    </span>


                                </td>
                                <td>${{ number_format($pedido->total, 2) }}</td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>

                                <td class="text-center">
                                    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-utensils"></i>
                                    </a>

                                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if ($pedido->estado === 'listo' || $pedido->estado === 'listo_para_cobrar')
                                        <a href="{{ route('pedidos.cobrar', $pedido) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-cash-register"></i>
                                        </a>
                                    @endif



                                    <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST"
                                        class="d-inline form-cancelar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-cancelar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay pedidos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-center">
                {{ $pedidos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            document.querySelectorAll('.btn-cancelar').forEach(btn => {

                btn.addEventListener('click', function() {

                    let form = this.closest('.form-cancelar');

                    Swal.fire({
                        title: "¿Cancelar pedido?",
                        text: "El pedido será marcado como CANCELADO y la mesa quedará libre.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#8C3F2E",
                        cancelButtonColor: "#6C757D",
                        confirmButtonText: "Sí, cancelar",
                        cancelButtonText: "No, volver"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

                });

            });

        });
    </script>
@endpush
