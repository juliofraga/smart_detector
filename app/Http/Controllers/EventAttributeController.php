<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\event_attribute;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventAttributeController extends BaseController
{
    public function __construct(event_attribute $event_attribute)
    {
        parent::__construct($event_attribute);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['id', 'asc']);
    }

    public function show(int $id = null)
    {
        return view('/event_attributes');
    }
}
