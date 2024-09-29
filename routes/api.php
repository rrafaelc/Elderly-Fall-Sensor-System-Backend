<?php
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
Route::get("user", "App\Http\Controllers\UsersController@index");
Route::get("user/{user}", "App\Http\Controllers\UsersController@show");
Route::post("user", "App\Http\Controllers\UsersController@store");
Route::patch("user/{user}", "App\Http\Controllers\UsersController@update");
Route::delete("user/{user}", "App\Http\Controllers\UsersController@destroy");
