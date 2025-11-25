<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

        // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'categorias';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    
    public function productos()
{
    return $this->hasMany(Producto::class, 'categoria_id');
}
}
