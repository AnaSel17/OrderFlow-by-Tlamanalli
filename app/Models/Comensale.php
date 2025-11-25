<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comensale extends Model
{
    /** @use HasFactory<\Database\Factories\ComensaleFactory> */
    use HasFactory;

     protected $fillable = ['pedido_id', 'numero', 'nombre'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
