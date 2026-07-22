<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        try {

            $validated = $request->validated();

            if ($validated->fails()) {
                return response()->json($validated->errors(), 422);
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
                'seccess' => true,
                'message' => 'Usuário criado com sucesso',
                'data'    => [
                    'user'  => $user,
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar usuário: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            /** @var JWTGuard $auth */
            $auth = auth('api');

            if (!$token = $auth->attempt($credentials)) {
                throw new \Exception('Credenciais inválidas.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'data'    => [
                    'token'      => $token,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao realizar login: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function logout()
    {
        /** @var JWTGuard $auth */
        $auth = auth('api');

        $auth->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso',
            'data' => []
        ]);
    }
}
