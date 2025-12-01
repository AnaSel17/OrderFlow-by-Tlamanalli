<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\Mesa;
use App\Models\Pago;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;




class DashboardController extends Controller
{
public function index()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->id_rol) {

        case 1: // Administrador
            return redirect()->route('dashboard.admin');

        case 2: // Gerente General
            return redirect()->route('dashboard.admin'); 
            // Si luego quieres un dashboard especial, aquí lo cambiamos.

        case 3: // Mesero
            return redirect()->route('dashboard.mesero');

        case 4: // Cocinero
            return redirect()->route('dashboard.cocinero');

        case 5: // Cajero
            return redirect()->route('dashboard.cajero');

        default:
            abort(403, 'No tienes permiso para acceder al dashboard.');
    }
}


    public function cajero()
{
    // 1. Tomar fecha seleccionada o usar hoy
    $fecha = request('fecha', now()->toDateString());

    // Ventas del día
    $ventasHoy = Cuenta::whereHas('pedido', function ($q) use ($fecha) {
        $q->where('estado', 'pagado')
          ->whereDate('created_at', $fecha);
    })->sum('subtotal');

    // Total cobrado
    $totalCobrado = Pago::whereDate('created_at', $fecha)->sum('monto');

    // Pedidos listos para cobrar
    $listosParaCobrar = Pedido::where('estado', 'listo_para_cobrar')
        ->whereDate('created_at', $fecha)
        ->count();

    // Últimos pagos
    $ultimosPagos = Pago::whereDate('created_at', $fecha)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    // Gráfico por hora
    $labelsHoras = [];
    $dataHoras = [];

    for ($i = 0; $i <= 23; $i++) {
        $labelsHoras[] = sprintf('%02d:00', $i);

        $dataHoras[] = Cuenta::whereHas('pedido', function ($q) use ($fecha, $i) {
            $q->where('estado', 'pagado')
              ->whereDate('created_at', $fecha)
              ->whereRaw('EXTRACT(HOUR FROM created_at) = ?', [$i]);
        })->sum('subtotal');
    }

    return view('dashboard.cajero', compact(
        'ventasHoy', 'totalCobrado', 'listosParaCobrar',
        'ultimosPagos', 'labelsHoras', 'dataHoras'
    ));
}

    public function admin()
    {
        $hoy = now()->toDateString();

        // Ventas del día
        $ventasHoy = Cuenta::whereHas('pedido', function ($q) use ($hoy) {
            $q->where('estado', 'pagado')->whereDate('created_at', $hoy);
        })->sum('subtotal');

        // Ventas últimos 7 días
        $labels7dias = [];
        $data7dias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->toDateString();
            $labels7dias[] = now()->subDays($i)->format('d M');

            $data7dias[] = Cuenta::whereHas('pedido', function ($q) use ($fecha) {
                $q->where('estado', 'pagado')->whereDate('created_at', $fecha);
            })->sum('subtotal');
        }

        $ventasSemana = array_sum($data7dias);

        // Pedidos activos
        $pedidosActivos = Pedido::whereIn('estado', [
            'pendiente',
            'en_preparacion',
            'listo',
            'entregado'
        ])->count();

        // Mesas ocupadas
        $mesasOcupadas = Mesa::where('estado', 'ocupada')->count();
        $totalMesas = Mesa::count();

        // Productos más vendidos
        $ventasProductos = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total')
            ->groupBy('producto_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $labelsProductos = $ventasProductos->pluck('producto.nombre');
        $dataProductos = $ventasProductos->pluck('total');

        // Inventario bajo
        $inventarioBajo = Inventario::whereColumn('stock_actual', '<=', 'punto_reorden')->get();

        return view('dashboard.admin', compact(
            'ventasHoy',
            'ventasSemana',
            'pedidosActivos',
            'mesasOcupadas',
            'totalMesas',
            'labels7dias',
            'data7dias',
            'labelsProductos',
            'dataProductos',
            'inventarioBajo'
        ));
    }


    public function mesero(Request $request)
    {
        $user = auth()->user();

        // 📅 Fecha seleccionada o la de hoy
        $fecha = $request->get('fecha', now()->toDateString());

        // Pedidos activos del mesero EN ESA FECHA
        $pedidosHoy = Pedido::where('usuario_id', $user->id)
            ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo', 'listo_para_cobrar'])
            ->whereDate('pedidos.created_at', $fecha)   // 👈 FILTRAMOS POR FECHA
            ->orderBy('pedidos.created_at', 'desc')
            ->get();

        $pedidosActivos = $pedidosHoy->count();
        $pedidosListos = $pedidosHoy->where('estado', 'listo')->count();

        // Mesas que tiene asignadas
        $mesasAsignadas = Mesa::whereHas('pedidos', function ($q) use ($user, $fecha) {
            $q->where('usuario_id', $user->id)
                ->whereDate('pedidos.created_at', $fecha);   // 👈 Mesas solo de esa fecha
        })->count();

        // Actividad del día por hora
        $labelsHoras = [];
        $dataHoras = [];

        for ($i = 0; $i <= 23; $i++) {
            $labelsHoras[] = sprintf('%02d:00', $i);

            $dataHoras[] = Pedido::where('usuario_id', $user->id)
                ->whereDate('created_at', $fecha)   // 👈 FILTRAMOS POR FECHA
                ->whereRaw('EXTRACT(HOUR FROM created_at) = ?', [$i])
                ->count();
        }

        return view('dashboard.mesero', compact(
            'fecha',
            'pedidosHoy',
            'pedidosActivos',
            'pedidosListos',
            'mesasAsignadas',
            'labelsHoras',
            'dataHoras'
        ));
    }



    public function cocinero()
    {
        // Solo productos próximos a preparar
        $detalles = DetallePedido::with(['producto', 'pedido'])
            ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo'])
            ->orderByRaw("
    CASE estado
        WHEN 'pendiente' THEN 1
        WHEN 'en_preparacion' THEN 2
        WHEN 'listo' THEN 3
        ELSE 4
    END
")

            ->orderBy('created_at')
            ->get();

        return view('dashboard.cocinero', [
            'pendientes'    => $detalles->where('estado', 'pendiente')->count(),
            'enPreparacion' => $detalles->where('estado', 'en_preparacion')->count(),
            'listos'        => $detalles->where('estado', 'listo')->count(),
            'detalles'      => $detalles,
        ]);
    }
}
