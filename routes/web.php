<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AccountController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\PaymentBankController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SigninController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


// ---------------------------
// ⚙️ PUBLIC ROUTES (ai cũng xem được)
// ---------------------------

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('client.home');
    Route::get('/login', 'login')->name('client.login');
    Route::get('/register', 'register')->name('client.register');
    Route::get('/policy', 'policy')->name('client.policy');
    Route::get('/contact', 'contact')->name('client.contact');
    Route::get('/faq', 'faq')->name('client.faq');
    Route::get('/blogs', 'blogs')->name('client.blogs');
    Route::get('/product_detail', 'productDetail')->name('client.product_detail');
});

// ---------------------------
// 🔐 PROTECTED ROUTES (phải đăng nhập + xác minh)
// ---------------------------

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/wallet', [HomeController::class, 'wallet'])->name('client.account.wallet');
    Route::get('/profile', [AccountController::class, 'profile'])->name('client.account.profile');
    Route::get('/change-password', [AccountController::class, 'changePasswordForm'])->name('client.account.change_password');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('client.account.change_password.submit');
});

// --------- 🌐 KHÔI PHỤC MẬT KHẨU ---------
Route::get('/forgot-password', function () {
    return view('client.auth.request-reset-password');
})->middleware('guest')->name('client.auth.reset_password');

// ---------------------------
// 📧 EMAIL VERIFICATION ROUTES
// ---------------------------

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

// ---------------------------
// 🛠 ADMIN ROUTES
// ---------------------------
// Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('banners', BannerController::class)->names('admin.banners');

    Route::post('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])
        ->name('admin.banners.toggle-status');
    // Products & Services


    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('products', ProductController::class)->names('admin.products');
    Route::resource('users', UserController::class)->names('admin.users');
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

});
