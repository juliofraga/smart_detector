<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Classification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

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
        $text = Lang::get('text.classification_domain');
        $buttons = Lang::get('text.buttons');
        $translations = array_merge($text, $buttons);
        return view('/classifications', ['translations' => $translations]);
    }
}
