<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HistorialLicencia;

class HistorialLicenciaController extends Controller
{
    public function index()
    {
        $historiales = HistorialLicencia::all();

        return response()->json($historiales);
    }

    /**
     * Show the specified historial component.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $historial = HistorialLicencia::find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial component not found'], 404);
        }

        return response()->json($historial);
    }
}
