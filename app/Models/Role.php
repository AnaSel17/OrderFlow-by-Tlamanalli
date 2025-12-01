<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     * Con esto corregimos el problema de pluralización automática (rols -> roles).
     *
     * @var string
     */
    protected $table = 'roles';      // nombre real de la tabla
    protected $primaryKey = 'id_rol'; //coincide con tu migración
    protected $fillable = ['nombre', 'descripcion','categoria','permisos'];

    protected $casts = [
        'permisos' => 'array'
    ];

    // Un Rol "pertenece" a muchos Usuarios
    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_rol');
    }
}