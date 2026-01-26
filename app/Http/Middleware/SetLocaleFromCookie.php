<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class SetLocaleFromCookie
{
    public function handle($request, Closure $next)
    {
        if ($request->hasCookie('app_locale')) {
            $locale = $request->cookie('app_locale');

            App::setLocale($locale);
            Config::set('app.locale', $locale);
        } else {
            $settings = config('system_settings');
            if (!empty($settings['select_language'])) {
                App::setLocale($settings['select_language']);
                Config::set('app.locale', $settings['select_language']);
            }
        }
        return $next($request);
    }
}
