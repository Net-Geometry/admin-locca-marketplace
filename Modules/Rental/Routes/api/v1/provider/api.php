<?php

use Illuminate\Support\Facades\Route;
use Modules\Rental\Http\Controllers\Api\Provider\DriverController;
use Modules\Rental\Http\Controllers\Api\Provider\ProviderController;
use Modules\Rental\Http\Controllers\Api\Provider\VehicleController;

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

Route::group(['prefix' => 'vendor', 'namespace' => 'Provider', 'middleware'=>['vendor.api']], function () {
    Route::group(['prefix' => 'rental', 'as' => 'rental.'], function () {
        Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
            Route::get('list', [DriverController::class, 'list']);
            Route::post('create', [DriverController::class, 'store']);
            Route::post('update/{id}', [DriverController::class, 'update']);
            Route::get('details/{id}', [DriverController::class, 'details']);
            Route::get('status/{id}', [DriverController::class, 'status']);
            Route::delete('delete/{id}', [DriverController::class, 'destroy']);
        });

        Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
            Route::get('list', [VehicleController::class, 'list']);
            Route::post('create', [VehicleController::class, 'store']);
            Route::post('update/{id}', [VehicleController::class, 'update']);
            Route::get('details/{id}', [VehicleController::class, 'details']);
            Route::get('status/{id}', [VehicleController::class, 'status']);
            Route::get('new-tag/{id}', [VehicleController::class, 'newTag']);
            Route::delete('delete/{id}', [VehicleController::class, 'destroy']);
        });

        Route::get('category/list', [ProviderController::class, 'categoryList']);
        Route::get('brand/list', [ProviderController::class, 'BrandList']);
    });
});
