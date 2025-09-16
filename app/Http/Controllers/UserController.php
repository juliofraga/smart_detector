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

    public function store(Request $request): JsonResponse
    {
        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password)
            ]);
        }
        return parent::store($request);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return parent::update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return parent::destroy($id);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['name', 'asc']);
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
}
