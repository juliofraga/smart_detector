<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends AbstractController
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

    public function index(Request $request): JsonResponse
    {
        return parent::paginate($request, null, ['name', 'asc']);
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
