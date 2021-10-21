<?php

use App\Http\Controllers\Api\V1\Admin\LaundryController;
use App\Http\Controllers\Api\V1\Owner\{
    CatalogController,
    EmployeeController,
    ParfumeController,
    ServiceController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'v1'], function () {
    Route::apiResource('laundries',     LaundryController::class);

    Route::group(['prefix' => 'owner'], function () {
        // Managing Employees...
        Route::get('laundries/{laundry}/employees',                         [EmployeeController::class, 'index']);
        Route::post('laundries/{laundry}/employees',                        [EmployeeController::class, 'store']);
        Route::put('laundries/{laundry}/employees/{employee}',              [EmployeeController::class, 'update']);
        Route::delete('laundries/{laundry}/employees/{employee}',           [EmployeeController::class, 'destroy']);

        // Managing Parfumes...
        Route::get('laundries/{laundry}/parfumes',                          [ParfumeController::class, 'index']);
        Route::post('laundries/{laundry}/parfumes',                         [ParfumeController::class, 'store']);
        Route::put('laundries/{laundry}/parfumes/{parfume}',                [ParfumeController::class, 'update']);
        Route::delete('laundries/{laundry}/parfumes/{parfume}',             [ParfumeController::class, 'destroy']);

        // Managing Service...
        Route::get('laundries/{laundry}/services',                          [ServiceController::class, 'index']);
        Route::post('laundries/{laundry}/services',                         [ServiceController::class, 'store']);
        Route::put('laundries/{laundry}/services/{service}',                [ServiceController::class, 'update']);
        Route::delete('laundries/{laundry}/services/{service}',             [ServiceController::class, 'destroy']);

        // Managing Service Catalog...
        /**
         * TODO: Managing Service Catalog
         * Route::get('laundries/{laundry}/services/{service}/catalogs',               [CatalogController::class, 'index']);
         * Route::post('laundries/{laundry}/services/{service}/catalogs',              [CatalogController::class, 'store']);
         * Route::put('laundries/{laundry}/services/{service}/catalogs/{catalog}',     [CatalogController::class, 'update']);
         * Route::delete('laundries/{laundry}/services/{service}/catalogs/{catalog}',  [CatalogController::class, 'destroy']);
         */
    });
});
