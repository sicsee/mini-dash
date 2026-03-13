<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StockController;

use Illuminate\Support\Facades\Route;

Route::get("/", [SiteController::class,"index"])->name('site.index');

Route::get('/register', [RegisterController::class, 'index'])->name('site.register');
Route::post('/register', [RegisterController::class, 'store'])->name('auth.register');

Route::get('/login', [LoginController::class, 'index'])->name('site.login');
Route::post('/login',  [LoginController::class, 'authenticate'])->name('auth.login');



Route::middleware('auth')->group(function () {
    Route::post('/logout',[LoginController::class, 'logout'])->name('auth.logout');


    Route::get('/dashboard', [SiteController::class, 'dashboard'])->name('site.dashboard');


    Route::resource('/dashboard/products', ProductController::class)->except('create', 'show', 'edit');
    Route::resource('/dashboard/stocks', StockController::class)->except('create', 'show', 'edit', 'destroy');
    Route::resource('/dashboard/customers', CustomerController::class)->except('create', 'show', 'edit');
    Route::resource('/dashboard/sales', SaleController::class)->except('create','show','edit');
});