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
    });

    Route::group(['namespace' => 'Merchant', 'prefix' => 'merchant', 'middleware' => ['auth:api', 'isMerchant']], function () { 
        Route::resource('store', 'StoreController')->except(['index', 'create', 'edit']);
        Route::get('store', 'StoreController@getMyStore');
    });
});
