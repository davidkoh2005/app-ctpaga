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

Route::get('/test', 'DeliveryController@test');


Route::get('/', function () {
    return view('welcome');
})->name('welcome'); 

Route::fallback(function () {
    return redirect()->route('welcome');
});

Route::get('activate/sockets', function(){
    \Illuminate\Support\Facades\Artisan::call('websockets:serve');
});

Route::post('court/active', function(){
    \Illuminate\Support\Facades\Artisan::call('command:cutDeposits');
})->name('admin.court');

Route::get('/admin/login', function () {
    if (Auth::guard('web')->check()){
        return redirect(route('commerce.dashboard'));
    }elseif (Auth::guard('admin')->check()){
        return redirect(route('admin.dashboard'));
    }

    return view('auth.login')->with('type', 0);
})->name('admin.login');

Route::get('/login', function () {
    if (Auth::guard('web')->check()){
        return redirect(route('commerce.dashboard'));
    }elseif (Auth::guard('admin')->check()){
        return redirect(route('admin.dashboard'));
    }

    return view('auth.login')->with('type', 1);
})->name('commerce.login');

Route::post('login/form', 'CommerceController@login')->name('formCommerce.login');
Route::post('admin/login/', 'AdminController@login')->name('formAdmin.login');
Route::get('logout/', 'AdminController@logout')->name('logout');
Route::post('logout/', 'AdminController@logout')->name('logout');

Route::group(['middleware'=>'web'], function() {
    Route::get('/inicio/', 'CommerceController@dashboard')->name('commerce.dashboard');
    Route::post('/delivery', 'CommerceController@transactions')->name('commerce.transactions');
    Route::post('/transacciones', 'CommerceController@transactions')->name('commerce.transactions');
    Route::get('/transacciones', 'CommerceController@transactions')->name('commerce.transactions');
    Route::get('/historial', 'CommerceController@depositHistory')->name('commerce.depositHistory');
    Route::post('/historial', 'CommerceController@depositHistory')->name('commerce.depositHistory');
    Route::get('/tasa', 'CommerceController@rate')->name('commerce.rate');
    Route::post('/tasa', 'CommerceController@rate')->name('commerce.rate');
});

Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/', 'AdminController@dashboard')->name('admin.dashboard');
    Route::post('/admin/dataGraphic', 'AdminController@dataGraphic')->name('admin.dataGraphic');
    Route::get('/admin/usuario', 'AdminController@listUsers')->name('admin.listUsers');
    Route::post('/admin/usuario', 'AdminController@changeStatusUser')->name('admin.changeStatusUser');
    Route::get('/admin/depositos', 'AdminController@index')->name('admin.balance');
    Route::post('/admin/depositos', 'AdminController@index')->name('admin.balance');
    Route::get('/admin/comerciantes', 'AdminController@commerces')->name('admin.commerces');
    Route::get('/admin/comerciantes/{id}', 'AdminController@commercesShow')->name('admin.commercesShow');
    Route::post('/admin/confirmed', 'AdminController@confirmedCommerce')->name('admin.confirmedCommerce');
    Route::post('/admin/transacciones', 'AdminController@transactions')->name('admin.transactionsSearch');
    Route::get('/admin/transacciones', 'AdminController@transactions')->name('admin.transactions');
    Route::get('/admin/transacciones/{id}', 'AdminController@transactions')->name('admin.transactionsSearchId');
    Route::get('/admin/transaccionesShow/', 'AdminController@transactionsShow')->name('admin.transactionsShow');
    Route::post('/admin/payment', 'AdminController@showPayment')->name('admin.showPayment');
    Route::post('/admin/status', 'AdminController@changeStatus')->name('admin.changeStatus');
    Route::post('/admin/statusPayment', 'AdminController@changeStatusPayment')->name('admin.changeStatusPayment');
    Route::get('/admin/historial', 'AdminController@reportPayment')->name('admin.reportPayment');
    Route::post('/admin/historial', 'AdminController@reportPayment')->name('admin.reportPayment');
    Route::post('/admin/txt', 'AdminController@downloadTxt')->name('admin.downloadTxt');
    Route::get('/admin/delivery', 'AdminController@delivery')->name('admin.delivery');
    Route::post('/admin/delivery', 'AdminController@delivery')->name('admin.deliverySearch');
    Route::post('/admin/showDelivery', 'AdminController@showDeliveryAjax')->name('admin.showDeliveryAjax');
    Route::get('/admin/delivery/{codeUrl}', 'AdminController@showDelivery')->name('admin.showDelivery');
    Route::post('/admin/countDeliveries', 'AdminController@countDeliveries')->name('admin.countDeliveries');
    Route::post('/admin/deliverySendCode', 'AdminController@deliverySendCode')->name('admin.deliverySendCode');
    Route::post('/admin/deliverySendCodeManual', 'AdminController@deliverySendCodeManual')->name('admin.deliverySendCodeManual');
    Route::post('/admin/saveAlarm', 'AdminController@saveAlarm')->name('admin.saveAlarm');
    Route::post('/admin/verifyAlarm', 'AdminController@verifyAlarm')->name('admin.verifyAlarm');
    Route::get('/admin/tasa', 'AdminController@showRate')->name('admin.showRate');
    Route::post('/admin/tasa', 'AdminController@showRate')->name('admin.showRatePost');
    Route::post('/admin/nuevoTasa', 'AdminController@newRate')->name('admin.newRate');
    Route::get('/admin/autorizado', 'AdminController@authDelivery')->name('admin.authDelivery');
    Route::post('/admin/autorizado', 'AdminController@changeStatusDelivery')->name('admin.changeStatusDelivery');
    Route::get('/admin/configuraciones', 'AdminController@settings')->name('admin.settings');
    Route::post('/admin/settingsSchedule', 'AdminController@settingsSchedule')->name('admin.settingsSchedule');
    Route::post('/admin/settingsEmails', 'AdminController@settingsEmails')->name('admin.settingsEmails');
});

