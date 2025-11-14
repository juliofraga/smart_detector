<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Llm;
use Illuminate\Http\JsonResponse;

class LlmController extends BaseController
{
    public function __construct(Llm $llm)
    {
        parent::__construct($llm);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['name', 'asc']);
    }

    public function show(int $id = null)
    {
        return view('/llm');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if ($request->api_key) {
            $request->merge([
                'api_key' => bcrypt($request->api_key)
            ]);
        }
        return parent::update($request, $id);
    }

    public function getIdentifiers(): JsonResponse
    {
        $llms = $this->model->select('id', 'name', 'provider', 'model_id')->get();
        return parent::responseGeneric($llms);
    }
}
