<?php

namespace App\Http\Controllers;

use App\Models\Colete;
use Illuminate\Http\Request;

class ColeteController extends Controller
{
    // Lista todos os coletes
    public function index()
    {
        return response()->json(Colete::all());
    }

    // Cria um novo colete
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'num_serie' => 'required|string|max:255|unique:coletes',
            'quantidade' => 'required|integer|min:1',
        ]);

        $colete = Colete::create($validated);

        return response()->json([
            'message' => 'Colete criado com sucesso!',
            'data' => $colete
        ], 201);
    }

    // Mostra um colete específico
    public function show(Colete $colete)
    {
        return response()->json($colete);
    }

    // Atualiza um colete
    public function update(Request $request, Colete $colete)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'num_serie' => 'required|string|max:255|unique:coletes,num_serie,' . $colete->id,
            'quantidade' => 'required|integer|min:1',
        ]);

        $colete->update($validated);

        return response()->json([
            'message' => 'Colete atualizado com sucesso!',
            'data' => $colete
        ]);
    }

    // Remove um colete
    public function destroy(Colete $colete)
    {
        $colete->delete();

        return response()->json([
            'message' => 'Colete deletado com sucesso!'
        ], 204);
    }
}
