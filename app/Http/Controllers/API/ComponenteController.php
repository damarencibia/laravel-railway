<?php

namespace App\Http\Controllers\API;

use App\Models\Componente;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ComponenteController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        $componentes = Componente::where('user_id', $userId)->get();

        return response()->json($componentes);
    }

    public function show($id)
    {
        $componente = Componente::findOrFail($id);
        return response()->json($componente);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'No se encontró un usuario autenticado'], 401);
            }

            $componente = Componente::create([
                'nro_serie' => $request->input('nro_serie'),
                'marca' => $request->input('marca'),
                'tipo_componente' => $request->input('tipo_componente'),
                'user_id' => $user->id,
                'disponible' => $request->input('disponible'),
            ]);

            DB::commit();

            return response()->json(['message' => 'Componente creado exitosamente', 'data' => $componente], 201);
        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->getCode() === '23000') {
                // Manejo de violación de integridad (duplicidad)
                return response()->json([
                    'error' => 'Error al registrar el componente',
                    'message' => 'Ya existe un componente con ese número de serie.',
                    'details' => 'Por favor, verifique el número de serie y asegúrese de que sea único.'
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
            $componente = Componente::findOrFail($id);

            // Update the attributes of the model
            $componente->fill($request->only([
                'nro_serie',
                'marca',
                'tipo_componente',
            ]));

            // save the model
            if ($componente->save()) {
                DB::commit();
                return response()->json([
                    'data' => $componente,
                    'message' => 'Componente actualizado exitosamente.'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'error' => true,
                    'message' => 'Falló la actualización del componente.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollback();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Componente no encontrado.'
                ], 404);
            }

            // Specific handling for integrity duplicity violations
            if ($e->getCode() === '23000') {
                return response()->json([
                    'error' => true,
                    'message' => 'Ya existe un componente con ese número de serie.',
                    'details' => 'Por favor, verifique el número de serie y asegúrese de que sea único.'
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

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $componente = Componente::findOrFail($id);

            if ($componente->delete()) {
                DB::commit();

                return response()->json([
                    'data' => $componente,
                    'message' => 'Componente eliminado exitosamente.'
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'message' => 'Error al eliminar el componente.'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Componente no encontrado.'
                ], 404);
            }

            // Manejo específico para violaciones de integridad por claves foráneas
            if ($e->getCode() === '23000') {
                return response()->json([
                    'error' => true,
                    'message' => 'No se puede eliminar este componente porque está asociado a una computadora.',
                    'details' => 'Para eliminar este componente, primero debe desvincularlo de la computadora relacionada.'
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

    public function availableComponents()
    {
        $comoponentesDisponibles = Componente::where('disponible', 1)->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterByPlacaBase()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'placa_base'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
    public function filterByRam()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'memoria_ram'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
    public function filterByDiscoDuro()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'disco_duro'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
    public function filterByLectorCd()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'lector_cd'],
            ['disponible', 1]

        ])->get();

        return response()->json($comoponentesDisponibles);
    }

    public function filterAllByPlacaBase()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'placa_base']

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
    public function filterAllByRam()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'memoria_ram']

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
    public function filterAllByDiscoDuro()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'disco_duro']

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
    public function filterAllByLectorCd()
    {
        $comoponentesDisponibles = Componente::where([
            ['tipo_componente', 'lector_cd']

        ])->get();

        return response()->json($comoponentesDisponibles);
    }
}
