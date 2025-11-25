<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    /** @use HasFactory<\Database\Factories\ComandaFactory> */
    use HasFactory;

     protected $fillable = [
        'pedido_id',
        'estado',
        'enviada_en',
    ];

    protected $casts = [
    'enviada_en' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function getRondaAttribute()
    {
        return Comanda::where('pedido_id', $this->pedido_id)
            ->where('id', '<=', $this->id)
            ->count();
    }

    public function getEstadoTextoAttribute()
{
    if ($this->detalles->every(fn($d) => $d->estado == 'listo')) {
        return 'listo';
    }

    if ($this->detalles->contains(fn($d) => $d->estado == 'en_preparacion')) {
        return 'en_preparacion';
    }

    return 'pendiente';
}

    }

