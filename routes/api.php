<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::prefix('user')->middleware('admin')->group(function () {
        Route::post('/', 'UserController@store');
        Route::get('/', 'UserController@index');
        Route::patch('/{id}', 'UserController@update');
        Route::delete('/{id}', 'UserController@destroy');
    });
    Route::prefix('event')->group(function () {
        Route::get('/', 'EventController@index');
        Route::post('/', 'EventController@store');
        Route::get('/new/{id}', 'EventController@getNewEvents')->middleware('throttle:new-events');
        Route::get('/{id}', 'EventController@get');
        Route::get('/get/all', 'EventController@getAll');
    });
    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');
});

Route::post('login', 'AuthController@login');