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

    Route::get('/account', 'UserController@profile');
    Route::get('/delete_account', 'UserController@destroy');
    Route::post('/edit_account', 'UserController@edit_account');

    Route::get('/events', 'EventController@index');
    Route::get('/events/{id}', 'EventController@show');
    Route::post('/create_event', 'EventController@store');
    Route::post('/edit_event/{id}', 'EventController@edit_event');
    Route::post('/delete_event/{id}', 'EventController@destroy');

    Route::get('/listes/{id}', 'ListeController@show');
    Route::post('/{id}/create_liste', 'ListeController@store');
    Route::post('/edit_liste/{id}', 'ListeController@edit_liste');
    Route::post('/delete_liste/{id}', 'ListeController@destroy');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
