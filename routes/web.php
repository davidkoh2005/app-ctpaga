<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome'); 

Route::fallback(function () {
    return redirect()->route('welcome');
});

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('admin/login/', 'AdminController@login')->name('form.login');
Route::get('admin/logout/', 'AdminController@logout')->name('admin.logout');
Route::post('admin/logout/', 'AdminController@logout')->name('admin.logout');

Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/admin/balance', 'AdminController@index')->name('admin.balance');
    Route::get('/admin/comerciante', 'AdminController@commerces')->name('admin.commerces');
    Route::get('/admin/comerciante/{id}', 'AdminController@commercesShow')->name('admin.commercesShow');
    Route::get('/admin/transacciones', 'AdminController@transactions')->name('admin.transactions');
    Route::get('/admin/transaccionesShow/', 'AdminController@transactionsShow')->name('admin.transactionsShow');
    Route::get('/admin/{id}', 'AdminController@show')->name('admin.show');
});

Route::post('/admin/removePicture', 'AdminController@removePicture')->name('admin.removePicture');
Route::post('admin/saveDeposits', 'AdminController@saveDeposits')->name('admin.saveDeposits');

Route::get('password/create', 'Auth\PasswordResetController@create');
Route::get('password/find/{token}', 'Auth\PasswordResetController@find');
Route::post('password/reset', 'Auth\PasswordResetController@reset')->name('form.passwordReset');

Route::get('/{userUrl}/', 'SaleController@indexStore')->name('form.store');
Route::get('/{userUrl}/{codeUrl}/{statusModification?}', 'SaleController@index');
Route::post('verify', 'SaleController@verifyDiscount');
Route::post('showCategories', 'SaleController@showCategories')->name('show.categories');
Route::post('showProductsServices', 'SaleController@showProductsServices')->name('show.productsServices');

Route::post('update', 'Auth\PasswordResetController@updatePassword')->name('user.updatePassword');
Route::post('pay', 'PaidController@formSubmit')->name('form.formSubmit');
Route::post('newSales', 'SaleController@new')->name('sale.newSale');
Route::post('modifysale', 'SaleController@modifysale')->name('sale.modifysale');
Route::post('removeSale', 'SaleController@removeSale')->name('sale.removeSale');

