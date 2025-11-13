<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\llm;
use Illuminate\Http\JsonResponse;

class LlmController extends BaseController
{
    public function __construct(llm $llm)
    {
        parent::__construct($llm);
    }

    public function show(int $id = null)
    {
        return view('/llm');
    }
}
