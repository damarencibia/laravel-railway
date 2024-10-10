<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPieza extends Model
{
    protected $table = 'historial_piezas';

    protected $fillable = [
        'pieza_id',
        'operacion',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'detalles'
    ];
}
