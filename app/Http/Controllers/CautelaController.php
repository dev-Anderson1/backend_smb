<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Cautela;
use App\Models\CautelaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CautelaController extends Controller
{

    // Listar todas as cautelas
    public function index()
    {
        $cautelas = Cautela::with([
            'admin:id,name',
            'usuario:id,name,email',
            'itens.arma:id,modelo_id,carregador_id,municao_id',
            'itens.arma.modelo:id,name,numero_serie,calibre_id', // <- numero_serie está aqui!
            'itens.arma.modelo.calibre:id,nome',
            'itens.arma.carregador:id,capacidade,quantidade',
            'itens.arma.municao:id,tipo,calibre_id,quantidade',
            'itens.arma.municao.calibre:id,nome',
        ])->get();
        
        

        return response()->json($cautelas);
    }

    // Mostrar uma cautela específica
    public function show($id)
    {
        $cautela = Cautela::with([
            'admin:id,name',
            'usuario:id,name,email',
            'itens.arma:id,name,numero_serie,quantidade',
            'itens.colet:id,tipo,num_serie,quantidade',
            'itens.espada:id,tipo,num_serie,quantidade',
            'itens.algema:id,tipo,num_serie,quantidade',
            'itens.material:id,tipo,num_serie,quantidade',
        ])->find($id);

        if (!$cautela) {
            return response()->json(['message' => 'Cautela não encontrada'], 404);
        }

        return response()->json($cautela);
    }

// Atualizar status ou informações
public function update(Request $request, $id)
{
    $cautela = Cautela::find($id);

    if (!$cautela) {
        return response()->json(['message' => 'Cautela não encontrada'], 404);
    }

    $cautela->update($request->only(['status'])); // ou outros campos que quiser permitir

    return response()->json(['message' => 'Cautela atualizada', 'cautela' => $cautela]);
}

// Deletar cautela
public function destroy($id)
{
    $cautela = Cautela::find($id);

    if (!$cautela) {
        return response()->json(['message' => 'Cautela não encontrada'], 404);
    }

    $cautela->delete();

    return response()->json(['message' => 'Cautela deletada com sucesso']);
}

   
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'admin_id' => 'required|exists:users,id',
    //         'user_id' => 'required|exists:users,id',
    //     ]);
    
    //     $cautela = Cautela::create([
    //         'admin_id' => $request->admin_id,
    //         'user_id' => $request->user_id,
    //         'status' => 'pendente',
    //     ]);
    
    //     $item = new CautelaItem([
    //         'cautela_id' => $cautela->id,
    //         'arma_id' => $request->arma_id,
    //         'colete_id' => $request->colete_id,
    //         'espada_id' => $request->espada_id,
    //         'algema_id' => $request->algema_id,
    //         'outros_materiais' => $request->outros_materiais,
    //         'quantidade' => $request->quantidade ?? 1,
    //     ]);
    //     $item->save();
    
    //     return response()->json([
    //         'success' => true,
    //         'cautela_id' => $cautela->id,
    //     ]);
    // }
    


   public function store(Request $request)
{
    Log::info('Store Cautela iniciado', ['request' => $request->all()]);

    $request->validate([
        'admin_id' => 'required|exists:users,id',
        'user_id' => 'required|exists:users,id',
        'itens.armas' => 'nullable|array',
        'itens.armas.*.arma_id' => 'required|exists:armas,id',
        'itens.armas.*.quantidade' => 'required|integer|min:1',

        'itens.espadas' => 'nullable|array',
        'itens.espadas.*.espada_id' => 'required|exists:espadas,id',
        'itens.espadas.*.quantidade' => 'required|integer|min:1',

        'itens.coletes' => 'nullable|array',
        'itens.coletes.*.colete_id' => 'required|exists:coletes,id',
        'itens.coletes.*.quantidade' => 'required|integer|min:1',
    ]);

    Log::info('Dados validados com sucesso', [
        'admin_id' => $request->admin_id,
        'user_id' => $request->user_id,
        'itens' => $request->input('itens'),
    ]);

    // Cria a cautela
    $cautela = Cautela::create([
        'admin_id' => $request->admin_id,
        'user_id' => $request->user_id,
        'status' => 'pendente',
    ]);

    Log::info('Cautela criada', ['cautela_id' => $cautela->id]);

    // Função para criar os itens
    $criarItens = function ($itens, $campoId) use ($cautela) {
        foreach ($itens as $item) {
            Log::info("Criando item {$campoId}", ['item' => $item]);
            $cautela->itens()->create([
                $campoId => $item[$campoId],
                'quantidade' => $item['quantidade'] ?? 1,
            ]);
        }
    };

    if ($request->has('itens.armas')) {
        Log::info('Criando armas');
        $criarItens($request->input('itens.armas'), 'arma_id');
    }

    if ($request->has('itens.espadas')) {
        Log::info('Criando espadas');
        $criarItens($request->input('itens.espadas'), 'espada_id');
    }

    if ($request->has('itens.coletes')) {
        Log::info('Criando coletes');
        $criarItens($request->input('itens.coletes'), 'colete_id');
    }

    Log::info('Itens criados com sucesso');

    return response()->json([
        'success' => true,
        'cautela_id' => $cautela->id,
        'created_at' => $cautela->created_at->toISOString(),
    ]);
}

    



    // Autenticação do usuário para finalizar a cautela
   public function finalizar(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Autentica o usuário
   

    $user = Auth::user();

    // Busca a cautela pendente dele
    $cautela = Cautela::where('user_id', $user->id)->where('status', 'pendente')->first();

    if (!$cautela) {
        return response()->json(['message' => 'Nenhuma cautela pendente encontrada para este usuário.'], 404);
    }

    // Atualiza status e salva quem finalizou (user_confirm_id)
    $cautela->update([
        'status' => 'autorizada',
        'user_confirm_id' => $user->id,
    ]);

    return response()->json(['message' => 'Cautela finalizada com sucesso.']);
}



    // Devolução dos itens com nova autenticação do usuário
    public function devolucao(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Autenticação inválida'], 401);
        }
    
        $cautela = Cautela::findOrFail($request->cautela_id);
    
        // Garantir que a cautela esteja no status 'autorizada' para poder fazer a devolução
        if ($cautela->status !== 'autorizada') {
            return response()->json(['message' => 'A cautela não foi autorizada ainda.'], 400);
        }
    
        $cautela->update(['status' => 'devolvido']);
    
        return response()->json(['message' => 'Itens devolvidos com sucesso']);
    }

    public function usuariosComCautelasPendentes()
    {
        $usuarios = User::whereHas('cautelas', function ($query) {
            $query->where('status', 'pendente');
           
        })
        ->with([
            'opm:id,bpm',
            'postoGraduacao:id,nome',
            'cautelas' => function ($query) {
                $query->where('status', 'pendente')->with([
                    'itens.arma:id,modelo_id,carregador_id,municao_id',
                    'itens.arma.modelo:id,name,calibre_id',
                    'itens.arma.modelo.calibre:id,nome',
                    'itens.arma.carregador:id,capacidade,quantidade',
                    'itens.arma.municao:id,tipo,calibre_id,quantidade',
                    'itens.arma.municao.calibre:id,nome',
                    'itens.algema:id,tipo,num_serie,quantidade',
                    'itens.colete:id,tipo,num_serie,quantidade',
                    'itens.espada:id,tipo,num_serie,quantidade',
                ]);
            }
        ])
        ->get(['id', 'name', 'email', 'apelido', 'opm_id', 'posto_graduacoes_id']);
    
        return response()->json($usuarios);
    }

    public function getCautelasPorUsuario($userId)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }
    
        $cautelas = Cautela::where('user_id', $userId)
            ->where('status', 'pendente')
            ->with([
                'itens.arma:id,modelo_id,carregador_id,municao_id',
                'itens.arma.modelo:id,name,calibre_id',
                'itens.arma.modelo.calibre:id,nome',
                'itens.arma.carregador:id,capacidade,quantidade',
                'itens.arma.municao:id,tipo,calibre_id,quantidade',
                'itens.arma.municao.calibre:id,nome',
                'itens.algema:id,tipo,num_serie,quantidade',
                'itens.colete:id,tipo,num_serie,quantidade',
                'itens.espada:id,tipo,num_serie,quantidade',
                'user:id,name,email,apelido,opm_id,posto_graduacoes_id',
                'user.opm:id,bpm',
                'user.postoGraduacao:id,nome',
            ])
            ->get();
    
        return response()->json($cautelas);
    }
    
    
    
    public function cautelasPendentesDoUsuarioAutenticado()
    {
        $user = auth()->user();
    
        // Se for admin, bloqueia
        if ($user->is_admin) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }
    
        // Busca as cautelas pendentes do próprio usuário
        $cautelas = Cautela::where('user_id', $user->id)
            ->where('status', 'pendente')
            ->with([
                'itens.arma:id,modelo_id,carregador_id,municao_id',
                'itens.arma.modelo:id,name,calibre_id',
                'itens.arma.modelo.calibre:id,nome',
                'itens.arma.carregador:id,capacidade,quantidade',
                'itens.arma.municao:id,tipo,calibre_id,quantidade',
                'itens.arma.municao.calibre:id,nome',
                'itens.algema:id,tipo,num_serie,quantidade',
                'itens.colete:id,tipo,num_serie,quantidade',
                'itens.espada:id,tipo,num_serie,quantidade',
            ])
            ->get();
    
        return response()->json($cautelas);
    }

// No backend, no CautelaController
public function authUser(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    $user = Auth::user();

    return response()->json([
        'success' => true,
        'user' => $user
    ]);
}

    

    
}
