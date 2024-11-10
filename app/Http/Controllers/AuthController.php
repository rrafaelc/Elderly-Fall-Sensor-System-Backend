<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Email ou senha inválidos'], 401);
        }

        // Get user details from database - verificando usuário no banco.
        $user = User::where('email', $request->email)->first();


        // Return JSON data for user details - retorno JSON com dados do usuário.
        return response()->json([
            'message' => 'Logado com sucesso',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    public function me(){

        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    // public function refresh() {
    //     $token = auth('api')->refresh();
    //     return response()->json(['token' => $token]);
    // }

}
