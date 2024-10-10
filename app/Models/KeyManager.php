<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KeyManager extends Model
{
    use HasFactory;

    protected $table = 'key_manager';

    protected $fillable = [
        'serial',
        'key',
        'estado', // cantidad de días que el usuario pone
        'user_id'
    ];

    public function setFechaExpiracion()
    {
        $fecha_compra = Carbon::parse($this->fecha_compra)->startOfDay();
        $this->fecha_expiracion = $fecha_compra->addDays(intval($this->estado))->startOfDay();
    }

    public function getDiasVigentes()
    {
        $fecha_actual = Carbon::now()->startOfDay();
        $fecha_expiracion = Carbon::parse($this->fecha_expiracion)->startOfDay();

        $dias_pasados = $fecha_actual->diffInDays($fecha_expiracion);

        return $dias_pasados;
    }

    public function verificarEstado()
    {
        $dias_vigentes = $this->getDiasVigentes();

        if ($dias_vigentes <= 0) {
            // la licencia ha expirado
            $this->estado = 0;
        } else {
            $this->estado = $dias_vigentes;
        }

        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($licencia) {
            $licencia->setFechaExpiracion();
        });

        static::retrieved(function ($licencia) {
            $licencia->verificarEstado();
        });
    }
}
