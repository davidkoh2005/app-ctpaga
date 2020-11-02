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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('version', 'AuthController@VersionApp');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
        Route::post('updateUserImg', 'UsersController@updateImg');
        Route::post('updateUser', 'UsersController@updateUser');
        Route::get('updateBankUser', 'UsersController@updateBankUser');
        Route::post('updateCommerceUser', 'UsersController@updateCommerceUser');
        Route::get('test', 'UsersController@testJson');
    });
});
