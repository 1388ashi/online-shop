<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Admin\AuthController as admin;
use Modules\Auth\Http\Controllers\Customer\AuthController as customer;

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
Route::name('api')->prefix('admin')->middleware('guest')->group(function() {
    Route::post('/loginAdmin',[admin::class,'login'])->name('admin.login');

    Route::post('/login',[customer::class,'login'])->name('customer.login');
    Route::post('/registerLogin',[customer::class,'registerLogin']);
    Route::post('/register',[customer::class,'register']);
    Route::post('/sendToken',[customer::class,'sendToken']);
    Route::post('/verify',[customer::class,'verify']);
});


Route::name('api')->prefix('admin')->middleware('auth:admin-api')->group(function() {
    Route::post('/logout',[admin::class,'logout'])->name('admin.logout');
});
Route::name('api')->prefix('customer')->middleware('auth:customer-api')->group(function() {
    Route::post('/logout',[customer::class,'logout'])->name('customer.logout');

});
