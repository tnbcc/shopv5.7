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

    //收藏取消商品
    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');

    //收藏商品列表
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');

    //添加到购物车
    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');

    Route::get('orders', 'OrdersController@index')->name('orders.index');
    //添加订单
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');

    //支付宝付款
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    //支付宝前端回调
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
    //微信支付
    Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
});

Route::get('pages/error', function (){
    $msg = request('msg');
   return view('pages.error',compact('msg'));
})->name('pages.error');

Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');