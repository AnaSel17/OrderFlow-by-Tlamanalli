@extends('adminlte::page')

@section('title', 'Inventario de Productos')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-actividad py-4">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="m-0 text-dark">Control de Inventario</h1>

        <a href="{{ route('inventarios.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Agregar Registro
        </a>
    </div>


    <!-- Tabla -->
    <div class="card card-outline card-primary">
        <div class="card-header border-0">
            <h3 class="card-title">Listado de Inventario</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Punto Reorden</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($inventarios as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->producto->nombre }}</td>

                            <td class="text-center fw-bold">{{ $item->stock_actual }}</td>
                            <td class="text-center">{{ $item->punto_reorden }}</td>

                            <td class="text-center">
                                @if ($item->estado == 'Agotado')
                                    <span class="badge bg-danger">Agotado</span>
                                @elseif ($item->estado == 'Bajo')
                                    <span class="badge bg-warning text-dark">Bajo</span>
                                @else
                                    <span class="badge bg-success">Suficiente</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('inventarios.edit', $item) }}"
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('inventarios.destroy', $item) }}"
                                      method="POST"
                                      class="d-inline form-eliminar"
                                      onsubmit="return false;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No hay registros en inventario.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="card-footer d-flex justify-content-center">
            {{ $inventarios->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {

            let form = this.closest('.form-eliminar');

            Swal.fire({
                title: "¿Eliminar registro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#8C3F2E",
                cancelButtonColor: "#6C757D",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    });

});
</script>
@endpush
