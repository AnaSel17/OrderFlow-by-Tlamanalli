<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    /** @use HasFactory<\Database\Factories\DetallePedidoFactory> */
    use HasFactory;

    protected $table = 'detalle_pedidos';

    protected $fillable = [
        'pedido_id',
    'producto_id',
    'cantidad',
    'precio_unitario',
    'notas',
    'comensal_id',
    'comanda_id',
    'estado',
    ];

    protected $attributes = [
        'estado' => 'pendiente',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function comensal()
    {
        return $this->belongsTo(Comensale::class);
    }

    public function comanda()
    {
        return $this->belongsTo(Comanda::class);
    }

    public function cuentaDetalles()
    {
        return $this->hasMany(CuentaDetalle::class, 'detalle_id');
    }

}
