<?php

namespace App\Http\Controllers;

use App\Models\Calibre;
use Illuminate\Http\Request;

class CalibreController extends Controller
{
    // Lista todos os calibres
    public function index()
    {
        $calibres = Calibre::all();
        return response()->json($calibres);
    }

    // Cria um novo calibre
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'medidas' => 'required|string|max:255',
        ]);

        // Criação do calibre
        $calibre = Calibre::create($validated);

        return response()->json([
            'message' => 'Calibre criado com sucesso!',
            'data' => $calibre
        ], 201);
    }

    // Exibe um calibre específico
    public function show(Calibre $calibre)
    {
        return response()->json($calibre);
    }

    // Atualiza um calibre
    public function update(Request $request, Calibre $calibre)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'medidas' => 'required|string|max:255',
        ]);

        // Atualização do calibre
        $calibre->update($validated);

        return response()->json([
            'message' => 'Calibre atualizado com sucesso!',
            'data' => $calibre
        ]);
    }

    // Remove um calibre
    public function destroy(Calibre $calibre)
    {
        $calibre->delete();

        return response()->json([
            'message' => 'Calibre deletado com sucesso!'
        ], 204);
    }
}
