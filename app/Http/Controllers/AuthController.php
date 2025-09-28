<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
   

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Criando o token de acesso usando Passport
        $token = $user->createToken('api-token')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    return response()->json(['message' => 'Credenciais inválidas'], 401);
}



public function authPolicial(Request $request)
{
    Log::info('authPolicial chamado.', ['request_data' => $request->all()]);

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    Log::info('Validação ok. Tentando autenticar usuário.', [
        'email' => $request->input('email'),
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        Log::warning('Falha na autenticação para o email: ' . $request->input('email'));
        return response()->json(['success' => false, 'message' => 'Credenciais inválidas.'], 401);
    }

    $user = Auth::user();

    Log::info('Usuário autenticado com sucesso.', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);

    return response()->json([
        'success' => true,
        'user' => $user,
    ]);
}





   


    // Método para fazer logoutpublic function logout(Request $request)
   
    public function logout(Request $request)
{
    if (!auth()->user()) {
        return response()->json(['success' => false, 'mensagem' => 'Usuário já está deslogado.']);
    }

    auth()->user()->token()->revoke();
    return response()->json(['success' => true, 'mensagem' => 'Desconectado com sucesso.']);
}



    // Método para verificar se o usuário está autenticado
    public function check(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }
}
