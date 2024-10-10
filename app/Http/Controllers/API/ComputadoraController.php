<?php

namespace App\Http\Controllers\API;

use App\Models\Computadora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ComputadoraController extends \Illuminate\Routing\Controller
{

    public function index()
    {
        $computadoras = Computadora::all();
        return response()->json($computadoras);
    }



    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $computadora = Computadora::create($request->all());

            DB::commit();

            return response()->json(['message' => 'Computadora creada exitosamente', 'data' => $computadora], 201);
        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->getCode() === '23000') {
                // Manejo de violación de integridad (duplicidad)
                return response()->json([
                    'error' => 'Error al registrar la computadora',
                    'message' => 'Ya existe una computadora con ese número de expediente.'
                ], 422); // Código HTTP 422 Unprocessable Entity
            }

            // Para otros tipos de errores de QueryException
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un problema al procesar su solicitud.'
            ], 500);
        }
    }


    public function show($id)
    {
        $computadora = Computadora::findOrFail($id);
        return response()->json($computadora);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            // Find the component instance by id
            $computadora = Computadora::findOrFail($id);

            // Update the attributes of the model
            $computadora->fill($request->only([
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
                'jefe_seg_inf',
            ]));

            // save the model
            if ($computadora->save()) {
                DB::commit();
                return response()->json([
                    'data' => $computadora,
                    'message' => 'computadora actualizada exitosamente.'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'error' => true,
                    'message' => 'Falló la actualización de la computadora.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollback();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'computadora no encontrada.'
                ], 404);
            }

            // Specific handling for integrity duplicity violations
            if ($e->getCode() === '23000') {
                return response()->json([
                    'error' => true,
                    'message' => 'Ya existe una computadora con ese número de expediente.',
                    'details' => 'Por favor, verifique el número de expediente y asegúrese de que sea único.'
                ], 422); //Unproccesable Entity
            }

            // Para otros tipos de errores
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al procesar su solicitud.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // $computadora->fill($request->only([
    // 'nro_expediente',
    // 'departamento',
    // 'usuario',
    // 'cpu_torre',
    // 'monitor',
    // 'mouse',
    // 'teclado',
    // 'ups',
    // 'bocinas',
    // 'placa_base',
    // 'ram',
    // 'lector_cd',
    // 'disco_duro',
    // 'local_climatizado',
    // 'local_sd_mcmpt',
    // 'so',
    // 'responsable',
    // 'jefe_seg_inf',
    // ]));

    public function destroy(string $id)
    {
        $computadora = Computadora::findOrFail($id);

        if ($computadora) {
            $resultado = $computadora->delete();
            if ($resultado) {
                return response()->json([
                    'data' => $computadora,
                    'message' => 'Computadora eliminada con éxito.',
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Error al eliminar la computadora.',
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No existe la computadora.',
            ]);
        }
    }

    public function getByDepartment($id1, $id2)
    {
        $computadoras = Computadora::where($id1, $id2)->get();
        return response()->json($computadoras);
    }
}
