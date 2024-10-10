<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HistorialComponente extends Model
{
    protected $table = 'historial_componentes';

    protected $fillable = [
        'componente_id',
        'operacion',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'detalles'
    ];
}
