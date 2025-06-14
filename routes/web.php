<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// ========== CLIENT CONTROLLERS ==========
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;

// ========== ADMIN CONTROLLERS ==========
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\PaymentBankController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SigninController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\VariantAttributeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductLabelController;
// GHI ĐÈ route đăng ký Fortify
Route::post('/register', [RegisterController::class, 'store'])->name('register');
// ========== PUBLIC CLIENT ROUTES ==========

Route::prefix('/')->name('client.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/policy', 'policy')->name('policy');
        Route::get('/faq', 'faq')->name('faq');
    });

    Route::controller(ContactController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(ClientProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{slug}', 'show')->name('show');
    });

    Route::controller(BlogController::class)->prefix('blog')->name('blog.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{slug}', 'show')->name('show');
    });

    Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show', 'show')->name('show');
    });

    Route::controller(WishlistController::class)->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', 'index')->name('index');
    });


Route::post('/review', [ClientReviewController::class, 'store']) ->middleware('auth') ->name('review');

});

// ========== PROTECTED ROUTES ==========
Route::middleware(['auth', 'verified'])->prefix('account')->name('client.account.')->group(function () {
    Route::get('/wallet', [HomeController::class, 'wallet'])->name('wallet');
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [AccountController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [AccountController::class, 'update'])->name('profile.update');

    Route::get('/change-password', [AccountController::class, 'changePasswordForm'])->name('change_password');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change_password.submit');
});

// ========== LOGOUT ==========

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with([
        'success' => 'Đăng xuất thành công!',
        'action' => 'logout' // 👈 Thêm dòng này để JS biết đây là hành động đăng xuất
    ]);
})->name('logout');

// ========== EMAIL VERIFICATION ==========
Route::get('/email/verify', function () {
    return view('client.auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('client.home')->with('success', 'Xác minh email thành công!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Email xác minh đã được gửi lại!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ========== ADMIN ROUTES ==========
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('banners', BannerController::class);
    Route::post('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    Route::resource('post-categories', PostCategoryController::class);
    Route::resource('faq', FaqController::class);
    Route::put('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');

    Route::resource('coupons', CouponController::class);

    // System Settings
    // Route::get('/settings/language', [SettingController::class, 'language'])->name('admin.settings.language');
    // Route::get('/settings/currency', [SettingController::class, 'currency'])->name('admin.settings.currency');
    // Route::get('/settings/theme', [SettingController::class, 'theme'])->name('admin.settings.theme');
    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');

    //reviews crud
    Route::resource('reviews', ReviewController::class)->names('reviews');
    
     Route::resource('badwords', \App\Http\Controllers\Admin\BadWordController::class);

   Route::resource('product-labels', ProductLabelController::class);


    // Route::resource('roles', RoleController::class)->names('admin.roles');

    // Topup & Campaigns
    // Route::get('/topups', [TopupController::class, 'index'])->name('admin.topups');
    // Route::get('/affiliates', [AffiliateController::class, 'index'])->name('admin.affiliates');
    // Route::get('/campaigns', [CampaignController::class, 'index'])->name('admin.campaigns');

    // Marketing
    // Route::resource('coupons', CouponController::class)->names('admin.coupons');
    // Route::resource('promotions', PromoController::class)->names('admin.promotions');
    // Route::resource('posts', PostController::class)->names('admin.posts');

    // System Settings
    // Route::get('/settings/language', [SettingController::class, 'language'])->name('admin.settings.language');
    // Route::get('/settings/currency', [SettingController::class, 'currency'])->name('admin.settings.currency');
    // Route::get('/settings/theme', [SettingController::class, 'theme'])->name('admin.settings.theme');
    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');

    Route::resource('brands', BrandController::class);
    Route::resource('tags', TagController::class);

    // Variant Attributes
    Route::resource('variant_attributes', VariantAttributeController::class);
    // Setting
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{id}', [SettingController::class, 'destroy'])->name('settings.destroy');
});
