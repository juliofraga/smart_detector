<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends BaseController
{
    public function __construct(Profile $profile)
    {
        parent::__construct($profile);
    }

    public function getAll(Request $request, array $attributes = null): JsonResponse
    {
        return parent::getAll($request, ['id', 'description']);
    }
}
