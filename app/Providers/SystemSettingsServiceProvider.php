<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\system_setting;
use Illuminate\Support\Facades\App;

class SystemSettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            $settings = Cache::rememberForever('system_settings', function () {
                return system_setting::all(['attribute', 'value'])
                    ->pluck('value', 'attribute')
                    ->toArray();
            });

            Config::set('system_settings', $settings);

            // Timezone
            if (!empty($settings['timezone_selected'])) {
                Config::set('app.timezone', $settings['timezone_selected']);
                date_default_timezone_set($settings['timezone_selected']);
            }

            // Locale
            if (!empty($settings['select_language'])) {
                Config::set('app.locale', $settings['select_language']);
                App::setLocale($settings['select_language']);
            }

        } catch (\Exception $e) {
            Log::error("Error to cache system settings: " . $e->getMessage());
        }
    }
}
