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

        Route::post('createCommerce', 'UserController@createCommerce');
        Route::post('updateCommerceUser', 'UserController@updateCommerceUser');

        Route::post('showCategories', 'CategoryController@show');
        Route::post('newCategories', 'CategoryController@new');

        Route::post('showProducts', 'ProductController@show');
        Route::post('newProducts', 'ProductController@new');
        Route::post('updateProducts', 'ProductController@update');
        Route::post('deleteProducts', 'ProductController@delete');

        Route::post('showServices', 'ServiceController@show');
        Route::post('newServices', 'ServiceController@new');
        Route::post('updateServices', 'ServiceController@update');
        Route::post('deleteServices', 'ServiceController@delete');

        Route::post('showShipping', 'ShippingController@show');
        Route::post('newShipping', 'ShippingController@new');
        Route::post('updateShipping', 'ShippingController@update');
        Route::post('deleteShipping', 'ShippingController@delete');

        Route::post('showDiscounts', 'DiscountController@show');
        Route::post('newDiscounts', 'DiscountController@new');
        Route::post('updateDiscounts', 'DiscountController@update');
        Route::post('deleteDiscounts', 'DiscountController@delete');



        Route::get('test', 'UserController@testJson');
    });
});
