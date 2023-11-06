<?php

use App\Enums\ViewPaths\Admin\Category;
use App\Http\Controllers\Admin\Item\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('get-all', 'CategoryController@get_all')->name('get-all');
        Route::group(['middleware' => ['module:category']], function () {
            Route::get('', [CategoryController::class, 'index'])->name('add');
            Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
            Route::post('update/{id}', 'CategoryController@update')->name('update');
            Route::get('update-priority/{category}', 'CategoryController@update_priority')->name('priority');
            Route::post(Category::ADD['uri'], [CategoryController::class, 'add'])->name('store');
            Route::get('status/{id}/{status}', [CategoryController::class,'updateStatus'])->name('status');
            Route::get('featured/{id}/{featured}',  [CategoryController::class,'updateFeatured'])->name('featured');
            Route::delete('delete/{id}', 'CategoryController@delete')->name('delete');
            Route::get('export-categories', 'CategoryController@export_categories')->name('export-categories');

            //Import and export
            Route::get('bulk-import', 'CategoryController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'CategoryController@bulk_import_data');
            Route::get('bulk-export', 'CategoryController@bulk_export_index')->name('bulk-export-index');
            Route::post('bulk-export', 'CategoryController@bulk_export_data')->name('bulk-export');
        });
    });

});
