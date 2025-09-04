<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate(User::rules(), User::feedback());
        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password)
            ]);
        }
        $user = User::create($request->all());
        if ($user) {
            return response()->json($user, 201);
        } else {
            return response()->json(['error' => 'Falha ao criar o registro.'], 500);
        }
    }
}
