<?php

namespace App\Http\Controllers;

use App\Models\Algema;
use Illuminate\Http\Request;

class AlgemaController extends Controller
{
    public function index()
    {
        $algemas = Algema::all();
        return response()->json($algemas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string',
            'num_serie' => 'required|string|unique:algemas',
            'quantidade' => 'required|integer|min:1',
        ]);

        $algema = Algema::create($validated);

        return response()->json([
            'message' => 'Algema criada com sucesso!',
            'data' => $algema
        ], 201);
    }

    public function show(Algema $algema)
    {
        return response()->json($algema);
    }

    public function update(Request $request, Algema $algema)
    {
        $validated = $request->validate([
            'tipo' => 'required|string',
            'num_serie' => 'required|string|unique:algemas,num_serie,' . $algema->id,
            'quantidade' => 'required|integer|min:1',
        ]);

        $algema->update($validated);

        return response()->json([
            'message' => 'Algema atualizada com sucesso!',
            'data' => $algema
        ]);
    }

    public function destroy(Algema $algema)
    {
        $algema->delete();

        return response()->json([
            'message' => 'Algema deletada com sucesso!'
        ], 204);
    }
}
