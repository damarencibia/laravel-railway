<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KeyManager;
use App\Models\User;
class KeyManagerController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        $keyes = KeyManager::where('user_id', $userId)->get();

        $lastRecord = $keyes->last();

        if ($lastRecord && $lastRecord->estado == 0) {
            $user = auth()->user();
            $userInstance = User::find($user->id);
            $userInstance->status = 'suscriptor';
            $userInstance->save();
        }

        return response()->json($keyes);
    }



    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'No se encontró un usuario autenticado'], 401);
        }

        $key = KeyManager::create([
            'serial' => $request->input('serial'),
            'key' => $request->input('key'),
            'fecha_compra' => $request->input('fecha_compra'),
            'fecha_expiracion' => $request->input('fecha_expiracion'),
            'estado' => $request->input('estado'),
            'user_id' => $user->id,
        ]);

        // Cambiar el status del usuario a "comprador"
        $user->status = 'comprador';
        $user->save();

        return response()->json($key, 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the Piece instance by ID
        $key = KeyManager::findOrFail($id);

        // Update the attributes of the model
        $key->fill($request->only([
            'serial',
            'key',
            'fecha_compra',
            'fecha_expiracion',
            'estado',
        ]));

        // Save the model
        if ($key->save()) {
            return response()->json([
                'data' => $key,
                'message' => 'Licence updated successfully.',
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update the licence.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
