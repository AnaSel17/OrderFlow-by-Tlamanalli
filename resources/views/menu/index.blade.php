@extends('adminlte::page')

@section('title', 'Menú Tonalli Café')

@push('css')
<style>
    /* ============================
       ESTILOS GENERALES PARA PANTALLA
       ============================ */
    body {
        background-color: #f7f3ee;
    }

    .menu-wrapper {
        max-width: 1100px;
        margin: auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .menu-title {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #5D2728;
        font-family: 'Georgia', serif;
    }

    /* COLUMNA DE MENÚ */
    .menu-columns {
        column-count: 2;
        column-gap: 50px;
    }

    .categoria-box {
        break-inside: avoid;
        margin-bottom: 30px;
        padding: 10px 0;
    }

    .categoria-title {
        background: #5D2728;
        color: white;
        display: inline-block;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .menu-item {
        display: flex;
        justify-content: space-between;
        font-size: 15px;
        padding: 4px 0;
        border-bottom: 1px dashed #d3c7b9;
    }

    .menu-item span:last-child {
        font-weight: bold;
        color: #5D2728;
    }

    /* BOTÓN IMPRIMIR */
    .btn-print {
        position: fixed;
        right: 20px;
        top: 20px;
        z-index: 999;
    }


    /* ============================
       ⏳ ESTILOS PARA IMPRESIÓN
       ============================ */
    @media print {

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        /* Ocultar adminLTE */
        .main-sidebar,
        .main-header,
        .btn-print,
        .no-print {
            display: none !important;
        }

        .menu-wrapper {
            box-shadow: none;
            border-radius: 0;
            width: 100%;
            max-width: 100%;
            padding: 10px;
        }

        /* Columnas obligatorias */
        .menu-columns {
            column-count: 2 !important;
            column-gap: 30px !important;
        }

        body {
            background: white !important;
            font-size: 16px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@section('content')

<button class="btn btn-primary btn-print no-print" onclick="window.print()">
    <i class="fas fa-print"></i> Imprimir Menú
</button>

<div class="menu-wrapper">

    <h1 class="menu-title">Menú Tonalli Café</h1>

    <div class="menu-columns">

        @foreach($categorias as $cat)
            <div class="categoria-box">

                <div class="categoria-title">{{ $cat->nombre }}</div>

                @foreach($cat->productos as $prod)
                    <div class="menu-item">
                        <span>{{ $prod->nombre }}</span>
                        <span>${{ number_format($prod->precio, 2) }}</span>
                    </div>
                @endforeach

            </div>
        @endforeach

    </div>
</div>

@endsection

