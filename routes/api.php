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
    Route::prefix('profile')->group(function () {
        Route::post('/', 'ProfileController@store');
        Route::get('/', 'ProfileController@getAll');
        Route::patch('/{id}', 'ProfileController@update');
        Route::delete('/{id}', 'ProfileController@destroy');
    });
    Route::prefix('event')->group(function () {
        Route::get('/', 'EventController@index');
        Route::post('/', 'EventController@store');
        Route::get('/new/{id}', 'EventController@getNewEvents')->middleware('throttle:new-events');
        Route::get('/{id}', 'EventController@get');
        Route::get('/get/all', 'EventController@getAll');
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
    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');
});

Route::post('login', 'AuthController@login');