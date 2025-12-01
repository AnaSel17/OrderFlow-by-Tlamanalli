<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activa',
        'hora_apertura',
        'hora_cierre',
        'dias_activos',
        'color_hex',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'dias_activos' => 'array',
        // estos dos ayudan a que las horas se manejen como tiempo
        
    ];

    /**
     * Relación: una zona tiene muchas mesas
     */
    public function mesas()
    {
        return $this->hasMany(Mesa::class, 'zona_id');
    }

    /**
     * Verifica si la zona está abierta según hora y día actual
     */
    public function estaAbierta(): bool
    {
        if (!$this->activa || !$this->hora_apertura || !$this->hora_cierre) {
            return false;
        }

        // Hora actual sin fecha
        $ahora = Carbon::now('America/Mexico_City')->format('H:i:s');

        // Día actual abreviado (Lun, Mar, Mie, etc.)
        $dia = ucfirst(Carbon::now('America/Mexico_City')->locale('es')->dayName);

        // Validar día activo
        if (is_array($this->dias_activos) && !in_array($dia, $this->dias_activos)) {
            return false;
        }

        // 🔧 Importante: convertir a solo hora (sin fecha)
        $abre   = Carbon::parse($this->hora_apertura)->format('H:i:s');
        $cierra = Carbon::parse($this->hora_cierre)->format('H:i:s');

        logger("[$this->nombre] ahora=$ahora, abre=$abre, cierra=$cierra, dia=$dia");


        // Comparar correctamente solo horas
        return $ahora >= $abre && $ahora <= $cierra;
    }


    /**
     * Estado formateado para vistas
     */
    public function getEstaAbiertaAttribute(): bool
{
    return $this->estaAbierta();
}

    /**
     * Validar antes de eliminar
     */
    protected static function booted()
    {
        static::deleting(function ($zona) {
            if ($zona->mesas()->exists()) {
                throw new \Exception('No se puede eliminar la zona porque tiene mesas asignadas. Reasigna o elimina esas mesas primero.');
            }
        });
    }
}
