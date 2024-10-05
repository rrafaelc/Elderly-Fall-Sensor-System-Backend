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

//PUBLIC USER ROUTES
Route::get("user", "App\Http\Controllers\UsersController@index");
Route::get("user/{user}", "App\Http\Controllers\UsersController@show");
Route::post("user", "App\Http\Controllers\UsersController@store");


//
//USER ROUTES
Route::patch("user/{user}", "App\Http\Controllers\UsersController@update");
Route::delete("user/{user}", "App\Http\Controllers\UsersController@destroy");

//PERSON ROUTES
Route::get("person", "App\Http\Controllers\PersonsController@index");
Route::get("person/{person}", "App\Http\Controllers\PersonsController@show");
Route::post("person", "App\Http\Controllers\PersonsController@store");
Route::patch("person/{person}", "App\Http\Controllers\PersonsController@update");
Route::delete("person/{person}", "App\Http\Controllers\PersonsController@destroy");

//DEVICE ROUTES
Route::get("device", "App\Http\Controllers\DevicesController@index");
Route::get("device/{device}", "App\Http\Controllers\DevicesController@show");
Route::post("device", "App\Http\Controllers\DevicesController@store");
Route::patch("device/{device}", "App\Http\Controllers\DevicesController@update");
Route::delete("device/{device}", "App\Http\Controllers\DevicesController@destroy");
Route::post("devicecreate", "App\Http\Controllers\DevicesController@create");

Route::post('/device/{deviceId}/associate', "App\Http\Controllers\DevicesController@associate");


Route::post('login', "App\Http\Controllers\AuthController@login");
Route::post('logout', "App\Http\Controllers\AuthController@logout");
Route::post('refresh', "App\Http\Controllers\AuthController@refresh");
Route::post('me', "App\Http\Controllers\AuthController@me");
