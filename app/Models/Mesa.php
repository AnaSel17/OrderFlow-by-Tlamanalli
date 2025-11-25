<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    /** @use HasFactory<\Database\Factories\MesaFactory> */
    use HasFactory;

    protected $fillable = [
        'codigo',
        'capacidad',
        'sillas_extra',
        'estado',
        'tipo',
        'zona_id',
    ];

       /**
     * Relación: una mesa pertenece a una zona
     */
    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'mesa_pedidos', 'mesa_id', 'pedido_id');
    }

    public function getCapacidadTotalAttribute()
    {
        return $this->capacidad + $this->sillas_extra;
    }

    public function getMesasTextoAttribute(): string
{
    return $this->mesas->pluck('codigo')->join(' + ');
}


     public function disponible(): bool
    {
        return $this->estado === 'disponible' && $this->zona && $this->zona->estaAbierta();
    }
}
