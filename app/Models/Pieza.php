<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pieza extends Model
{
    use HasFactory;

    protected $table = 'piezas';

    public $incrementing = false;
    
    public $timestamps = true; // Mantén los timestamps activados

    protected $fillable = [
        'nro_inventario',
        'marca',
        'color',
        'tipo_de_pieza',
        // 'disponible'
    ];

    protected $casts = [
        'tipo_de_pieza' => 'string',
    ];

    protected static function booted()
    {
        static::created(function ($pieza) {
            HistorialPieza::create([
                'pieza_id' => $pieza->nro_inventario,
                'operacion' => 'crear',
            ]);
        });

        static::deleted(function ($pieza) {
            HistorialPieza::create([
                'pieza_id' => $pieza->nro_inventario,
                'operacion' => 'eliminar',
            ]);
        });

        static::updated(function ($pieza) {
            $dirty = $pieza->getDirty(); // Obtiene los campos que han cambiado

            // Filtrar out el campo updated_at si está presente
            unset($dirty['updated_at']);

            foreach ($dirty as $campo => $nuevoValor) {
                $valorAnterior = $pieza->getOriginal($campo); // Obtiene el valor original del campo antes de la actualización
                HistorialPieza::create([
                    'pieza_id' => $pieza->nro_inventario,
                    'operacion' => 'modificar',
                    'campo_modificado' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $nuevoValor,
                ]);
            }
        });
    }

}
