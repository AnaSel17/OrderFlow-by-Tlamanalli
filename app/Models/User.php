<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>    
     */
    protected $fillable = [
        'nombre',            // <--- CAMBIADO de 'name' a 'nombre' (Diccionario V1 )
        'apellido_paterno',  // <--- Añadido según Diccionario V1 
        'apellido_materno',  // <--- Añadido según Diccionario V1 
        'email',
        'password',
        'status',            // <--- Añadido el campo 'status' (Diccionario V1 )
        'rol_id',            // <--- Añadido el FK 'rol_id' (Diagrama ER [cite: 37])
        'telefono',          // <--- Añadido el campo 'telefono' (Diccionario V1 )
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
