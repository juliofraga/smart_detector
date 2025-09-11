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
        $user = $this->model->create($request->all());
        if ($user) {
            return response()->json($user, 201);
        } else {
            return response()->json(['error' => 'Falha ao criar o registro.'], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $except = [];
        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password)
            ]);
        } else {
            $except[] = 'password';
        }
        $user = $this->model->find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
        $update = empty($except) ? $user->update($request->all()) : $user->update($request->except($except));
        return $update ? response()->json($user, 201) : response()->json(['error' => 'Falha ao atualizar o registro.'], 500);
    }

    public function destroy(int $id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
        $delete = $model->delete();
        return $delete ? response()->json(['message' => 'Usuário deletado com sucesso'], 200) : response()->json(['error' => 'Falha ao deletar usuário.'], 500);
    }

    public function index(Request $request): JsonResponse
    {
        return $this->paginate($request, null, ['name', 'asc']);
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
        $data = $this->model->orderby($by, $direction)->paginate($qtd);
        return response()->json($data, 200);
    }

    public function filter(string $filters): void
    {
        $filters = explode(';', $filters);
        $this->model = User::where(function ($query) use ($filters) {
            foreach ($filters as $key => $condition) {
                $c = explode(':', $condition);
                $query->orWhere($c[0], $c[1], $c[2]);
            }
        });
    }

    public function show()
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
