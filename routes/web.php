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

Route::get('sockets/serve', function(){
    \Illuminate\Support\Facades\Artisan::call('websockets:serve');
});

Route::get('/admin/login', function () {
    return view('auth.login')->with('type', 0);
})->name('admin.login');

Route::get('/login', function () {
    return view('auth.login')->with('type', 1);
})->name('commerce.login');

Route::post('login/form', 'CommerceController@login')->name('formCommerce.login');
Route::post('admin/login/', 'AdminController@login')->name('formAdmin.login');
Route::get('logout/', 'AdminController@logout')->name('logout');
Route::post('logout/', 'AdminController@logout')->name('logout');

Route::group(['middleware'=>'web'], function() {
    Route::get('/inicio/', 'CommerceController@dashboard')->name('commerce.dashboard');
    Route::post('/transacciones', 'CommerceController@transactions')->name('commerce.transactions');
    Route::get('/transacciones', 'CommerceController@transactions')->name('commerce.transactions');
});

Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/', 'AdminController@dashboard')->name('admin.dashboard');
    Route::post('/admin/dataGraphic', 'AdminController@dataGraphic')->name('admin.dataGraphic');
    Route::get('/admin/depositos', 'AdminController@index')->name('admin.balance');
    Route::post('/admin/depositos', 'AdminController@index')->name('admin.balance');
    Route::get('/admin/comerciantes', 'AdminController@commerces')->name('admin.commerces');
    Route::get('/admin/comerciantes/{id}', 'AdminController@commercesShow')->name('admin.commercesShow');
    Route::post('/admin/transacciones', 'AdminController@transactions')->name('admin.transactionsSearch');
    Route::get('/admin/transacciones', 'AdminController@transactions')->name('admin.transactions');
    Route::get('/admin/transacciones/{id}', 'AdminController@transactions')->name('admin.transactionsSearchId');
    Route::get('/admin/transaccionesShow/', 'AdminController@transactionsShow')->name('admin.transactionsShow');
    Route::get('/admin/depositos/{id}', 'AdminController@show')->name('admin.show');
    Route::post('/admin/payment', 'AdminController@showPayment')->name('admin.showPayment');
    Route::get('/admin/reportPayment', 'AdminController@reportPayment')->name('admin.reportPayment');
    Route::post('/admin/reportPayment', 'AdminController@reportPayment')->name('admin.reportPayment');
});

Route::post('/admin/removePicture', 'AdminController@removePicture')->name('admin.removePicture');
Route::post('admin/saveDeposits', 'AdminController@saveDeposits')->name('admin.saveDeposits');

Route::get('password/create', 'Auth\PasswordResetController@create');
Route::get('password/find/{token}', 'Auth\PasswordResetController@find');
Route::post('password/reset', 'Auth\PasswordResetController@reset')->name('form.passwordReset');

Route::get('password/delivery/create', 'Auth\PasswordResetController@createDelivery');
Route::get('password/delivery/find/{token}', 'Auth\PasswordResetController@findDelivery');
Route::post('password/delivery/reset', 'Auth\PasswordResetController@resetDelivery')->name('form.passwordResetDelivery');


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