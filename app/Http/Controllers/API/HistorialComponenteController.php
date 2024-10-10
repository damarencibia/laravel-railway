<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HistorialComponente;
use Illuminate\Http\Request;

class HistorialComponenteController extends Controller
{
    /**
     * Display a listing of the historial components.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historiales = HistorialComponente::all();

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
        $historial = HistorialComponente::find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial component not found'], 404);
        }

        return response()->json($historial);
    }
}
