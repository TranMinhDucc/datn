<?php

use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('client.home');
    Route::get('/about', 'about')->name('client.about');
    Route::get('/contact', 'contact')->name('client.contact');
    Route::get('/faq', 'faq')->name('client.faq');
    Route::get('/login', 'login')->name('client.login');
    Route::get('/reset-password', 'reset_password')->name('client.reset_password');
    Route::get('/register', 'register')->name('client.register');
    Route::get('/blogs', 'blogs')->name('client.blogs');
    Route::get('/wallet', 'wallet')->name('client.wallet');
    Route::get('/product_detail', 'productDetail')->name('client.product_detail');
});
