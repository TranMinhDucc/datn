<?php

use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('client.home');
    Route::get('/policy', 'policy')->name('client.policy');
    Route::get('/contact', 'contact')->name('client.contact');
    Route::get('/faq', 'faq')->name('client.faq');
    Route::get('/login', 'login')->name('client.login');
    Route::get('/reset-password', 'reset_password')->name('client.reset_password');
    Route::get('/register', 'register')->name('client.register');
    Route::get('/blogs', 'blogs')->name('client.blogs');
    Route::get('/wallet', 'wallet')->name('client.wallet');
    Route::get('/product_detail', 'productDetail')->name('client.product_detail');
});

// Routes cho giao diện admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

Route::prefix('products')->name('client.products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('account')->name('client.account.')->group(function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::get('/wallet', [AccountController::class, 'wallet'])->name('wallet');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/password', [AccountController::class, 'password'])->name('password');
});
