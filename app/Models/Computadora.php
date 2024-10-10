<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Computadora extends Model
{
    use HasFactory;

    protected $table = 'computadoras';
    protected $fillable = [
        'nro_expediente',
        'departamento',
        'usuario',
        'cpu_torre',
        'monitor',
        'mouse',
        'teclado',
        'ups',
        'bocinas',
        'placa_base',
        'ram',
        'lector_cd',
        'disco_duro',
        'local_climatizado',
        'local_sd_mcmpt',
        'so',
        'responsable',
        'jefe_seg_inf'
    ];

    public function piezas()
    {
        return $this->hasThrough(
            \App\Models\Pieza::class,
            'nro_inventario', // Foreign key on Pieza table...
        );
    }

    public function componentes()
    {
        return $this->hasThrough(
            \App\Models\Componente::class,
            'nro_serie', // Foreign key on Componente table...
        );
    }

    protected static function boot()
    {
        parent::boot();

        /*METODO CREATING*/
        static::creating(function ($computadora) {
            DB::transaction(function () use ($computadora) {
                // Lista de piezas asociadas a una computadora
                $piezasAsociadas = [
                    'cpu_torre',
                    'monitor',
                    'mouse',
                    'teclado',
                    'ups',
                    'bocinas',
                ];

                // Crear un array para almacenar los números de inventario de las piezas
                $nroInventarios = [];

                foreach ($piezasAsociadas as $pieza) {
                    // Obtener el nro_inventario de la pieza asociada
                    $nro_inventario = $computadora->$pieza;

                    // Agregar el nro_inventario al array de nroInventarios
                    $nroInventarios[] = $nro_inventario;

                    // Verificar si la pieza está disponible
                    $piezaDisponible = DB::table('piezas')
                        ->where('nro_inventario', $nro_inventario)
                        ->value('disponible');

                    if (!$piezaDisponible) {
                        throw new \Exception("La pieza {$pieza} ya está siendo utilizada.");
                    }
                }

                // Comprobar si hay elementos duplicados en el array de nroInventarios
                if (count(array_unique($nroInventarios)) != count($nroInventarios)) {
                    throw new \Exception("Existen piezas duplicadas en la computadora.");
                }

                // Una vez que todas las piezas están disponibles y son únicas, actualizar su estado a no disponible
                foreach ($piezasAsociadas as $pieza) {
                    $nro_inventario = $computadora->$pieza;
                    DB::table('piezas')
                        ->where('nro_inventario', $nro_inventario)
                        ->update(['disponible' => 0]);
                }
                /*------------------Lógica para la creación de los componentes-----------*/
                // Lista de piezas asociadas a una computadora
                $componentesAsociados = [
                    'placa_base',
                    'ram',
                    'lector_cd',
                    'disco_duro',
                ];

                // Crear un array para almacenar los números de serie de las piezas
                $nroSeries = [];

                foreach ($componentesAsociados as $componente) {
                    // Obtener el nro_inventario de la pieza asociada
                    $nro_serie = $computadora->$componente;

                    // Agregar el nro_inventario al array de nroInventarios
                    $nroSeries[] = $nro_serie;

                    // Verificar si la pieza está disponible
                    $componenteDisponible = DB::table('componentes')
                        ->where('nro_serie', $nro_serie)
                        ->value('disponible');

                    if (!$componenteDisponible) {
                        throw new \Exception("El componente {$componente} ya está siendo utilizada.");
                    }
                }

                // Comprobar si hay elementos duplicados en el array de nroInventarios
                if (count(array_unique($nroSeries)) != count($nroSeries)) {
                    throw new \Exception("Existen componentes duplicados en la computadora.");
                }

                // Una vez que todas las piezas están disponibles y son únicas, actualizar su estado a no disponible
                foreach ($componentesAsociados as $componente) {
                    $nro_serie = $computadora->$componente;
                    DB::table('componentes')
                        ->where('nro_serie', $nro_serie)
                        ->update(['disponible' => 0]);
                }
            }, 5); // El segundo parámetro es el número de reintentos en caso de fallo de la transacción
        });

        /*METODO UPDATING*/
        static::updating(function ($computadora) {
            DB::transaction(function () use ($computadora) {
                // Lista de piezas asociadas a una computadora
                $piezasAsociadas = [ //Lógica para la edición de las PIEZAS
                    'cpu_torre',
                    'monitor',
                    'mouse',
                    'teclado',
                    'ups',
                    'bocinas',
                ];

                // Inicializar arrays para almacenar los números de inventario de las piezas entrantes y salientes
                $entrantes = [];
                $salientes = [];

                // Recopilar los números de inventario de las piezas entrantes (nuevos valores)
                foreach ($piezasAsociadas as $pieza) {
                    $entrantes[$pieza] = $computadora->$pieza;
                }

                // Recopilar los números de inventario de las piezas salientes (valores originales)
                foreach ($piezasAsociadas as $pieza) {
                    $salientes[$pieza] = $computadora->getOriginal($pieza);
                }

                // Identificar piezas agregadas o eliminadas
                $agregadas = array_diff_assoc($entrantes, $salientes);
                $eliminadas = array_diff_assoc($salientes, $entrantes);

                // Procesar piezas agregadas
                foreach ($agregadas as $pieza => $nro_inventario) {
                    if (!empty($nro_inventario)) {
                        // Verificar disponibilidad de la pieza agregada
                        $piezaDisponible = DB::table('piezas')
                            ->where('nro_inventario', $nro_inventario)
                            ->value('disponible');

                        if (!$piezaDisponible) {
                            throw new \Exception("La pieza {$pieza} ya está siendo utilizada.");
                        }

                        // Marcar la pieza agregada como no disponible
                        DB::table('piezas')
                            ->where('nro_inventario', $nro_inventario)
                            ->update(['disponible' => 0]);
                    }
                }

                // Procesar piezas eliminadas
                foreach ($eliminadas as $pieza => $nro_inventario) {
                    if (!empty($nro_inventario)) {
                        // Marcar la pieza eliminada como disponible
                        DB::table('piezas')
                            ->where('nro_inventario', $nro_inventario)
                            ->update(['disponible' => 1]);
                    }
                }

                // Verificar duplicados entre las piezas entrantes
                if (count($entrantes) != count(array_unique($entrantes))) {
                    throw new \Exception("Existen piezas duplicadas en la computadora.");
                }

                /*-----------------Lógica para la edición de los componentes-----------*/
                // Lista de componentes asociadas a una computadora
                $componentesAsociados = [
                    'placa_base',
                    'ram',
                    'lector_cd',
                    'disco_duro',
                ];

                // Inicializar arrays para almacenar los números de serie de los componentes entrantes y salientes
                $componentesEntrantes = [];
                $componentesSalientes = [];

                // Recopilar los números de serie de los componentes entrantes (nuevos valores)
                foreach ($componentesAsociados as $componente) {
                    $componentesEntrantes[$componente] = $computadora->$componente;
                }

                // Recopilar los números de serie de los componentes salientes (valores originales)
                foreach ($componentesAsociados as $componente) {
                    $componentesSalientes[$componente] = $computadora->getOriginal($componente);
                }

                // Identificar componentes agregados o eliminados
                $componentesAgregados = array_diff_assoc($componentesEntrantes, $componentesSalientes);
                $componentesEliminados = array_diff_assoc($componentesSalientes, $componentesEntrantes);

                // Procesar componentes agregados
                foreach ($componentesAgregados as $componente => $nro_serie) {
                    if (!empty($nro_serie)) {
                        // Verificar disponibilidad del componente agregado
                        $componenteDisponible = DB::table('componentes')
                            ->where('nro_serie', $nro_serie)
                            ->value('disponible');

                        if (!$componenteDisponible) {
                            throw new \Exception("el componente {$componente} ya está siendo utilizado.");
                        }

                        // Marcar el componente agregado como no disponible
                        DB::table('componentes')
                            ->where('nro_serie', $nro_serie)
                            ->update(['disponible' => 0]);
                    }
                }

                // Procesar componentes Eliminados
                foreach ($componentesEliminados as $componente => $nro_serie) {
                    if (!empty($nro_serie)) {
                        // Marcar la pieza eliminada como disponible
                        DB::table('componentes')
                            ->where('nro_serie', $nro_serie)
                            ->update(['disponible' => 1]);
                    }
                }

                // Verificar dupliados entre los componentes Entrantes
                if (count($componentesEntrantes) != count(array_unique($componentesEntrantes))) {
                    throw new \Exception("Existen componentes duplicados en la computadora.");
                }
            }, 5); // El segundo parámetro es el número de reintentos en caso de fallo de la transacción
        });

        /*METODO DELETING*/
        static::deleting(function ($computadora) {
            // Lista de piezas asociadas a una computadora
            $piezasAsociadas = [
                'cpu_torre',
                'monitor',
                'mouse',
                'teclado',
                'ups',
                'bocinas',
            ];

            foreach ($piezasAsociadas as $pieza) {
                // Obtener el nro_inventario de la pieza asociada
                $nro_inventario = $computadora->$pieza;

                // Restablecer el estado de la pieza a disponible
                DB::table('piezas')
                    ->where('nro_inventario', $nro_inventario)
                    ->update(['disponible' => 1]);
            }
            /*Lógica para la eliminación de los componentes-----------*/
            // Lista de componentes asociadas a una computadora
            $componentesAsociados = [
                'placa_base',
                'ram',
                'lector_cd',
                'disco_duro',
            ];

            // Recuperar los números de serie de los componentes para eliminar
            $componentesAEliminar = [];
            foreach ($componentesAsociados as $componente) {
                $componentesAEliminar[$componente] = $computadora->$componente;
            }

            // Procesar eliminación de componentes
            foreach ($componentesAEliminar as $componente => $nro_serie) {
                if (!empty($nro_serie)) {
                    // Marcar el componente como disponible
                    DB::table('componentes')
                        ->where('nro_serie', $nro_serie)
                        ->update(['disponible' => 1]);
                }
            }
        });
    }

    protected static function booted()
    {
        static::created(function ($computadora) {
            HistorialComputadora::create([
                'computadora_id' => $computadora->nro_expediente,
                'operacion' => 'crear',
            ]);
        });

        static::deleted(function ($computadora) {
            HistorialComputadora::create([
                'computadora_id' => $computadora->nro_expediente,
                'operacion' => 'eliminar',
            ]);
        });

        static::updated(function ($computadora) {
            $dirty = $computadora->getDirty(); // Obtiene los campos que han cambiado

            // Filtrar out el campo updated_at si está presente
            unset($dirty['updated_at']);

            foreach ($dirty as $campo => $nuevoValor) {
                $valorAnterior = $computadora->getOriginal($campo); // Obtiene el valor original del campo antes de la actualización
                HistorialComputadora::create([
                    'computadora_id' => $computadora->nro_expediente,
                    'operacion' => 'modificar',
                    'campo_modificado' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $nuevoValor,
                ]);
            }
        });
    }
}
