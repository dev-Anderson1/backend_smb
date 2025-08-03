<?php

namespace App\Http\Controllers;

use App\Models\Arma;
use App\Models\Carregador;
use Illuminate\Http\Request;

class CarregadorController extends Controller
{
    // Método para listar todos os carregadores
    public function index()
    {
        $carregadores = Carregador::all();
        return response()->json($carregadores);
    }

    // Método para armazenar um novo carregador
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validated = $request->validate([
            'capacidade' => 'required|integer|min:1',
            'quantidade' => 'required|integer|min:1',
            'arma_id' => 'nullable|exists:armas,id', // Verificando se o arma_id existe na tabela armas
        ]);

        // Criação de um novo carregador
        $carregador = Carregador::create($validated);

        return response()->json([
            'message' => 'Carregador criado com sucesso!',
            'data' => $carregador
        ], 201);
    }

    // Método para exibir um carregador específico
    public function show(Carregador $carregador)
    {
        return response()->json($carregador);
    }

    // Método para atualizar um carregador existente
    public function update(Request $request, Carregador $carregador)
    {
        // Validação dos dados recebidos
        $validated = $request->validate([
            'capacidade' => 'required|integer|min:1',
            'quantidade' => 'required|integer|min:1',
            'arma_id' => 'nullable|exists:armas,id', // Verificando se o arma_id existe na tabela armas
        ]);

        // Atualizando o carregador
        $carregador->update($validated);

        return response()->json([
            'message' => 'Carregador atualizado com sucesso!',
            'data' => $carregador
        ]);
    }

    // Método para excluir um carregador
    public function destroy(Carregador $carregador)
    {
        $carregador->delete();

        return response()->json([
            'message' => 'Carregador deletado com sucesso!'
        ], 204);
    }


 public function emprestarCarregador(Request $request, $modeloId)
{
    // Buscar uma arma que tenha esse modelo
    $arma = Arma::where('modelo_id', $modeloId)->firstOrFail();

    // Buscar o carregador vinculado à arma
    $carregador = $arma->carregador; // Usa a relação definida no model Arma

    if (!$carregador) {
        return response()->json(['success' => false, 'mensagem' => 'Nenhum carregador vinculado a esta arma.']);
    }

    if ($carregador->quantidade > 0) {
        $carregador->quantidade -= 1;
        $carregador->save();

        return response()->json(['success' => true, 'mensagem' => 'Carregador emprestado com sucesso!']);
    } else {
        return response()->json(['success' => false, 'mensagem' => 'Nenhum carregador disponível para esta arma.']);
    }
}


public function devolverCarregador(Request $request, $armaId)
{
    // Buscar a arma pelo ID
    $arma = Arma::findOrFail($armaId);

    // Buscar o carregador vinculado à arma
    $carregador = $arma->carregador;

    if (!$carregador) {
        return response()->json(['success' => false, 'mensagem' => 'Nenhum carregador vinculado a esta arma.']);
    }

    // Aqui, você pode definir um limite máximo se quiser (por exemplo: quantidade total inicial)
    // ou apenas permitir devoluções indefinidas
    $carregador->quantidade += 1;
    $carregador->save();

    return response()->json(['success' => true, 'mensagem' => 'Carregador devolvido com sucesso!']);
}



// No ArmaController, ou outro controller dedicado
public function listarCarregadores()
{
    $carregadores = Carregador::all();
    return response()->json($carregadores);
}


}
