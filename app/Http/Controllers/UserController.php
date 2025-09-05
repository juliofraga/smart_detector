<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

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

    public function index(Request $request)
    {
        return $this->paginate($request, null, ['name', 'asc']);
    }

    public function paginate(Request $request, int $qtd = null, array $order) 
    {
        $by = $order[0];
        $direction = $order[1];
        $qtd = $qtd ?? 20;
        $data = [];
        if($request->has('filter')) {
            $this->filter($request->filter);
        }
        $data = $this->model->orderby($by, $direction)->paginate($qtd);
        return response()->json($data, 200);
    }

    public function filter(string $filters) 
    {
        $filters = explode(';', $filters);
        $this->model = User::where(function ($query) use ($filters) {
            foreach ($filters as $key => $condition) {
                $c = explode(':', $condition);
                $query->orWhere($c[0], $c[1], $c[2]);
            }
        });
    }
}
