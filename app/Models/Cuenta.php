<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    /** @use HasFactory<\Database\Factories\CuentaFactory> */
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'comensal_id',
        'usuario_id',
        'tipo',
        'subtotal',
        'descuento',
        'propina',
        'total',
        'estado',
    ];

    public function pedido()  { return $this->belongsTo(Pedido::class); }
    public function comensal() { return $this->belongsTo(Comensale::class); }
    public function usuario()  { return $this->belongsTo(User::class); }

    public function detalles() { return $this->hasMany(CuentaDetalle::class); }
    public function pagos()    { return $this->hasMany(Pago::class); }
}
