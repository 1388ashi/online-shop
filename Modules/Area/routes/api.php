<?php

use Illuminate\Support\Facades\Route;
use Modules\Area\Http\Controllers\Admin\CityController;
use Modules\Area\Http\Controllers\Admin\ProvinceController;


Route::name('api')->prefix('admin')->middleware('auth:admin-api')->group(function() {
    Route::apiResource('provinces', ProvinceController::class)->only(['index']);
    Route::apiResource('cities', CityController::class);
});



