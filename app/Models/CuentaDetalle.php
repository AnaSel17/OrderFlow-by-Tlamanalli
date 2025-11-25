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
        'subtotal_asignado',
    ];

    public function cuenta() { return $this->belongsTo(Cuenta::class); }
    public function detalle() { return $this->belongsTo(DetallePedido::class); }
}
