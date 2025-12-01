@extends('adminlte::page')

@section('title', 'Reportes')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-fluid px-5 py-4">

    <h3 class="card-title-custom text-center mb-4">REPORTES DEL SISTEMA</h3>

    <div class="list-group shadow-sm">

        <a href="{{ route('reportes.ventas-dia') }}" class="list-group-item list-group-item-action">
            Ventas por día
        </a>

        <a href="{{ route('reportes.ventas-producto') }}" class="list-group-item list-group-item-action">
            Ventas por producto
        </a>

        <a href="{{ route('reportes.top-clientes') }}" class="list-group-item list-group-item-action">
            Top clientes
        </a>

        <a href="{{ route('reportes.pedidos-estado') }}" class="list-group-item list-group-item-action">
            Pedidos por estado
        </a>

        <a href="{{ route('reportes.inventario-bajo') }}" class="list-group-item list-group-item-action">
            Inventario bajo
        </a>

        <a href="{{ route('reportes.exportar') }}" class="list-group-item list-group-item-action">
            Exportar (CSV / PDF)
        </a>

    </div>

</div>
@endsection
