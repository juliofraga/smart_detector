<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all(['email', 'password']);
        $token = auth('api')->attempt($data);
        if ($token) {
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Credenciais Inválidas'], 403);
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado com sucesso']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
