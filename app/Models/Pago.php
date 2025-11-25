<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    /** @use HasFactory<\Database\Factories\PagoFactory> */
    use HasFactory;

    protected $fillable = [
        'cuenta_id',
        'metodo',
        'monto',
        'referencia',
        'recibido_por',  
        'notas'
    ];

    public function cuenta()  { return $this->belongsTo(Cuenta::class); }
    public function usuario() { return $this->belongsTo(User::class); }
}
