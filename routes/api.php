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

    Route::post('loginDelivery', 'AuthController@loginDelivery');
    Route::post('signupDelivery', 'AuthController@signupDelivery');

    Route::post('version', 'AuthController@VersionApp');
    Route::post('verifyUrl', 'UserController@verifyUrlUser');

    Route::group(['middleware' => 'auth:delivery'], function() {
        Route::post('logoutDelivery', 'AuthController@logout');
        Route::post('delivery', 'AuthController@delivery');
        Route::post('updatePasswordDelivery', 'AuthController@updatePassword');
        Route::post('updateDeliveryImg', 'DeliveryController@updateImg');
        Route::post('updateDeliveryDocuments', 'DeliveryController@updateDocuments');
        Route::post('updateDelivery', 'DeliveryController@update');

        Route::post('showPaidDelivery', 'PaidController@showPaidDelivery');
        Route::post('orderPaidDelivery', 'PaidController@orderPaidDelivery');
        Route::post('changeStatus', 'PaidController@changeStatus');

        Route::get('showPaidAll', 'DeliveryController@showPaidAll');
   
    });
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
        Route::post('updatePassword', 'AuthController@updatePassword');

        Route::post('updateUserImg', 'UserController@updateImg');
        Route::post('updateUser', 'UserController@updateUser');
        Route::post('updateEmailUser', 'UserController@updateEmailUser');

        Route::get('updateBankUser', 'UserController@updateBankUser');

        Route::post('createCommerce', 'UserController@createCommerce');
        Route::post('updateCommerceUser', 'UserController@updateCommerceUser');
        Route::post('deleteCommerceUser', 'UserController@deleteCommerceUser');

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

        Route::post('showRates', 'RateController@show');
        Route::post('newRates', 'RateController@new');

        Route::post('newSales', 'SaleController@new');
        Route::get('showSales', 'SaleController@showSales');

        Route::post('showPaids', 'PaidController@show');

        Route::post('showBalances', 'BalanceController@show');
        
    });
});