<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\App\Http\Controllers\MediaController;
use Modules\Product\Http\Controllers\CategoryController;
use Modules\Product\Http\Controllers\ProductController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/
Route::name('api')->prefix('admin')->middleware('auth:admin-api')->group(function() {

    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('products',ProductController::class);
    
    Route::delete('media/{media}',[MediaController::class, 'destroy']);
    
});
Route::name('api')->prefix('front')->group(function() {

    Route::get('/products', [\Modules\Product\Http\Controllers\Front\ProductController::class,'index']);
    Route::get('/products/{product}', [\Modules\Product\Http\Controllers\Front\ProductController::class,'show']);
    Route::get('/categories', [\Modules\Product\Http\Controllers\Front\CategoryController::class,'index']);
});