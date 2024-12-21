<?php

use Illuminate\Support\Facades\Route;
use Modules\Rental\Http\Controllers\Web\Provider\VehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([ 'middleware' => ['vendor']], function () {
    Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
        Route::get('list', [VehicleController::class, 'index'])->name('list');
        Route::get('create', [VehicleController::class, 'create'])->name('create');
        Route::post('create', [VehicleController::class, 'store']);
        Route::get('update/{id}', [VehicleController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [VehicleController::class, 'update']);
        Route::get('details/{id}', [VehicleController::class, 'details'])->name('details');
        Route::get('status/{id}', [VehicleController::class, 'status'])->name('status');
        Route::get('new-tag/{id}', [VehicleController::class, 'newTag'])->name('new-tag');
        Route::delete('delete/{id}', [VehicleController::class, 'destroy'])->name('delete');
        Route::get('export', [VehicleController::class, 'export'])->name('export');
    });
});


