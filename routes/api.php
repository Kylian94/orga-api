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

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/events', 'EventController@index');

    Route::get('/account', 'UserController@profile');
    Route::get('/delete_account', 'UserController@destroy');
    Route::post('/edit_account', 'UserController@edit_account');


    Route::get('/events/{id}', 'EventController@show');
    Route::post('/create_event', 'EventController@store');
    Route::post('/edit_event/{id}', 'EventController@edit_event');
    Route::post('/delete_event/{id}', 'EventController@destroy');

    Route::get('/listes/{id}', 'ListeController@show');
    Route::post('/{event_id}/create_liste', 'ListeController@store');
    Route::post('/edit_liste/{id}', 'ListeController@edit_liste');
    Route::post('/delete_liste/{id}', 'ListeController@destroy');

    Route::post('/{liste_id}/create_item', 'ItemController@store');
    Route::post('/edit_item/{id}', 'ItemController@edit_item');
    Route::post('/delete_item/{id}', 'ItemController@destroy');

    Route::get('/friends', 'FriendController@show');
    Route::get('/friends_pending', 'FriendController@pending');
    Route::get('/friends_request', 'FriendController@request');
    Route::post('/add_friend/{user_id}', 'FriendController@store');
    Route::post('/search_friends', 'FriendController@search');
    Route::post('/accept_friend/{user_id}', 'FriendController@accept_friend');
    Route::post('/delete_friend/{user_id}', 'FriendController@destroy');

    Route::get('/{event_id}/members', 'MemberController@show');
    Route::post('/add_members', 'MemberController@store');
    Route::post('/{event_id}/accept_event', 'MemberController@accept_event');
    Route::post('/{event_id}/cancel_event', 'MemberController@cancel_event');
    Route::post('/{event_id}/delete_member/{member_id}', 'MemberController@destroy');

    Route::post('/logout', 'UserController@logout');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
