<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\system_setting;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $timezoneSetting = system_setting::where('attribute', 'timezone_selected')->first();
        if ($timezoneSetting && $timezoneSetting->value) {
            Config::set('app.timezone', $timezoneSetting->value);
            date_default_timezone_set($timezoneSetting->value);
        }
    }
}
