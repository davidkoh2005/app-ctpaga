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
        Route::post('updateUserImg', 'UserController@updateImg');
        Route::post('updateUser', 'UserController@updateUser');
        Route::get('updateBankUser', 'UserController@updateBankUser');
        Route::post('updateCommerceUser', 'UserController@updateCommerceUser');
        Route::get('test', 'UserController@testJson');

        Route::post('showCategories', 'CategoryController@show');
        Route::post('newCategories', 'CategoryController@new');

        Route::post('showProducts', 'ProductController@show');
        Route::post('newProducts', 'ProductController@new');
    });
});
