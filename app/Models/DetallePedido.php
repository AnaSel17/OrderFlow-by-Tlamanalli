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
    'descuento',
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

        // Helpers
    public function getSubtotalAttribute()
    {
        return ($this->cantidad * $this->precio_unitario) - $this->descuento;
    }

    // Para CFDI (precio base sin IVA)
    public function getPrecioBaseSinIvaAttribute()
    {
        return round($this->precio_unitario / 1.16, 2);
    }

    public function getDescuentoBaseSinIvaAttribute()
    {
        return round($this->descuento / 1.16, 2);
    }

}
