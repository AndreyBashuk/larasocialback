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
    Route::get('users', 'API\\UserController@index')->name('api.users.get');
    Route::get('chats', 'API\\ChatController@index')->name('api.chat.get');
    Route::post('chats', 'API\\ChatController@store')->name('api.chat.post');
    Route::delete('chat/{chat}', 'API\\ChatController@destroy')->name('api.chat.delete');

    Route::post('/message', 'API\\MessageController@store')->name('api.message.post');

    Route::get('message/{chat}', 'API\\MessageController@index')->name('api.message.get');
    Route::delete('message', 'API\\MessageController@destroy')->name('api.message.delete');

    Route::get('friends', 'API\\FriendController@index')->name('api.friends.get');
    Route::post('friends', 'API\\FriendController@store')->name('api.friends.post');
    Route::post('friends/confirm','API\\FriendController@confirm')->name('api.friends.confirm');

    Route::get('notifications', 'API\\NotificationsController@index')->name('api.notifications.get');
    Route::put('notifications', 'API\\NotificationsController@update')->name('api.notifications.update');
});
