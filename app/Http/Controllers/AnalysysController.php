<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Analysys;

class AnalysysController extends BaseController
{
    public function __construct(Analysys $analysys)
    {
        parent::__construct($analysys);
    }
}
