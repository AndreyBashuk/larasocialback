<?php

use Illuminate\Http\Request;

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
Route::get('test', function () {
   return response ([1,2,3,4], 200);
});

Route::post('register', 'API\RegisterController@register')->name('api.register');
Route::post('login', 'API\LoginController@login')->name('api.login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {

    Route::get('/me', 'API\\UserController@show');
    Route::get('chat', 'API\\ChatController@index');
    Route::post('chat', 'API\\ChatController@store');
    Route::delete('chat/{chat}', 'API\\ChatController@destroy');

    Route::post('/message', 'API\\MessageController@store');

    Route::get('message/{chat}', 'API\\MessageController@index');
});
