<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\event_attribute;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\EventController;

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

    public function store(Request $request): JsonResponse
    {
        $request->validate($this->model->rules(), $this->model->feedback());
        $ret = EventController::addTableColumn($request->field_name, $request->type_field);
        if ($ret) {
            return parent::store($request);
        }
        return parent::responseError();
    }

    public function destroy(int $id): JsonResponse
    {
        $event = event_attribute::find($id);
        if ($event) {
            $field_name = $event->field_name;
            EventController::removeColumn($field_name);
        }
        return parent::destroy($id);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        EventController::updateColumn($request->type_field, $request->field_name);
        if ($request->enabled == 0) {
            $request->merge([
                'show' => 0
            ]);
        }
        return parent::update($request, $id);
    }
}
