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

    public function show(int $id = null)
    {
        return view('/classifications');
    }
}
