<?php

namespace App\Http\Controllers;

use App\Models\Municao;
use Illuminate\Http\Request;

class MunicaoController extends Controller
{
    // Lista todas as munições
    public function index()
    {
        $municoes = Municao::all();
        return response()->json($municoes);
    }

    // Cria uma nova munição
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'calibre_id' => 'required|exists:calibres,id',
            'arma_id' => 'required|exists:armas,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        // Criação da munição
        $municao = Municao::create($validated);

        return response()->json([
            'message' => 'Munição criada com sucesso!',
            'data' => $municao
        ], 201);
    }

    // Exibe uma munição específica
    public function show(Municao $municao)
    {
        return response()->json($municao);
    }

    // Atualiza uma munição
    public function update(Request $request, Municao $municao)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'calibre_id' => 'required|exists:calibres,id',
            'arma_id' => 'required|exists:armas,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        // Atualização da munição
        $municao->update($validated);

        return response()->json([
            'message' => 'Munição atualizada com sucesso!',
            'data' => $municao
        ]);
    }

    // Remove uma munição
    public function destroy(Municao $municao)
    {
        $municao->delete();

        return response()->json([
            'message' => 'Munição deletada com sucesso!'
        ], 204);
    }
}
