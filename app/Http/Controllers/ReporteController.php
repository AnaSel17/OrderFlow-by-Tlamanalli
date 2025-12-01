<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    /* ===========================
       VENTAS POR DÍA
    ============================ */
public function ventasDia(Request $request)
{
    $fecha = $request->fecha ?? now()->toDateString();

    // 🔥 Ventas sin propina: solo subtotal real
    $ventas = Cuenta::whereHas('pedido', function ($q) use ($fecha) {
        $q->whereDate('created_at', $fecha)
          ->where('estado', 'pagado');
    })
    ->sum('subtotal');

    $pedidos = Pedido::whereDate('created_at', $fecha)
        ->where('estado', 'pagado')
        ->get();

    return view('reportes.ventas-dia', compact('fecha', 'ventas', 'pedidos'));
}




    /* ===========================
       VENTAS POR PRODUCTO
    ============================ */
public function ventasProducto(Request $request)
{
    $fecha = $request->fecha ?? now()->toDateString();

    $ventas = \App\Models\DetallePedido::select(
            'productos.nombre',
            DB::raw('SUM(detalle_pedidos.cantidad) AS cantidad_total'),
            DB::raw('SUM(detalle_pedidos.cantidad * (detalle_pedidos.precio_unitario - detalle_pedidos.descuento)) AS total_vendido')
        )
        ->join('productos', 'productos.id', '=', 'detalle_pedidos.producto_id')
        ->join('pedidos', 'pedidos.id', '=', 'detalle_pedidos.pedido_id')
        ->join('cuentas', 'cuentas.pedido_id', '=', 'pedidos.id') // para validar que está pagado
        ->whereDate('pedidos.created_at', $fecha)
        ->where('pedidos.estado', 'pagado')
        ->groupBy('productos.nombre')
        ->orderByDesc('total_vendido')
        ->get();

    $labels = $ventas->pluck('nombre');
    $data = $ventas->pluck('total_vendido');

    return view('reportes.ventas-producto', compact('fecha', 'ventas', 'labels', 'data'));
}


    /* ===========================
       TOP CLIENTES
    ============================ */
    public function topClientes()
    {
        $clientes = Pedido::select('usuario_id', DB::raw('SUM(total) AS total_gastado'))
            ->with('usuario')
            ->where('estado', 'pagado')
            ->groupBy('usuario_id')
            ->orderByDesc('total_gastado')
            ->take(10)
            ->get();

        return view('reportes.top-clientes', compact('clientes'));
    }

    /* ===========================
       PEDIDOS POR ESTADO
    ============================ */
    public function pedidosEstado()
    {
        $data = Pedido::select('estado', DB::raw('COUNT(*) AS total'))
            ->groupBy('estado')
            ->get();

        return view('reportes.pedidos-estado', compact('data'));
    }

    /* ===========================
       INVENTARIO BAJO
    ============================ */
    public function inventarioBajo()
    {
        $items = Inventario::whereColumn('stock_actual', '<=', 'punto_reorden')
            ->with('producto')
            ->get();

        return view('reportes.inventario-bajo', compact('items'));
    }

    /* ===========================
       EXPORTAR PDF / CSV
    ============================ */
    public function exportar()
    {
        return view('reportes.exportar');
    }
}
