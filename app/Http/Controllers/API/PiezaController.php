<?php

namespace App\Http\Controllers\API;

use App\Models\Pieza;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class PiezaController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $piezas = Pieza::all();
        return response()->json($piezas);
    }

    public function show($id)
    {
        $licencia = Pieza::findOrFail($id);
        return response()->json($licencia);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $piezas = Pieza::create($request->all());

            DB::commit();

            return response()->json(['message' => 'Pieza creada exitosamente', 'data' => $piezas], 201);

        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->getCode() === '23000') {
                // Manejo de violación de integridad (duplicidad)
                return response()->json([
                    'error' => 'Error al registrar el piezas',
                    'message' => 'Ya existe un pieza con ese número de inventario.',
                    'details' => 'Por favor, verifique el número de inventario y asegúrese de que sea único.'
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
            $pieza = Pieza::findOrFail($id);

            // Update the attributes of the model
            $pieza->fill($request->only([
                'nro_inventario',
                'marca',
                'color',
                'tipo_de_pieza',
                'disponible'
            ]));

            // save the model
            if ($pieza->save()) {
                DB::commit();
                return response()->json([
                    'data' => $pieza,
                    'message' => 'Pieza actualizada exitosamente.'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'error' => true,
                    'message' => 'Falló la actualización de la pieza.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollback();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Pieza no encontrada.'
                ], 404);
            }

            // Specific handling for integrity duplicity violations
            if($e->getCode() === '23000') {
                return response()->json([
                    'error' => true,
                    'message' => 'Ya existe una pieza con ese número de inventario.',
                    'details' => 'Por favor, verifique el número de inventario y asegúrese de que sea único.'
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

            $pieza = Pieza::findOrFail($id);

            if ($pieza->delete()) {
                DB::commit();

                return response()->json([
                    'data' => $pieza,
                    'message' => 'Pieza eliminada exitosamente.'
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'message' => 'Error al eliminar la pieza.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Pieza no encontrado.'
                ], 404);
            }

            // Manejo específico para violaciones de integridad por claves foráneas
            if ($e->getCode() === '23000') {
                return response()->json([
                    'error' => true,
                    'message' => 'No se puede eliminar esta pieza porque está asociada a una computadora.',
                    'details' => 'Para eliminarla, primero debe desvincularla de la computadora relacionada.'
                ], 409); // Conflict
            }

            // Para otros tipos de errores
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al procesar su solicitud.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Dentro de app/Http/Controllers/API/LicenciaController.php

    public function availablePieces()
    {
        $piezasDisponibles = Pieza::where('disponible', 1)->get();

        return response()->json($piezasDisponibles);
    }

    public function filterByCpu()
    {
        $comoponentesDisponibles = Pieza::where([
            ['tipo_de_pieza', 'cpu_torre'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterByMonitor()
    {
        $comoponentesDisponibles = Pieza::where([
            ['tipo_de_pieza', 'monitor'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterByMouse()
    {
        $comoponentesDisponibles = Pieza::where([
            ['tipo_de_pieza', 'mouse'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterByTeclado()
    {
        $comoponentesDisponibles = Pieza::where([
            ['tipo_de_pieza', 'teclado'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterByUps()
    {
        $comoponentesDisponibles = Pieza::where([
            ['tipo_de_pieza', 'ups'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterByBocinas()
    {
        $comoponentesDisponibles = Pieza::where([
            ['tipo_de_pieza', 'bocinas'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
}
