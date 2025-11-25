<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;
      protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'sku',
        'precio',
        'activo',
        'categoria_id',
    ];

    // Relación: un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
