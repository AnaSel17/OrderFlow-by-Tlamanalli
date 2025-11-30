<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetallePedidoRequest;
use App\Http\Requests\PedidoRequest;
use App\Models\Cuenta;
use App\Models\CuentaDetalle;
use App\Models\DetallePedido;
use App\Models\Mesa;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        // --- FILTROS DINÁMICOS ---
        $query = Pedido::with(['mesas', 'usuario']);

        // Filtrar por mesa
        if ($request->filled('mesa_id')) {
            $query->where('mesa_id', $request->mesa_id);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por fechas
        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        // --- CONSULTA PRINCIPAL ---
        $pedidos = $query->orderBy('created_at', 'desc')->paginate(10);

        // --- MÉTRICAS ---
        $totalPedidos = Pedido::count();
        $pedidosCompletados = Pedido::where('estado', 'pagado')->count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $totalVentas = Pedido::sum('total');

        // --- DATOS PARA FILTROS ---
        $mesas = Mesa::select('id', 'codigo')->orderBy('codigo')->get();
        $estados = ['pendiente','en_preparacion', 'listo', 'listo_para_cobrar', 'pagado', 'cancelado'];

        return view('pedidos.index', compact(
            'pedidos',
            'mesas',
            'estados',
            'totalPedidos',
            'pedidosCompletados',
            'pedidosPendientes',
            'totalVentas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    // ✅ Solo mesas disponibles
        $mesas = Mesa::where('estado', 'disponible')->get();

        // ✅ Solo meseros (si tienes relación con roles)
        $usuarios = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'Mesero');
        })->get();

        // Si aún no tienes roles implementados:
        // $usuarios = User::all();

        return view('pedidos.create', compact('mesas', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PedidoRequest $request)
    {
         
       $request->validate([
            'mesa_id' => 'required|exists:mesas,id',
            'usuario_id' => 'required|exists:users,id',
            'estado' => 'required|string',
            'total' => 'nullable|numeric|min:0'
        ]);

        $pedido = Pedido::create([
            'mesa_id' => $request->mesa_id,
            'usuario_id' => $request->usuario_id,
            'estado' => $request->estado,
            'total' => $request->total ?? 0,
        ]);

        // Cambiar estado de la mesa a "ocupada"
        Mesa::where('id', $request->mesa_id)->update(['estado' => 'ocupada']);

        return redirect()->route('pedidos.index')->with('success', 'Pedido creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        // Cargar cuentas asociadas al pedido
    $cuentas = Cuenta::with([
        'detalles.detalle.producto',
        'pagos',
        'comensal'
    ])
    ->where('pedido_id', $pedido->id)
    ->get();

    return view('pedidos.show', compact('pedido', 'cuentas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        $pedido->load(['mesas', 'usuario', 'detalles.producto', 'comensales']);
        $productos = Producto::orderBy('nombre')->get();
        $comensales = $pedido->comensales()->orderBy('numero')->get();

        return view('pedidos.edit', compact('pedido', 'productos', 'comensales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PedidoRequest $request, Pedido $pedido)
    {
         DB::transaction(function () use ($request, $pedido) {
            $pedido->update([
                'mesa_id' => $request->mesa_id,
                'estado' => $request->estado,
                'propina' => $request->propina ?? 0,
            ]);

            // Actualizar detalles del pedido si vienen productos
            if ($request->has('productos')) {
                $pedido->detalles()->delete(); // eliminar los anteriores
                $total = 0;

                foreach ($request->productos as $item) {
                    $producto = Producto::findOrFail($item['id']);
                    $subtotal = $producto->precio * $item['cantidad'];
                    $total += $subtotal;

                    DetallePedido::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $producto->precio,
                        'notas' => $item['notas'] ?? null,
                    ]);
                }

                $pedido->update(['total' => $total]);
            }
        });

        return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        DB::transaction(function () use ($pedido) {
            $pedido->detalles()->delete();
            $pedido->delete();
        });

        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado correctamente.');
    }

    public function cobrar(Pedido $pedido)
    {
        $pedido->load(['detalles.producto', 'detalles.comensal','cuentas']);

        return view('pedidos.cobrar', compact('pedido'));
    }



// public function ticket(Pedido $pedido)
// {
//     $cuentas = Cuenta::with(['detalles.detalle.producto', 'pagos', 'comensal'])
//         ->where('pedido_id', $pedido->id)
//         ->get();

//     if ($cuentas->count() === 1 && $cuentas->first()->tipo === 'completa') {
//         $cuenta = $cuentas->first();
//         return view('tickets.ticket_completo', compact('pedido', 'cuenta'));
//     }

//     return view('tickets.ticket_por_comensal', compact('pedido', 'cuentas'));
// }


}
