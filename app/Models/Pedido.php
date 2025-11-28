<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoFactory> */
    use HasFactory;

      protected $fillable = [
        'usuario_id',
        'estado',
        'total',
        'propina',
        'abierta_en',
        'cerrada_en',
        'num_comensales',
        'modo_cuenta',
    ];

     protected $casts = [
        'abierta_en' => 'datetime',
        'cerrada_en' => 'datetime',
    ];

    public function mesas()
    {
        return $this->belongsToMany(Mesa::class, 'mesa_pedidos', 'pedido_id', 'mesa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

        public function comandas()
    {
        return $this->hasMany(Comanda::class);
    }

    public function comensales()
    {
        return $this->hasMany(Comensale::class);
    }

    /**
     * Devuelve un texto legible con los códigos de las mesas combinadas.
     */
    public function getMesasTextoAttribute(): string
    {
        return $this->mesas->pluck('codigo')->join(' + ');
    }

    /**
     * Suma total de capacidad de las mesas asociadas al pedido.
     */
    public function getCapacidadTotalAttribute(): int
    {
        return $this->mesas->sum('capacidad');
    }

    /**
     * Formatea el estado del pedido con un texto amigable.
     */
    public function getEstadoTextoAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => '🟡 Pendiente',
            'enviado_cocina'   => '📤 Enviado a cocina',
            'en_preparacion' => '🧑‍🍳 En preparación',
            'listo' => '✅ Listo para entregar',
            'entregado' => '💰 Entregado',
            'cerrado' => '🔒 Cerrado',
            'cancelado' => '❌ Cancelado',
            default => ucfirst($this->estado),
        };
    }

    /**
     * Devuelve el tiempo transcurrido desde que se abrió el pedido.
     */
    public function getDuracionAttribute(): ?string
    {
        if (!$this->abierta_en) return null;
        $cerrada = $this->cerrada_en ?? now();
        return $this->abierta_en->diffForHumans($cerrada, true);
    }

    /**
     * Indica si el pedido está activo (no cerrado ni cancelado).
     */
    public function getActivoAttribute(): bool
    {
        return !in_array($this->estado, ['cerrado', 'cancelado']);
    }

    public function cuentas()
    {
        return $this->hasMany(Cuenta::class);
    }


    
    
}
