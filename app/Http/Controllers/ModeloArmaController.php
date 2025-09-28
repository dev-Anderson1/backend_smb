<?php

namespace App\Http\Controllers;

use App\Models\ModeloArma;
use Illuminate\Http\Request;

class ModeloArmaController extends Controller
{
    // Lista todos os modelos de armas
    public function index()
    {
        $modelos = ModeloArma::all();
        return response()->json($modelos);
    }

    // Cria um novo modelo de arma
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'calibre_id' => 'required|exists:calibres,id',
            'numero_serie' => 'required|string|unique:modelo_armas',
        ]);

        // Criação do modelo de arma
        $modelo = ModeloArma::create($validated);

        return response()->json([
            'message' => 'Modelo de arma criado com sucesso!',
            'data' => $modelo
        ], 201);
    }

    // Exibe um modelo de arma específico
    public function show(ModeloArma $modeloArma)
    {
        return response()->json($modeloArma);
    }

    // Atualiza um modelo de arma
    public function update(Request $request, ModeloArma $modeloArma)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'calibre_id' => 'required|exists:calibres,id',
            'numero_serie' => 'required|string|unique:modelo_armas,numero_serie,' . $modeloArma->id,
        ]);

        // Atualização do modelo de arma
        $modeloArma->update($validated);

        return response()->json([
            'message' => 'Modelo de arma atualizado com sucesso!',
            'data' => $modeloArma
        ]);
    }

    // Remove um modelo de arma
    public function destroy(ModeloArma $modeloArma)
    {
        $modeloArma->delete();

        return response()->json([
            'message' => 'Modelo de arma deletado com sucesso!'
        ], 204);
    }
}
