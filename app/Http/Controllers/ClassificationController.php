<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Classification;
use Illuminate\Http\JsonResponse;

class ClassificationController extends BaseController
{
    public function __construct(Classification $profile)
    {
        parent::__construct($profile);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['id', 'asc']);
    }

    public function show(int $id = null)
    {
        return view('/classifications');
    }
}
