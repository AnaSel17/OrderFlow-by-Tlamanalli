<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'telefono',
        'id_rol',
        'user_estado',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Define la relación de usuario con el rol (FK: id_rol).
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // =========================================================================
    // MÉTODOS REQUERIDOS POR ADMINLTE PARA EL SIDEBAR Y EL PERFIL
    // =========================================================================

    /**
     * Devuelve la URL de la imagen (avatar) del usuario para AdminLTE.
     * Por ahora, devuelve la imagen por defecto de Gravatar.
     */
    public function adminlte_image(): string
    {
        // Puedes usar Gravatar para obtener una imagen basada en el email
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon"; 
    }

    /**
     * Devuelve el texto del rol o un estado para mostrar en el sidebar.
     */
    public function adminlte_desc(): string
    {
        // Muestra el nombre del rol si la relación existe, si no, muestra el estado.
        return $this->rol ? $this->rol->nombre : ucfirst($this->user_estado);
    }

    /**
     * Devuelve la URL para el enlace del perfil en el sidebar de AdminLTE.
     */
    public function adminlte_profile_url(): string
    {
        // Cambia 'profile' por la ruta real de edición de perfil si la tienes
        return 'profile'; 
    }
}
