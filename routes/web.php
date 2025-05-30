<?php

use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('client.home');
    Route::get('/about', 'about')->name('client.about');
    Route::get('/contact', 'contact')->name('client.contact');
    Route::get('/faq', 'faq')->name('client.faq');
});
