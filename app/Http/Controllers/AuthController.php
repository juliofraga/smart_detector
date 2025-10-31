<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginError;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;
use App\Models\system_setting;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        $blockUserSetting = system_setting::where('attribute', 'block_user')->first()->value === "Sim" ? true : false;
        if (!$user) {
            return parent::responseGeneric('Usuário não cadastrado no sistema', 401, 'error');
        }
        $loginError = LoginError::where('user_id', $user->id)->first();
        if ($blockUserSetting) {
            if ($loginError && $loginError->isBlocked()) {
                if ($this->BlockedByTime($loginError->blocked_at)) {
                    return parent::responseGeneric('Conta temporariamente bloqueada, tente novamente mais tarde', 403, 'error');
                }
            }
        }

        $token = auth('api')->attempt(['email' => $email, 'password' => $password]);
        if ($token) {
            if ($loginError) {
                $loginError->resetErrors();
            }
            if ($user->updated_pass == 0) {
                return response()->json(['error' => 'Usuário deve atualizar a senha via login web.', 'status' => 428], 428);
            }
            UserController::registerUserLogin($email);
            return parent::responseGeneric($token, 201, 'token');
        } else {
            if ($blockUserSetting) {
                $loginError = LoginError::firstOrCreate(['user_id' => $user->id]);
                if ($loginError->error_count >= 5) {
                    $loginError->block();
                    return parent::responseGeneric('Sua conta foi temporariamente bloqueada, tente novamente mais tarde', 403, 'error');
                }
                $loginError->incrementErrorCount();
            }
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
