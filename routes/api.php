<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Broadcast::routes(['middleware' => ['jwt.auth']]);

Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::prefix('user')->middleware('admin')->group(function () {
        Route::post('/', 'UserController@store');
        Route::get('/', 'UserController@index');
        Route::delete('/{id}', 'UserController@destroy');
    });
    Route::patch('user/{id}', 'UserController@update');
    Route::prefix('profile')->group(function () {
        Route::post('/', 'ProfileController@store');
        Route::get('/', 'ProfileController@getAll');
        Route::patch('/{id}', 'ProfileController@update');
        Route::delete('/{id}', 'ProfileController@destroy');
    });
    Route::prefix('event')->group(function () {
        Route::get('/', 'EventController@index');
        Route::post('/', 'EventController@store')
            ->withoutMiddleware('throttle:api')
            ->middleware('throttle:store-events');
        Route::get('/{id}', 'EventController@get');
        Route::get('/get/all', 'EventController@getAll');
        Route::get('/get/dashboards', 'EventController@getDashboards');
    });
    Route::prefix('classification')->middleware('admin')->group(function () {
        Route::post('/', 'ClassificationController@store');
        Route::get('/', 'ClassificationController@index');
        Route::patch('/{id}', 'ClassificationController@update');
        Route::delete('/{id}', 'ClassificationController@destroy');
    });
    Route::prefix('type')->middleware('admin')->group(function () {
        Route::post('/', 'TypeController@store');
        Route::get('/', 'TypeController@index');
        Route::patch('/{id}', 'TypeController@update');
        Route::delete('/{id}', 'TypeController@destroy');
    });
    Route::prefix('event-attribute')->middleware('admin')->group(function () {
        Route::post('/', 'EventAttributeController@store');
        Route::get('/', 'EventAttributeController@index');
        Route::patch('/{id}', 'EventAttributeController@update');
        Route::delete('/{id}', 'EventAttributeController@destroy');
    });
    Route::get('event-attribute/show-enabled', 'EventAttributeController@getShowEnabled');
    Route::prefix('system-settings')->middleware('admin')->group(function () {
        Route::get('/', 'SystemSettingController@index');
        Route::patch('/', 'SystemSettingController@update');
        Route::get('/timezones', function () {
            $timezones = collect(DateTimeZone::listIdentifiers())
                ->groupBy(function ($tz) {
                    return explode('/', $tz)[0];
                });
            return response()->json($timezones);
        });
    });
    Route::prefix('llm')->middleware('admin')->group(function () {
        Route::post('/', 'LlmController@store');
        Route::get('/', 'LlmController@index');
        Route::get('/identifiers', 'LlmController@getIdentifiers');
        Route::get('/default', 'LlmController@getDefault');
        Route::patch('/{id}', 'LlmController@update');
        Route::delete('/{id}', 'LlmController@destroy');
    });
    Route::prefix('ids')->middleware('admin')->group(function () {
        Route::post('/', 'IdsAgentController@store');
        Route::get('/', 'IdsAgentController@index');
        Route::patch('/{id}', 'IdsAgentController@update');
        Route::delete('/{id}', 'IdsAgentController@destroy');
    });
    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');
    
});

Route::post('login', 'AuthController@login');
Route::post('user/update-password/', 'UserController@updatePassword');