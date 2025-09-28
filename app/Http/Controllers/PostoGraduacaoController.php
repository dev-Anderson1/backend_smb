<?php

namespace App\Http\Controllers;

use App\Models\Posto_Graduacao;
use Illuminate\Http\Request;

class PostoGraduacaoController extends Controller
{
    // Lista todos os postos de graduação
    public function index()
    {
        return response()->json(Posto_Graduacao::all());
    }

    // Cria um novo posto de graduação
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $posto = Posto_Graduacao::create($validated);

        return response()->json([
            'message' => 'Posto de Graduação criado com sucesso!',
            'data' => $posto
        ], 201);
    }

    // Mostra um posto de graduação específico
    public function show(Posto_Graduacao $postoGraduacao)
    {
        return response()->json($postoGraduacao);
    }

    // Atualiza um posto de graduação
    public function update(Request $request, Posto_Graduacao $postoGraduacao)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $postoGraduacao->update($validated);

        return response()->json([
            'message' => 'Posto de Graduação atualizado com sucesso!',
            'data' => $postoGraduacao
        ]);
    }

    // Remove um posto de graduação
    public function destroy(Posto_Graduacao $postoGraduacao)
    {
        $postoGraduacao->delete();

        return response()->json([
            'message' => 'Posto de Graduação deletado com sucesso!'
        ], 204);
    }
}
