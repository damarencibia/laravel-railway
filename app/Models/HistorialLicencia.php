<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialLicencia extends Model
{
    protected $table = 'historial_licencias';

    protected $fillable = [
        'licencia_id',
        'operacion',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'detalles'
    ];
}
