<?php

namespace App\Http\Controllers;

use App\Models\Espada;
use Illuminate\Http\Request;

class EspadaController extends Controller
{
    // Lista todas as espadas
    public function index()
    {
        return response()->json(Espada::all());
    }

    // Cria uma nova espada
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'num_serie' => 'required|string|max:255|unique:espadas',
            'quantidade' => 'required|integer|min:1',
        ]);

        $espada = Espada::create($validated);

        return response()->json([
            'message' => 'Espada criada com sucesso!',
            'data' => $espada
        ], 201);
    }

    // Mostra uma espada específica
    public function show(Espada $espada)
    {
        return response()->json($espada);
    }

    // Atualiza uma espada
    public function update(Request $request, Espada $espada)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'num_serie' => 'required|string|max:255|unique:espadas,num_serie,' . $espada->id,
            'quantidade' => 'required|integer|min:1',
        ]);

        $espada->update($validated);

        return response()->json([
            'message' => 'Espada atualizada com sucesso!',
            'data' => $espada
        ]);
    }

    // Remove uma espada
    public function destroy(Espada $espada)
    {
        $espada->delete();

        return response()->json([
            'message' => 'Espada deletada com sucesso!'
        ], 204);
    }
}
