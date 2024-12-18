<?php

use Illuminate\Support\Facades\Route;

use Modules\Rental\Http\Controllers\Api\Provider\BannerController;
use Modules\Rental\Http\Controllers\Api\Provider\DriverController;
use Modules\Rental\Http\Controllers\Api\Provider\ProviderController;
use Modules\Rental\Http\Controllers\Api\Provider\VehicleController;
use Modules\Rental\Http\Controllers\Api\Public\CouponController as Coupon;
use Modules\Rental\Http\Controllers\Api\Public\BannerController as Banner;
use Modules\Rental\Http\Controllers\Api\Public\VehicleController as Vehicle;
use Modules\Rental\Http\Controllers\Api\Public\VehicleCategoryController as VehicleCategory;
use Modules\Rental\Http\Controllers\Api\Public\ProviderController as Provider;

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

        Route::group(['prefix' => 'banner', 'as' => 'vehicle.'], function () {
            Route::get('list', [BannerController::class, 'list']);
            Route::post('create', [BannerController::class, 'store']);
            Route::post('update/{id}', [BannerController::class, 'update']);
            Route::get('status/{id}', [BannerController::class, 'status']);
            Route::get('featured/{id}', [BannerController::class, 'featured']);
            Route::delete('delete/{id}', [BannerController::class, 'destroy']);
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::get('list', [BannerController::class, 'list']);
            Route::post('create', [BannerController::class, 'store']);
            Route::post('update/{id}', [BannerController::class, 'update']);
            Route::get('status/{id}', [BannerController::class, 'status']);
            Route::get('featured/{id}', [BannerController::class, 'featured']);
            Route::delete('delete/{id}', [BannerController::class, 'destroy']);
        });

        Route::get('category/list', [ProviderController::class, 'categoryList']);
        Route::get('brand/list', [ProviderController::class, 'BrandList']);
    });
});

Route::group(['prefix' => 'rental', 'as' => 'rental.' , 'middleware'=>'localization'], function () {
    Route::get('coupon/list', [Coupon::class, 'list']);

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', [Banner::class, 'list']);
        // Route::get('{store_id}/', [Banner::class, 'getStoreBanners']);
    });
    Route::group(['prefix' => 'vehicle'], function () {
        Route::get('top-rated/', [Vehicle::class, 'topRatedVehicleList']);
        Route::get('search/', [Vehicle::class, 'getSearchedVehicles']);
        Route::get('search/suggestion', [Vehicle::class, 'getSearchedVehiclesSuggestion']);
        Route::get('get-provider-vehicles', [Vehicle::class, 'getProviderWiseVehicles']);
        Route::get('get-vehicle-details/{vehicle}', [Vehicle::class, 'getVehicleDetails']);

        Route::get('category-list/', [VehicleCategory::class, 'vehicleCategoryList']);
    });
    Route::group(['prefix' => 'provider'], function () {
        Route::get('get-provider-details/{provider}', [Provider::class, 'getProvidereDetails']);
        Route::get('get-provider-reviews/{provider}', [Provider::class, 'getProvidereReviews']);
    });
});
