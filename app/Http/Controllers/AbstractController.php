<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

abstract class AbstractController extends Controller
{
    
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function store(Request $request): JsonResponse
    {
        $request->validate($this->model->rules(), $this->model->feedback());
        $data = $this->model->create($request->all());
        return parent::response($data);
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
        $data = $this->model->find($id);
        if (!$data) {
            return parent::responseDataNotFound();
        }
        $update = empty($except) ? $data->update($request->all()) : $data->update($request->except($except));
        return parent::responseOther($update, 'update');
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->model->find($id);
        if (!$model) {
            return parent::responseDataNotFound();
        }
        $delete = $model->delete();
        return parent::responseOther($delete, 'delete');
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
        return parent::responseGeneric($data);
    }

    public function filter(string $filters): void
    {
        $filters = explode(';', $filters);
        $this->model = $this->model->where(function ($query) use ($filters) {
            foreach ($filters as $key => $condition) {
                $c = explode(':', $condition);
                $query->orWhere($c[0], $c[1], $c[2]);
            }
        });
    }

    abstract public function index(Request $request): JsonResponse;

    abstract public function show(int $id = null);

}