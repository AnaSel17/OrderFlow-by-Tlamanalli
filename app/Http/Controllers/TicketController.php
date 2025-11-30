<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Pedido;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Imprime UN ticket específico (Ideal para reimpresiones o cuentas individuales)
    public function show(Cuenta $cuenta)
    {
        $cuenta->load(['detalles.detalle.producto', 'pagos', 'comensal']);
        
        // Obtenemos el pedido a través de la relación
        $pedido = $cuenta->pedido; 
        $pedido->load('usuario'); // Cargar mesero

        return view('tickets.ticket_completo', compact('cuenta', 'pedido'));
    }

    // Imprime TODOS los tickets del pedido (Tu lógica original para ver todo el panorama)
    public function showPorComensal(Pedido $pedido)
    {
        $cuentas = Cuenta::with(['detalles.detalle.producto', 'pagos', 'comensal'])
            ->where('pedido_id', $pedido->id)
            ->get();
            
        $pedido->load('usuario');

        return view('tickets.ticket_por_comensal', compact('pedido', 'cuentas'));
    }
}