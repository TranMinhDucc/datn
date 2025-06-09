<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

// ========== Client Controllers ==========
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\WishlistController;

// ========== Admin Controllers ==========
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

use Illuminate\Foundation\Auth\EmailVerificationRequest;

// ========== PUBLIC CLIENT ROUTES ==========
Route::prefix('/')->name('client.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/login', 'login')->name('login');
        Route::get('/register', 'register')->name('register');
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
});

// ========== PROTECTED ROUTES ==========
Route::middleware(['auth', 'verified'])->prefix('account')->name('client.account.')->group(function () {
    Route::get('/wallet', [HomeController::class, 'wallet'])->name('wallet');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::get('/change-password', [AccountController::class, 'changePasswordForm'])->name('change_password');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change_password.submit');
});

// ========== PASSWORD RESET ==========
Route::get('/forgot-password', function () {
    return view('client.auth.request-reset-password');
})->middleware('guest')->name('client.auth.reset_password');

// ========== EMAIL VERIFICATION ==========
Route::get('/email/verify', function () {
    if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
        return redirect()->route('client.home');
    }
    return view('client.auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = User::findOrFail($request->route('id'));
    $expectedHash = sha1($user->getEmailForVerification());

    if (!hash_equals((string) $request->route('hash'), $expectedHash)) {
        abort(403, 'Liên kết xác minh không hợp lệ.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    Auth::login($user);

    return redirect()->route('client.home')->with('success', 'Xác minh email thành công!');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Email xác minh đã được gửi lại!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ========== ADMIN ROUTES ==========
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('banners', BannerController::class);
    Route::post('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');

    // Admin Content
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    Route::resource('post-categories', PostCategoryController::class);
    Route::resource('faq', FaqController::class);
    Route::put('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');

    // Marketing
    Route::resource('coupons', CouponController::class);

    // System Settings
    // Route::get('/settings/language', [SettingController::class, 'language'])->name('admin.settings.language');
    // Route::get('/settings/currency', [SettingController::class, 'currency'])->name('admin.settings.currency');
    // Route::get('/settings/theme', [SettingController::class, 'theme'])->name('admin.settings.theme');
    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');

    // product crud
    Route::resource('products', ProductController::class)->names('admin.products');
//reviews crud
   Route::resource('reviews', ReviewController::class)->names('admin.reviews');

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
    // Tag
    Route::resource('tags', TagController::class);
});
