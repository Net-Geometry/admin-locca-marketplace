<?php
use Illuminate\Support\Facades\Route;
use Modules\Rental\Http\Controllers\Web\Admin\BrandController;
use Modules\Rental\Http\Controllers\Web\Admin\CategoryController;
use Modules\Rental\Http\Controllers\Web\Admin\DashboardController;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['admin', 'current-module']], function () {
    Route::group(['prefix' => 'rental', 'as' => 'rental.'], function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('list', [CategoryController::class, 'list'])->name('list');
            Route::post('list', [CategoryController::class, 'store']);
            Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
            Route::post('edit/{id}', [CategoryController::class, 'update']);
            Route::delete('delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
            Route::get('status/{id}', [CategoryController::class, 'status'])->name('status');
            Route::get('status/{id}', [CategoryController::class, 'status'])->name('status');
            Route::get('export-categories', [CategoryController::class, 'export'])->name('export-categories');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
            Route::get('list', [BrandController::class, 'list'])->name('list');
            Route::post('list', [BrandController::class, 'store']);
            Route::get('edit/{id}', [BrandController::class, 'edit'])->name('edit');
            Route::post('edit/{id}', [BrandController::class, 'update']);
            Route::delete('delete/{id}', [BrandController::class, 'destroy'])->name('delete');
            Route::get('status/{id}', [BrandController::class, 'status'])->name('status');
            Route::get('status/{id}', [BrandController::class, 'status'])->name('status');
            Route::get('export-categories', [BrandController::class, 'export'])->name('export-brands');

        });
    });
});


