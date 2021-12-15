<?php

use App\Http\Controllers\Api\V1\Customer\HomeController;
use App\Http\Controllers\Api\V1\Customer\LaundryController as CustomerLaundryController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\Owner\{
    CatalogController,
    EmployeeController,
    HomeController as OwnerHomeController,
    LaundryController,
    OperationalHourController,
    ParfumeController,
    ShippingRateController,
    TransactionController
};
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('v1/login',     [LoginController::class, 'login']);
Route::post('v1/register',  [RegisterController::class, 'register']);
Route::post('v1/logout',    [LoginController::class, 'logout']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'v1'], function () {
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

        // Managing Service Catalog...
        Route::get('laundries/{laundry}/catalogs',               [CatalogController::class, 'index']);
        Route::post('laundries/{laundry}/catalogs',              [CatalogController::class, 'store']);
        Route::put('laundries/{laundry}/catalogs/{catalog}',     [CatalogController::class, 'update']);
        Route::delete('laundries/{laundry}/catalogs/{catalog}',  [CatalogController::class, 'destroy']);

        // Managing Shipping Rate...
        Route::get('laundries/{laundry}/shipping',                         [ShippingRateController::class, 'index']);
        Route::post('laundries/{laundry}/shipping',                        [ShippingRateController::class, 'store']);
        Route::delete('laundries/{laundry}/shipping/{shippingRate}',       [ShippingRateController::class, 'destroy']);

        // Update Profile Laundry
        Route::put('laundries/{laundry}/update',                 [LaundryController::class, 'update']);

        // Managing Operational Hour
        Route::get('laundries/{laundry}/operationalhour',        [OperationalHourController::class, 'index']);

        // Home Owner and Employee
        Route::get('laundries/{laundry}/home',      OwnerHomeController::class);

        // Update Status Transaction
        Route::post('laundries/{laundry}/transaction/{transaction}',    [TransactionController::class, 'update']);
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('laundries',         [HomeController::class, 'index']);
        Route::get('laundries/transaction',         [CustomerLaundryController::class, 'index']);
        Route::post('laundries/{laundry}/store',    [CustomerLaundryController::class, 'store']);
    });
});
