<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TypeController extends BaseController
{
    public function __construct(Type $type)
    {
        parent::__construct($type);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['id', 'asc']);
    }

    public function show(int $id = null)
    {
        return view('/types');
    }
}
