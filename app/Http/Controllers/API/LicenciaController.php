<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Licencia;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class LicenciaController extends Controller
{
    public function index()
    {
        $licencias = Licencia::all();

        return response()->json($licencias);
    }

    public function show($id)
    {
        $licencia = Licencia::findOrFail($id);

        // Actualiza el estado de la licencia
        $licencia->verifyEstado();

        return response()->json($licencia);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $licencia = Licencia::create($request->all());

            DB::commit();

            return response()->json(['message' => 'Licencia creado exitosamente', 'data' => $licencia], 201);

        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->getCode() === '23000') {
                // Manejo de violación de integridad (duplicidad)
                return response()->json([
                    'error' => 'Error al registrar la licencia',
                    'message' => 'Ya existe un licencia con ese ID.',
                    'details' => 'Por favor, verifique el ID y asegúrese de que sea único.'
                ], 422); // Código HTTP 422 Unprocessable Entity
            }

            // Para otros tipos de errores de QueryException
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un problema al procesar su solicitud.'
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            // Find the component instance by id
            $licencia = Licencia::findOrFail($id);

            // Update the attributes of the model
            $licencia->fill($request->only([
                'id_licencia',
                'programa',
                'fecha_compra',
                'fecha_expiracion',
                'estado',
                'detalles'
            ]));

            // save the model
            if ($licencia->save()) {
                DB::commit();
                return response()->json([
                    'data' => $licencia,
                    'message' => 'Licencia actualizada exitosamente.'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'error' => true,
                    'message' => 'Falló la actualización de la Licencia.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollback();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Licencia no encontrada.'
                ], 404);
            }

            // Specific handling for integrity duplicity violations
            if($e->getCode() === '23000') {
                return response()->json([
                    'error' => true,
                    'message' => 'Ya existe un Licencia con ese ID.',
                    'details' => 'Por favor, verifique el ID y asegúrese de que sea único.'
                ],422); //Unproccesable Entity
            }

            // Para otros tipos de errores
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al procesar su solicitud.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $licencia = Licencia::findOrFail($id);

            if ($licencia->delete()) {
                DB::commit();

                return response()->json([
                    'data' => $licencia,
                    'message' => 'Licencia eliminada exitosamente.'
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'message' => 'Error al eliminar la Licencia.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Licencia no encontrada.'
                ], 404);
            }

            // Para otros tipos de errores
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al procesar su solicitud.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function updateEstado(Request $request, $id)
    {
        // Find the Licencia instance by ID
        $licencia = Licencia::findOrFail($id);

        // Call the verifyEstado function
        $licencia->verifyEstado();

        // Return a response
        return response()->json([
            'data' => $licencia,
            'message' => 'Estado updated successfully.',
        ]);
    }

}
