<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    use HasFactory;

    protected $table = 'componentes';

    // protected $primaryKey = 'nro_serie';

    public $incrementing = false;

    public $timestamps = true; // Mantén los timestamps activados

    protected $fillable = [
        'nro_serie',
        'marca',
        'tipo_componente',
        'user_id',
        // 'disponible'
    ];

    protected $casts = [
        'tipo_componente' => 'string',
    ];

    protected static function booted()
    {
        static::created(function ($componente) {
            HistorialComponente::create([
                'componente_id' => $componente->nro_serie,
                'operacion' => 'crear',
            ]);
        });

        static::deleted(function ($componente) {
            HistorialComponente::create([
                'componente_id' => $componente->nro_serie,
                'operacion' => 'eliminar',
            ]);
        });

        static::updated(function ($componente) {
            $dirty = $componente->getDirty(); // Obtiene los campos que han cambiado

            // Filtrar out el campo updated_at si está presente
            unset($dirty['updated_at']);

            foreach ($dirty as $campo => $nuevoValor) {
                $valorAnterior = $componente->getOriginal($campo); // Obtiene el valor original del campo antes de la actualización
                HistorialComponente::create([
                    'componente_id' => $componente->nro_serie,
                    'operacion' => 'modificar',
                    'campo_modificado' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $nuevoValor,
                ]);
            }
        });
    }
}
