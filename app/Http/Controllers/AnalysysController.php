<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Analysys;
use Illuminate\Http\JsonResponse;

class AnalysysController extends BaseController
{
    public function __construct(Analysys $analysys)
    {
        parent::__construct($analysys);
    }
}
