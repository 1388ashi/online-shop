<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\AddressController;
use Modules\Customer\Http\Controllers\Admin\CustomerController as admin;
use Modules\Customer\Http\Controllers\CustomerController as customer;

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
        Route::get('/customers', [admin::class, 'index']);
        Route::get('/customers/{customer}', [admin::class, 'show']);
        Route::delete('/customers/{customer}', [admin::class, 'destroy']);
    });

    Route::name('api')->prefix('customer')->middleware('auth:customer-api')->group(function() {
        
        Route::apiResource('addresses', AddressController::class);
        //profile
        Route::get('/profile', [customer::class,'profile']);
        Route::patch('/profile/{customer}',[customer::class,'update']);
        
        Route::put('/changepassword/{customer}',[customer::class,'changePassword']);
    });
