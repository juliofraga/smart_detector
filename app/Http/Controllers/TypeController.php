<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Type;

class TypeController extends BaseController
{
    public function __construct(Type $type)
    {
        parent::__construct($type);
    }
}
