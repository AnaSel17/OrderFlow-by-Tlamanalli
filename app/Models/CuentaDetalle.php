<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\CuentaDetalleFactory> */
    use HasFactory;

     protected $fillable = [
        'cuenta_id',
        'detalle_id',
        'comensal_id',
        'cantidad_asignada',
        'precio_unitario',
        'descuento',  
        'subtotal_asignado',
    ];

    public function cuenta() { return $this->belongsTo(Cuenta::class); }
    public function detalle() { return $this->belongsTo(DetallePedido::class); }

     public function getSubtotalAttribute()
    {
        return ($this->cantidad_asignada * $this->precio_unitario) - $this->descuento;
    }

    // Para CFDI simulado
    public function getBaseSinIvaAttribute()
    {
        return round($this->precio_unitario / 1.16, 2);
    }

    public function getDescuentoSinIvaAttribute()
    {
        return round($this->descuento / 1.16, 2);
    }
}
