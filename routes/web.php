<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\PaymentBankController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\OrderController;

// Giao diện client
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
    // Trang tổng quan
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');



    // User
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        // Route::get('/create', [UserController::class, 'create'])->name('create');
        // Route::post('/', [UserController::class, 'store'])->name('store');
        // Route::get('/{user}', [UserController::class, 'show'])->name('show');
        // Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        // Route::put('/{user}', [UserController::class, 'update'])->name('update');
        // Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        // Route::get('/create', [CategoryController::class, 'create'])->name('create');
        // Route::post('/', [CategoryController::class, 'store'])->name('store');
        // Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        // Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        // Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        // Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        // Route::get('/create', [PostController::class, 'create'])->name('create');
        // Route::post('/', [PostController::class, 'store'])->name('store');
        // Route::get('/{post}', [PostController::class, 'show'])->name('show');
        // Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        // Route::put('/{post}', [PostController::class, 'update'])->name('update');
        // Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        // Route::get('/create', [ProductController::class, 'create'])->name('create');
        // Route::post('/', [ProductController::class, 'store'])->name('store');
        // Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        // Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        // Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        // Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        // Route::get('/create', [ReviewController::class, 'create'])->name('create');
        // Route::post('/', [ReviewController::class, 'store'])->name('store');
        // Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
        // Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('edit');
        // Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
        // Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // Payment Banks
    Route::prefix('payment-banks')->name('payment_banks.')->group(function () {
        Route::get('/', [PaymentBankController::class, 'index'])->name('index');
        // Route::get('/create', [PaymentBankController::class, 'create'])->name('create');
        // Route::post('/', [PaymentBankController::class, 'store'])->name('store');
        // Route::get('/{payment_bank}', [PaymentBankController::class, 'show'])->name('show');
        // Route::get('/{payment_bank}/edit', [PaymentBankController::class, 'edit'])->name('edit');
        // Route::put('/{payment_bank}', [PaymentBankController::class, 'update'])->name('update');
        // Route::delete('/{payment_bank}', [PaymentBankController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('banners')->name('banners.')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('index');
    // Route::get('/create', [BannerController::class, 'create'])->name('create');
    // Route::post('/', [BannerController::class, 'store'])->name('store');
    // Route::get('/{id}', [BannerController::class, 'show'])->name('show');
    // Route::get('/{id}/edit', [BannerController::class, 'edit'])->name('edit');
    // Route::put('/{id}', [BannerController::class, 'update'])->name('update');
    // Route::delete('/{id}', [BannerController::class, 'destroy'])->name('destroy');
});

// Status routes
Route::prefix('statuses')->name('statuses.')->group(function () {
    Route::get('/', [StatusController::class, 'index'])->name('index');
    // Route::get('/create', [StatusController::class, 'create'])->name('create');
    // Route::post('/', [StatusController::class, 'store'])->name('store');
    // Route::get('/{id}', [StatusController::class, 'show'])->name('show');
    // Route::get('/{id}/edit', [StatusController::class, 'edit'])->name('edit');
    // Route::put('/{id}', [StatusController::class, 'update'])->name('update');
    // Route::delete('/{id}', [StatusController::class, 'destroy'])->name('destroy');
});
// Order routes
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    // Route::get('/create', [OrderController::class, 'create'])->name('create');
    // Route::post('/', [OrderController::class, 'store'])->name('store');
    // Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    // Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
    // Route::put('/{id}', [OrderController::class, 'update'])->name('update');
    // Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
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
