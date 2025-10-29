<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['name', 'asc']);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user_id = $request->user()->id;
        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password),
                'updated_pass' => $user_id != $id ? 0 : 1
            ]);
        } else {
            $request->request->remove('password');
        }
        return parent::update($request, $id);
    }

    public function paginate(Request $request, int $qtd = null, array $order): JsonResponse
    {
        $by = $order[0];
        $direction = $order[1];
        $qtd = $qtd ?? 20;
        $data = [];
        if($request->has('filter')) {
            $this->filter($request->filter);
        }
        $data = $this->model->with('profile')->orderby($by, $direction)->paginate($qtd);
        return parent::responseGeneric($data);
    }

    public function show(int $id = null)
    {
        return view('/users');
    }

    public function showMyAccount()
    {
        return view('/my-account');
    }

    public static function registerUserLogin(string $email): void
    {
        $now = date('Y-m-d H:i:s');
        User::where('email', $email)->update(['last_access' => $now]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->all(['email', 'password', 'passwordNew']);
        $token = auth('api')->attempt(['email' => $data['email'], 'password' => $data['password']]);
        if (!$token) {
            return parent::responseGeneric('Senha temporária inválida, tente novamente!', 403, 'error');
        } else {
            User::where('email', $data['email'])->update([
                'password' => bcrypt($data['passwordNew'])
            ]);
            $data['password'] = $data['passwordNew'];
            $token = auth('api')->attempt($data);
            if ($token) {
                User::where('email', $data['email'])->update([
                    'updated_pass' => 1
                ]);
                return parent::responseSuccessUpdate();
            } else {
                return parent::responseError();
            }
        }
    }
}
