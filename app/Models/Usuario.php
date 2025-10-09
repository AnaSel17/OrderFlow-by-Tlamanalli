<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable // <-- CAMBIO AQUÍ
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'usuarios';

    /**
     * La llave primaria asociada con la tabla.
     */
    protected $primaryKey = 'user_id';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'id_rol',
        'user_nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'telefono',
        'user_estado',
        'last_login_at',
    ];

    /**
     * Los atributos que deben ocultarse.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación: Un Usuario pertenece a un Rol.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}