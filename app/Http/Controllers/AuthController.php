<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginError;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuário não cadastrado no sistema'], 401);
        }
        $loginError = LoginError::where('user_id', $user->id)->first();
        if ($loginError->isBlocked()) {
            if ($this->BlockedByTime($loginError->blocked_at)) {
                return response()->json(['error' => 'Conta temporariamente bloqueada, tente novamente mais tarde'], 403);
            }
        }

        $token = auth('api')->attempt(['email' => $email, 'password' => $password]);
        if ($token) {
            $loginError->resetErrors();
            UserController::registerUserLogin($email);
            return response()->json(['token' => $token]);
        } else {
            $loginError = LoginError::firstOrCreate(['user_id' => $user->id]);
            if ($loginError->error_count >= 5) {
                $loginError->block();
                return response()->json(['error' => 'Sua conta foi temporariamente bloqueada, tente novamente mais tarde'], 403);
            }
            $loginError->incrementErrorCount();
            return response()->json(['error' => 'Credenciais Inválidas'], 401);
        }
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    private function BlockedByTime(string $blocked_at): bool
    {
        $blocked = new \DateTime($blocked_at);
        $blocked->add(new \DateInterval('PT10M'));
        $now = new \DateTime();
        if ($now > $blocked) {
            return false;
        } else {
            return true;
        }
    }
}
