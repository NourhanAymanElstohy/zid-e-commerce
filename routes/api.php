<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('profile', 'profile');
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['middleware' => ['role:merchant']], function () {
        Route::get('list-merchant-stores', [StoreController::class, 'getMerchantStores']);
        Route::post('add-store', [StoreController::class, 'addStore']);
        Route::post('stores/{id}/set-name', [StoreController::class, 'setStoreName']);
        Route::delete('delete-store/{id}', [StoreController::class, 'deleteStore']);
        
        Route::post('add-product', [ProductController::class, 'addProduct']);
        Route::post('update-product/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('delete-product/{id}', [ProductController::class, 'deleteProduct']);
    });

    Route::group(['middleware' => ['role:consumer']], function () {
        Route::get('cart', [CartController::class, 'cartList']);
        Route::post('cart', [CartController::class, 'addToCart']);
        Route::post('update-cart', [CartController::class, 'updateCart']);
        Route::post('remove-product', [CartController::class, 'removeProductFromCart']);
        Route::post('clear-cart', [CartController::class, 'clearAllCart']);
        Route::get('get-total-cart', [CartController::class, 'getTotalCart']);
    });
});

Route::get('get-all-stores', [StoreController::class, 'getAllStores']);
Route::get('get-store-products/{id}', [StoreController::class, 'getStoreProducts']);
Route::get('all-products', [ProductController::class, 'getProducts']);
Route::get('get-product/{id}', [ProductController::class, 'getProduct']);
