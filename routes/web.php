<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\HomeController;
// use App\Http\Controllers\Client\ProductController;
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
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\BrandController;


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

// Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
Route::prefix('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Main
    // Route::get('/history', [HistoryController::class, 'index'])->name('admin.history');
    // Route::get('/automation', [AutomationController::class, 'index'])->name('admin.automation');

    // Security
    // Route::get('/ip-blocks', [IpBlockController::class, 'index'])->name('admin.ip-block');

    // Banner
    Route::resource('banners', BannerController::class)->names('admin.banners');
    // Products & Services
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('products', ProductController::class)->names('admin.products');
    // Route::get('/products/api', [ApiConnectionController::class, 'index'])->name('admin.api');
    // Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
    // Route::get('/accounts/sold', [AccountController::class, 'sold'])->name('admin.accounts.sold');
    // Route::get('/accounts/in-stock', [AccountController::class, 'stock'])->name('admin.accounts.stock');

    // Users & Roles
    Route::resource('users', UserController::class)->names('admin.users');
    // Route::resource('roles', RoleController::class)->names('admin.roles');

    // Topup & Campaigns
    // Route::get('/topups', [TopupController::class, 'index'])->name('admin.topups');
    // Route::get('/affiliates', [AffiliateController::class, 'index'])->name('admin.affiliates');
    // Route::get('/campaigns', [CampaignController::class, 'index'])->name('admin.campaigns');

    // Marketing
    Route::resource('coupons', CouponController::class)->names('admin.coupons');
    // Route::resource('promotions', PromoController::class)->names('admin.promotions');
    // Route::resource('posts', PostController::class)->names('admin.posts');

    // System Settings
    // Route::get('/settings/language', [SettingController::class, 'language'])->name('admin.settings.language');
    // Route::get('/settings/currency', [SettingController::class, 'currency'])->name('admin.settings.currency');
    // Route::get('/settings/theme', [SettingController::class, 'theme'])->name('admin.settings.theme');
    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::resource('brands', BrandController::class)->names('admin.brands');
});