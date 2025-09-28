<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Registro de usuáriopublic function register(Request $request)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
            'is_admin' => 'nullable|boolean',
            'opm_id' => 'nullable|integer',
            'posto_graduacoes_id' => 'nullable|integer',
           
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'apelido' => $request->apelido,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin ?? 0,
            'opm_id' => $request->opm_id ?? null,
            'posto_graduacoes_id' => $request->posto_graduacoes_id ?? null,
           
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    


    // Mostrar todos os usuários
    public function index()
    {
        $users = User::where('is_obsolete', false)
            ->select('id', 'name', 'email', 'apelido', 'opm_id', 'posto_graduacoes_id')
            ->with([
                'opm:id,bpm',
                'postoGraduacao:id,nome',
            ])
            ->get();
    
        return response()->json($users);
    }
    
    




    // Mostrar um usuário específico pelo ID
    public function show($id)
{
    $user = User::select('id', 'name', 'email', 'apelido', 'opm_id', 'posto_graduacoes_id')
        ->with([
            'opm:id,bpm',
            'postoGraduacao:id,nome',
            'cautelas' => function ($query) {
                $query->with([
                    'admin:id,name',
                    'itens.arma:id,modelo_id,carregador_id,municao_id',
                    'itens.arma.modelo:id,name,calibre_id,numero_serie',
                    'itens.arma.modelo.calibre:id,nome',
                    'itens.arma.carregador:id,capacidade,quantidade',
                    'itens.arma.municao:id,tipo,calibre_id,quantidade',
                    'itens.arma.municao.calibre:id,nome',
                    'itens.colete:id,tipo,num_serie,quantidade',
                    'itens.espada:id,tipo,num_serie,quantidade',
                    'itens.algema:id,tipo,num_serie,quantidade',
                ]);
            }
        ])
        ->find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    return response()->json($user);
}


    // Atualizar informações de um usuário
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'apelido' => 'nullable|string|max:255', // Validação do apelido
            'password' => 'nullable|min:6',
            'is_admin' => 'nullable|boolean',
            'opm_id' => 'nullable|integer',
            'posto_graduacoes_id' => 'nullable|integer',
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->apelido = $request->apelido ?? $user->apelido; // Atualiza o apelido
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->is_admin = $request->is_admin ?? $user->is_admin;
        $user->opm_id = $request->opm_id ?? $user->opm_id;
        $user->posto_graduacoes_id = $request->posto_graduacoes_id ?? $user->posto_graduacoes_id;
    
        $user->save();
    
        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
            'user' => $user,
        ]);
    }
    
    

    // Deletar um usuário pelo ID
    public function destroy($id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Você não é admin para executar essa ação'], 403);
        }
    
        $user = User::findOrFail($id);
    
        // Marca o usuário como obsoleto ao invés de excluir
        $user->is_obsolete = true;
        $user->save();
    
        return response()->json(['message' => 'Usuário marcado como obsoleto com sucesso']);
    }
    
    public function markAsObsolete($id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Você não tem permissão para executar essa ação.'], 403);
        }
    
        $user = User::findOrFail($id);
        $user->is_obsolete = true;
        $user->save();
    
        return response()->json(['message' => 'Usuário marcado como obsoleto com sucesso.']);
    }
    

    
}