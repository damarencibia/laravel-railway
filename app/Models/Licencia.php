<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Importar Carbon para trabajar con fechas

class Licencia extends Model
{
    use HasFactory;

    protected $table = 'licencias';

    protected $fillable = [
        'id_licencia',
        'programa',
        'fecha_compra',
        'fecha_expiracion',
        'estado',
        'detalles'
    ];

    public function setFechaExpiracion()
    {
        $fecha_compra = Carbon::parse($this->fecha_compra)->startOfDay();
        $this->fecha_expiracion = $fecha_compra->addDays(intval($this->estado))->startOfDay();
    }

    public function verifyEstado()
    {
        $fecha_actual = Carbon::now()->startOfDay();
        $fecha_compra = Carbon::parse($this->fecha_compra)->startOfDay();

        $dias_pasados = $fecha_compra->diffInDays($fecha_actual);

        $this->estado -= $dias_pasados;

        if ($this->estado < 0) {
            $this->estado = 0;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($licencia) {
            $licencia->setFechaExpiracion();

        });

        static::updating(function ($licencia) {
            $licencia->setFechaExpiracion();

        });

        static::retrieved(function ($licencia) {
            $licencia->verifyEstado();
        });
    }

    protected static function booted()
    {
        static::created(function ($licencia) {
            HistorialLicencia::create([
                'licencia_id' => $licencia->id_licencia,
                'operacion' => 'crear',
            ]);
        });

        static::deleted(function ($licencia) {
            HistorialLicencia::create([
                'licencia_id' => $licencia->id_licencia,
                'operacion' => 'eliminar',
            ]);
        });

        static::updated(function ($licencia) {
            $dirty = $licencia->getDirty(); // Obtiene los campos que han cambiado

            // Filtrar out el campo updated_at si está presente
            unset($dirty['updated_at']);

            foreach ($dirty as $campo => $nuevoValor) {
                $valorAnterior = $licencia->getOriginal($campo); // Obtiene el valor original del campo antes de la actualización
                HistorialLicencia::create([
                    'licencia_id' => $licencia->id_licencia,
                    'operacion' => 'modificar',
                    'campo_modificado' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $nuevoValor,
                ]);
            }
        });
    }
}
