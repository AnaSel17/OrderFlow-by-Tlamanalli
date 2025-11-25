<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    /** @use HasFactory<\Database\Factories\InventarioFactory> */
    use HasFactory;
    
    protected $table = 'inventarios';
    protected $fillable = [
        'producto_id',
        'stock_actual',
        'stock_minimo',
        'estado',
    ];

    // Relación con productos
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
