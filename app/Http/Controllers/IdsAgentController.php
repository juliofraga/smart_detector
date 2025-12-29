<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\ids_agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class IdsAgentController extends BaseController
{
    public function __construct(ids_agent $ids)
    {
        parent::__construct($ids);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['id', 'asc']);
    }

    public function show(int $id = null)
    {
        $translations = Lang::get('text.ids_domain');
        return view('/ids', ['translations' => $translations]);
    }

    public function getIdentifiers(): JsonResponse
    {
        $ids = $this->model->select('id', 'name', 'hostname', 'ip_local')->get();
        return parent::responseGeneric($ids);
    }
}
