<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

Class BaseController extends Controller
{
    
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return $this->paginate($request, null, $attributes);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate($this->model->rules(), $this->model->feedback());
        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password)
            ]);
        }
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
        $f = explode('&filterDate=', $filters);
        $filters = $f[0];
        if (count($f) > 1) {
            $filtersDate = $f[1];
        }
        $filters = explode(';', $filters);
        if (end($filters) === "" || end($filters) === null) {
            array_pop($filters);
        }
        if (isset($filtersDate)) {
            $this->resolveFilterDate($filtersDate);
        }

        $this->model = $this->model->where(function ($query) use ($filters) {
            foreach ($filters as $key => $condition) {
                $c = explode(':', $condition);
                $query->orWhere($c[0], $c[1], $c[2]);
            }
        });
    }

    public function getAll(Request $request, array $attributes = null): JsonResponse
    {
        if ($attributes) {
            $data = $this->model->select($attributes)->get();
        } else {
            $data = $this->model->get();
        }
        return parent::responseGeneric($data);
    }

    public function show(int $id = null)
    {
        return null;
    }

    private function resolveFilterDate(string $filtersDate): void
    {
        $from = '';
        $to = '';
        $fd = explode(';', $filtersDate);
        $field = explode('field:', $fd[0]);
        $field = $field[1];
        if (strpos($fd[1], 'from:') !== false) {
            $from = explode('from:', $fd[1]);
            $from = $from[1];
            $from = str_replace('T', ' ', $from) . ':00';
        } else if (strpos($fd[1], 'to:') !== false) {
            $to = explode('to:', $fd[1]);
            $to = $to[1];
            $to = str_replace('T', ' ', $to) . ':00';
        }
        if (count($fd) > 2) {
            if (strpos($fd[2], 'to:') !== false) {
                $to = explode('to:', $fd[2]);
                $to = $to[1];
                $to = str_replace('T', ' ', $to) . ':00';
            }
        }
        if ($from && $to) {
            $this->model = $this->model->whereBetween($field, [$from, $to]);
        } elseif ($from) {
            $this->model = $this->model->where($field, '>=', $from);
        } elseif ($to) {
            $this->model= $this->model->where($field, '<=', $to);
        }
    }

}