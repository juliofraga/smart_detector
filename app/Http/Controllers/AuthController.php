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
            return parent::responseGeneric('Usuário não cadastrado no sistema', 401, 'error');
        }
        $loginError = LoginError::where('user_id', $user->id)->first();
        if ($loginError && $loginError->isBlocked()) {
            if ($this->BlockedByTime($loginError->blocked_at)) {
                return parent::responseGeneric('Conta temporariamente bloqueada, tente novamente mais tarde', 403, 'error');
            }
        }

        $token = auth('api')->attempt(['email' => $email, 'password' => $password]);
        if ($token) {
            if ($loginError) {
                $loginError->resetErrors();
            }
            UserController::registerUserLogin($email);
            return parent::responseGeneric($token, 201, 'token');
        } else {
            $loginError = LoginError::firstOrCreate(['user_id' => $user->id]);
            if ($loginError->error_count >= 5) {
                $loginError->block();
                return parent::responseGeneric('Sua conta foi temporariamente bloqueada, tente novamente mais tarde', 403, 'error');
            }
            $loginError->incrementErrorCount();
            return parent::responseGeneric('Credenciais Inválidas', 401, 'error');
        }
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return parent::responseGeneric('Logout realizado com sucesso');
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
