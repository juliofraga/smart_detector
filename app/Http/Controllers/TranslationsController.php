<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class TranslationsController extends BaseController
{

    public function getTranslation(string $domain): JsonResponse
    {
        if (!empty($domain)) {
            $translations = Lang::get('text.' . $domain);
            return parent::responseGeneric($translations);
        }
        return parent::responseGeneric(__('text.inform_domain_error'), 500, 'error');
    }

}