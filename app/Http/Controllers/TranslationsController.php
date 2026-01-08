<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class TranslationsController
{

    public function getTranslation(string $domain): JsonResponse
    {
        if (!empty($domain)) {
            
            if (str_contains($domain, '__buttons')) {
                $domain = str_replace('__buttons', '', $domain);
                $translations = Lang::get('text.' . $domain);
                $buttons = Lang::get('text.buttons');
                $translations = array_merge($translations, $buttons);
            } else {
                $translations = Lang::get('text.' . $domain);
            }
            return response()->json($translations, 201);
        }
        return response()->json(['error' => __('text.inform_domain_error')], 500);
    }

}