<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaundryController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/login');

Route::get('login',     [LoginController::class, 'showLoginForm']);
Route::post('login',    [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard',     DashboardController::class)->name('dashboard');

    Route::post('laundries/{laundry}/status',   [LaundryController::class, 'status'])->name('laundries.status');
    Route::resource('laundries',                LaundryController::class);

    Route::post('logout',   [LoginController::class, 'logout'])->name('logout');
});
