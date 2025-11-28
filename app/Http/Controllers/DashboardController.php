<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // 1) Ventas del día (solo pedidos pagados)
        $ventasHoy = Pedido::whereDate('updated_at', $hoy)
            ->whereIn('estado', ['listo_para_cobrar', 'pagado'])
            ->sum('total');

        // 2) Pedidos activos
        $pedidosActivos = Pedido::whereIn('estado', ['en_preparacion','listo_para_entregar','pendiente'])
            ->count();

        // 3) Producto más vendido (real)
        $masVendido = DetallePedido::select('producto_id', DB::raw('SUM(cantidad) as total'))
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->with('producto')
            ->first();

        $nombreMasVendido = $masVendido ? $masVendido->producto->nombre : 'N/A';

        // 4) Nuevos clientes (si manejas usuarios)
        $nuevosClientes = \App\Models\User::whereDate('created_at', $hoy)->count();

        // 5) Ventas últimos 7 días
        $ventas7 = Pedido::select(
                DB::raw('DATE(updated_at) as fecha'),
                DB::raw('SUM(total) as total')
            )
            ->whereIn('estado', ['listo_para_cobrar','pagado'])
            ->where('updated_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->pluck('total','fecha');

        $labels7 = $ventas7->keys()->map(fn($d) => Carbon::parse($d)->format('d M'));
        $data7   = $ventas7->values();

        // 6) Categorías más vendidas
        $categorias = DetallePedido::select(
                'categorias.nombre',
                DB::raw('COUNT(*) as total')
            )
            ->join('productos', 'productos.id', '=', 'detalle_pedidos.producto_id')
            ->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
            ->groupBy('categorias.nombre')
            ->orderByDesc('total')
            ->pluck('total', 'categorias.nombre');

        $labelsCat = $categorias->keys();
        $dataCat   = $categorias->values();


        $metricas = [
    [
        'icon' => 'fas fa-dollar-sign',
        'label' => 'Ventas del día',
        'value' => '$' . number_format($ventasHoy, 2)
    ],
    [
        'icon' => 'fas fa-clipboard-check',
        'label' => 'Pedidos activos',
        'value' => $pedidosActivos
    ],
    [
        'icon' => 'fas fa-shopping-cart',
        'label' => 'Producto más vendido',
        'value' => $nombreMasVendido
    ],
    [
        'icon' => 'fas fa-user',
        'label' => 'Nuevos clientes',
        'value' => $nuevosClientes
    ]
];


        return view('dashboard.index', compact(
    'metricas',
    'labels7',
    'data7',
    'labelsCat',
    'dataCat'
));


        
    }

    
}
