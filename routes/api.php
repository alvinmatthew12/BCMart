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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'v1', 'prefix' => 'v1'], function(){
    Route::post('/login','AuthController@login');

    Route::group(['middleware' => ['auth:api']], function(){
        Route::post('/logout','AuthController@logout');
    });

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth:api', 'isAdmin']], function () { 
        Route::resource('store', 'StoreController')->except(['create', 'edit']);

        Route::resource('product', 'ProductController')->except(['create', 'edit']);
        Route::put('product/updateBarcode/{id}', 'ProductController@updateBarcode');

        Route::get('wallet', 'WalletController@getTopUp');
    });

    Route::group(['namespace' => 'Merchant', 'prefix' => 'merchant', 'middleware' => ['auth:api', 'isMerchant']], function () { 
        Route::resource('store', 'StoreController')->except(['index', 'create', 'edit']);
        Route::get('store', 'StoreController@getStores');

        Route::resource('product', 'ProductController')->except(['index','create', 'edit']);
        Route::post('product/search', 'ProductController@getProducts');
        Route::put('product/updateBarcode/{id}', 'ProductController@updateBarcode');

        Route::get('checkout/report/{id}', 'CheckoutController@getCheckoutReport');
        Route::get('checkout/{id}', 'CheckoutController@show');
    });

    Route::group(['namespace' => 'Member', 'prefix' => 'member', 'middleware' => ['auth:api']], function () { 
        Route::get('wallet', 'WalletController@index');
        Route::get('wallet/topup', 'WalletController@getTopUps');
        Route::post('wallet/topup', 'WalletController@topUp');

        Route::resource('cart', 'CartController')->except(['show', 'create', 'edit']);

        Route::post('checkout', 'CheckoutController@checkout');
        Route::get('checkout', 'CheckoutController@index');
        Route::get('checkout/{id}', 'CheckoutController@show');
    });
});
