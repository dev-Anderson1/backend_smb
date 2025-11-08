<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->accessToken;
            
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'message' => 'Login realizado com sucesso!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas. Verifique seu email e senha.'
        ], 401);
    }

    public function logout(Request $request)
    {
        if (!auth()->user()) {
            return response()->json([
                'success' => false, 
                'message' => 'Usuário já está deslogado.'
            ]);
        }

        auth()->user()->token()->revoke();
        
        return response()->json([
            'success' => true, 
            'message' => 'Logout realizado com sucesso.'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function check(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }
}
