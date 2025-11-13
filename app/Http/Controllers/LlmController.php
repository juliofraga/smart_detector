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
}
