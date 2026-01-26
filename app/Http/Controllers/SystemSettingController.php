<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\system_setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class SystemSettingController extends BaseController
{
    public function __construct(system_setting $ss)
    {
        parent::__construct($ss);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        $data = $this->model->orderby('orderby', 'asc')->get();
        return parent::responseGeneric($data);
    }

    public function show(int $id = null)
    {
        return view('/system-settings');
    }

    public function update(Request $request, int $id = 0): JsonResponse
    {
        $attribute = array_key_first($request->all());
        $value = $request->input($attribute);
        if ($attribute === 'request_per_minute' && $value > 5000) {
            return parent::responseGeneric(__('text.value_exceed'), 422, 'error');
        }

        $data = $this->model->where('attribute', $attribute)->first();
        if (!$data) {
            return parent::responseDataNotFound();
        }
        if ($attribute === 'select_language') {
            Cookie::queue('app_locale', $request->select_language, 525600);
        }

        $data->value = $value;
        $update = $data->save();

        return parent::responseOther($update, 'update');
    }
}
