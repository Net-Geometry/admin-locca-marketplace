<?php

use App\Enums\ViewPaths\Admin\Category;
use App\Http\Controllers\Admin\Item\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('get-all', [CategoryController::class, 'getNameList'])->name('get-all');
        Route::group(['middleware' => ['module:category']], function () {
            Route::get('', [CategoryController::class, 'index'])->name('add');
            Route::get('update/{id}', [CategoryController::class, 'getUpdateView'])->name('edit');
            Route::post('update/{id}', [CategoryController::class, 'update'])->name('update');
            Route::get('update-priority/{category}', [CategoryController::class, 'updatePriority'])->name('priority');
            Route::post(Category::ADD['uri'], [CategoryController::class, 'add'])->name('store');
            Route::get('status/{id}/{status}', [CategoryController::class, 'updateStatus'])->name('status');
            Route::get('featured/{id}/{featured}', [CategoryController::class, 'updateFeatured'])->name('featured');
            Route::delete('delete/{id}', [CategoryController::class, 'delete'])->name('delete');
            Route::get('export-categories', [CategoryController::class, 'exportData'])->name('export-categories');

            //Import and export
            Route::get(Category::BULK_IMPORT['uri'], [CategoryController::class, 'getBulkImportView'])->name('bulk-import');
            Route::post('bulk-import', [CategoryController::class, 'importBulkData']);
            Route::post('bulk-update', [CategoryController::class, 'updateBulkData'])->name('bulk-update');
            Route::get('bulk-export', [CategoryController::class, 'getBulkExportView'])->name('bulk-export-index');
            Route::post('bulk-export', [CategoryController::class, 'exportBulkData'])->name('bulk-export');
        });
    });

});
