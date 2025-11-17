<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Llm;
use App\Models\system_setting;
use App\Traits\CurrencyHandler;
use Illuminate\Http\Request;
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
    
    public function store(Request $request): JsonResponse
    {
        if ($request->pricing_prompt_token) {
            $request->merge([
                'pricing_prompt_token' => CurrencyHandler::handleDecimalValues(CurrencyHandler::removeCurrencySymbol($request->pricing_prompt_token, "R$"))
            ]);
        }
        if ($request->pricing_completion_token) {
            $request->merge([
                'pricing_completion_token' => CurrencyHandler::handleDecimalValues(CurrencyHandler::removeCurrencySymbol($request->pricing_completion_token, "R$"))
            ]);
        }
        if ($request->api_key) {
            $request->merge([
                'api_key' => encrypt($request->api_key)
            ]);
        }
        return parent::store($request);
    }

    public function show(int $id = null)
    {
        return view('/llm');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if ($request->api_key) {
            $request->merge([
                'api_key' => encrypt($request->api_key)
            ]);
        }
        return parent::update($request, $id);
    }

    public function getIdentifiers(): JsonResponse
    {
        $llms = $this->model->select('id', 'name', 'provider', 'model_id')->get();
        return parent::responseGeneric($llms);
    }

    public function getDefault(): JsonResponse
    {
        $llm_id = system_setting::where('attribute', 'llm_standard')->value('value');
        $data = $this->model->where('id', $llm_id)->get();
        $data->transform(function ($item) {
            $item->api_key = decrypt($item->api_key);
            return $item;
        });
        return parent::responseGeneric($data);
    }
}
