<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     * Con esto corregimos el problema de pluralización automática (rols -> roles).
     *
     * @var string
     */
    protected $table = 'roles'; // <-- ¡ESTA LÍNEA ES LA SOLUCIÓN!

    protected $primaryKey = 'id_rol';

    // Un Rol "pertenece" a muchos Usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }
}