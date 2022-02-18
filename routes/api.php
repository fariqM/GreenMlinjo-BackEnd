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


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('test', 'Api\ProductController@promo_section');

    Route::prefix('/products')->group(function () {
        Route::get('promo-section', 'Api\ProductController@promo_section');
        Route::get('package/{id}', 'Api\ProductController@package');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('/v1')->group(function () {
            Route::get('test', 'Api\ProductController@promo_section');

            Route::prefix('favourites')->group(function () {
                Route::get('my-favourites', 'Api\FavouriteController@myfavourite');
                Route::get('favourite-products', 'Api\FavouriteController@favouriteProducts');
                Route::post('add-favourites', 'Api\FavouriteController@addfavourite');
                Route::delete('remove-favourite/{favourite:id}', 'Api\FavouriteController@removeFavourite');
            });

            Route::prefix('carts')->group(function(){
                Route::get('my-carts', 'Api\CartController@mycarts');
                Route::get('cart-products', 'Api\CartController@cartProducts');
                Route::post('add-carts', 'Api\CartController@addcart');
                Route::delete('remove-carts', 'Api\CartController@removeCart');
            });

            Route::prefix('products')->group(function () {
                Route::get('promo-section', 'Api\ProductController@promo_section');
                Route::get('package/{id}', 'Api\ProductController@package');
            });

            Route::get('inspect', 'Api\AuthController@inspeksi');
            Route::post('logout', 'Api\AuthController@logout');
        });
    });

    Route::get('users', 'Api\AuthController@index');
    Route::post('login', 'Api\AuthController@login');
    Route::get('products',  'Api\ProductController@index');
});
