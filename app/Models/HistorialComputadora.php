<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialComputadora extends Model
{
    protected $table = 'historial_computadoras';

    protected $fillable = [
        'computadora_id',
        'operacion',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'detalles'
    ];
}
