<?php

Route::redirect('/', '/products')->name('root');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/email_verification/send', 'EmailVerificationController@send')->name('email_verification.send');
    Route::get('/email_verify_notice', 'PagesController@emailVerifyNotice')->name('email_verify_notice');
    Route::get('/email_verification/verify', 'EmailVerificationController@verify')->name('email_verification.verify');

});

Route::group(['middleware' => ['auth', 'email_verified']], function() {
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

});

Route::get('pages/error', function (){
    $msg = request('msg');
   return view('pages.error',compact('msg'));
})->name('pages.error');

Route::get('products', 'ProductsController@index')->name('products.index');