Route::post('/admin/removePicture', 'AdminController@removePicture')->name('admin.removePicture');
Route::post('admin/saveDeposits', 'AdminController@saveDeposits')->name('admin.saveDeposits');

Route::get('password/create', 'Auth\PasswordResetController@create');
Route::get('password/find/{token}', 'Auth\PasswordResetController@find');
Route::post('password/reset', 'Auth\PasswordResetController@reset')->name('form.passwordReset');

Route::get('password/delivery/create', 'Auth\PasswordResetController@createDelivery');
Route::get('password/delivery/find/{token}', 'Auth\PasswordResetController@findDelivery');
Route::post('password/delivery/reset', 'Auth\PasswordResetController@resetDelivery')->name('form.passwordResetDelivery');

Route::get('/pagar/completado/{userUrl}', function ($userUrl) {
    $status = false;
    return view('result', compact('userUrl', 'status'));
});

Route::get('/pagar/estadoPaypal', 'PaidController@statusPaypal');
Route::post('/pagar/criptomonedas', 'PaidController@cryptocurrencies');

Route::get('facturacion/{codeUrl}/', 'PaidController@billing');

Route::get('/{userUrl}/', 'SaleController@indexStore')->name('form.store');
Route::get('/{userUrl}/{codeUrl}/{statusModification?}', 'SaleController@index');
Route::post('verify', 'SaleController@verifyDiscount');
Route::post('showCategories', 'SaleController@showCategories')->name('show.categories');
Route::post('showProductsServices', 'SaleController@showProductsServices')->name('show.productsServices');

Route::post('update', 'Auth\PasswordResetController@updatePassword')->name('user.updatePassword');
Route::post('pagar', 'PaidController@formSubmit')->name('form.formSubmit');
Route::post('newSales', 'SaleController@new')->name('sale.newSale');
Route::post('modifysale', 'SaleController@modifysale')->name('sale.modifysale');
Route::post('removeSale', 'SaleController@removeSale')->name('sale.removeSale');