<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o token está presente nos cabeçalhos da requisição
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token não fornecido.'], 401);
        }

        // Autentica o usuário usando o token fornecido
        Auth::setUser(null); // Limpa qualquer usuário previamente autenticado
        $user = Auth::guard('sanctum')->user(); // Verifica o usuário autenticado pelo token do Sanctum

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado ou token inválido.'], 401);
        }

        // Adiciona o usuário autenticado à requisição
        $request->merge(['user' => $user]);

        return $next($request);
    }
}

