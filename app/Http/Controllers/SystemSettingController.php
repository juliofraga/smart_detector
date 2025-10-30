<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\system_setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemSettingController extends BaseController
{
    public function __construct(system_setting $ss)
    {
        parent::__construct($ss);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        $data = $this->model->orderby('id', 'asc')->get();
        return parent::responseGeneric($data);
    }

    public function show(int $id = null)
    {
        return view('/system-settings');
    }
}
