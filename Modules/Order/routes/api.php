<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

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

Route::name('api')->prefix('customer')->middleware('auth:customer-api')->group(function() {
    
    Route::get('/payments/drivers', [OrderController::class,'drivers']);
    Route::post('/parchase', [OrderController::class,'parchase'])->name('payments.parchase');
    
    Route::get('/orders', [OrderController::class,'index']);
    Route::get('/orders/{order}', [OrderController::class,'show']);

});
Route::name('api')->prefix('admin')->middleware('auth:admin-api')->group(function() {
    
    Route::get('/orders', [\Modules\Order\Http\Controllers\Admin\OrderController::class,'index']);
    Route::get('/orders/{order}', [\Modules\Order\Http\Controllers\Admin\OrderController::class,'show']);
    Route::get('/orders/update/{order}', [\Modules\Order\Http\Controllers\Admin\OrderController::class,'update']);

});
