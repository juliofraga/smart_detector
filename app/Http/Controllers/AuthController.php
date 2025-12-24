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
        try {
            $blockUserSetting = system_setting::where('attribute', 'block_user')->first()->value === "Yes" ? true : false;
        } catch (\Throwable $th) {
            return parent::responseGeneric(__('text.incorrect_system_configuration_login_was_not_possible'), 401, 'error');
        }
        if (!$user) {
            return parent::responseGeneric(__('text.user_not_found'), 401, 'error');
        }
        $loginError = LoginError::where('user_id', $user->id)->first();
        if ($blockUserSetting) {
            if ($loginError && $loginError->isBlocked()) {
                if ($this->BlockedByTime($loginError->blocked_at)) {
                    return parent::responseGeneric(__('text.account_temporarily_blocked'), 403, 'error');
                }
            }
        }

        $token = auth('api')->attempt(['email' => $email, 'password' => $password]);
        if ($token) {
            if ($loginError) {
                $loginError->resetErrors();
            }
            if ($user->updated_pass == 0) {
                return response()->json(['error' => __('text.user_must_update_password'), 'status' => 428], 428);
            }
            UserController::registerUserLogin($email);
            return parent::responseGeneric($token, 201, 'token');
        } else {
            if ($blockUserSetting) {
                $loginError = LoginError::firstOrCreate(['user_id' => $user->id]);
                if ($loginError->error_count >= 5) {
                    $loginError->block();
                    return parent::responseGeneric(__('text.your_account_temporarily_blocked'), 403, 'error');
                }
                $loginError->incrementErrorCount();
            }
            return parent::responseGeneric(__('text.invalid_credentials'), 401, 'error');
        }
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return parent::responseGeneric(__('text.logout_successful'));
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
