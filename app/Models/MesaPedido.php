<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesaPedido extends Model
{
    /** @use HasFactory<\Database\Factories\MesaPedidoFactory> */
    use HasFactory;

    protected $fillable = [
        'mesa_id', 'pedido_id'
    ];
}
