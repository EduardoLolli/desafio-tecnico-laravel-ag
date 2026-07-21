<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:120',
            'email'    => 'required|string|email|max:250|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        /** @var JWTGuard $auth */
        $auth = auth('api');

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => $request->input('password'), 
        ]);

        $token = $auth->login($user);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        /** @var JWTGuard $auth */
        $auth = auth('api');

        if (!$token = $auth->attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas.'], 401);
        }

        return response()->json([
            'token'      => $token,
        ]);
    }

    public function logout()
    {
        /** @var JWTGuard $auth */
        $auth = auth('api');
        
        $auth->logout();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}