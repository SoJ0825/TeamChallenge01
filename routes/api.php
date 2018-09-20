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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', 'MembersController@index');
Route::get('read/{id}', 'MembersController@show');

Route::get('index/', 'MembersController@index');
Route::post('create', 'MembersController@store');

//Route::get('read', 'MembersController@show');
Route::post('read', 'MembersController@show');

Route::post('login', 'MembersController@login');
Route::get('logout/{id}', 'MembersController@logout');
