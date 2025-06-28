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
use App\Http\Controllers\Client\BlogController as ClientBlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\FaqController as ClientFaqController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use App\Http\Controllers\Client\ShippingAddressController;
use App\Http\Controllers\Client\CouponController as ClientCouponController;

// ========== ADMIN CONTROLLERS ==========
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
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
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\VariantAttributeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductLabelController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\EmailCampaignController;

use App\Http\Controllers\Admin\ShippingFeeController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Webhook\BankWebhookController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Bank;
use App\Models\Setting;
use App\Services\BankTransactionService;

// GHI ĐÈ route đăng ký Fortify
Route::post('/register', [RegisterController::class, 'store'])->name('register');
// GHI ĐÈ route đăng nhập Fortify
Route::post('/login', [LoginController::class, 'login'])->name('login');
// GHI ĐÈ route đăng nhập Fortify
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ========== PUBLIC CLIENT ROUTES ==========

Route::prefix('/')->name('client.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/policy', 'policy')->name('policy');
        Route::get('/faq', 'faq')->name('faq');
    });

    Route::controller(ClientContactController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');       // Xử lý gửi liên hệ

    });


    Route::controller(ClientProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{slug}/', 'show')->name('show');
    });
    Route::controller(ClientContactController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');       // Xử lý gửi liên hệ

    });

    Route::controller(ClientBlogController::class)->prefix('blog')->name('blog.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{blog}', 'show')->name('show');
    });

    Route::get('/category/{id}', [ClientCategoryController::class, 'show'])->name('category.show');

    Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show', 'show')->name('show');
    });

    Route::controller(WishlistController::class)->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/place-order', 'placeOrder')->name('place-order');
    });

    Route::middleware(['auth'])->prefix('account')->name('client.')->group(function () {
        Route::get('/orders', [ClientOrderController::class, 'index'])->name('orders.index');
    });
    // Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
    //     Route::get('/', 'index')->name('index');
    // });



    Route::controller(ClientFaqController::class)->prefix('faq')->name('faq.')->group(function () {
        Route::get('/', 'index')->name('index');
    });


    Route::post('/review', [ClientReviewController::class, 'store'])->middleware('auth')->name('review');
});

Route::middleware(['auth', 'verified'])->prefix('account')->name('client.account.')->group(function () {
    Route::get('/wallet', [HomeController::class, 'wallet'])->name('wallet');
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [AccountController::class, 'edit'])->name('profile.edit');

    Route::get('/change-password', [AccountController::class, 'changePasswordForm'])->name('change_password');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change_password.submit');

    Route::middleware(['auth'])->prefix('address')->name('address.')->group(function () {
        Route::get('/', [ShippingAddressController::class, 'index'])->name('index');
        Route::post('/store', action: [ShippingAddressController::class, 'store'])->name('store');
        Route::get('{id}/edit', [ShippingAddressController::class, 'edit'])->name('edit');
        Route::put('{id}', [ShippingAddressController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ShippingAddressController::class, 'destroy'])->name('destroy');
        Route::post('/set-default/{id}', [ShippingAddressController::class, 'setDefault'])->name('setDefault');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    // UPDATE PROFILE
    Route::post('/profile/update', [AccountController::class, 'updateProfile'])->name('profile.update'); // ✅ Sửa ở đây
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change_password.submit');
    Route::post('/avatar', [AccountController::class, 'updateAvatar'])->name('avatar.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/apply-coupon', [ClientCouponController::class, 'apply'])->name('client.coupon.apply');
    Route::post('/remove-coupon', [ClientCouponController::class, 'remove'])->name('client.coupon.remove');

    // Tuỳ chọn: Gọi sau khi thanh toán thành công
    // Route::post('/finalize-coupon', [ClientCouponController::class, 'finalizeCouponUsage'])->name('client.coupon.finalize');
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
Route::prefix('admin')
    ->middleware(['auth', AdminMiddleware::class])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('banners', BannerController::class);
        Route::post('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
        Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class);

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);
        Route::resource('faq', FaqController::class);


        Route::resource('coupons', CouponController::class);
        //reviews crud
        Route::resource('reviews', ReviewController::class)->names('reviews');

        Route::resource('badwords', \App\Http\Controllers\Admin\BadWordController::class);



        Route::resource('product-labels', ProductLabelController::class);

        Route::resource('shipping-addresses', \App\Http\Controllers\Admin\ShippingAddressController::class);
        // Route::resource('roles', RoleController::class)->names('admin.roles');


        Route::resource('coupons', CouponController::class);
        // Marketing


        Route::get('/email-recipients', [EmailCampaignController::class, 'getRecipients'])->name('email_campaigns.recipients');
        Route::resource('email_campaigns', EmailCampaignController::class);


        //reviews crud
        Route::resource('reviews', ReviewController::class)->names('reviews');


        Route::resource('product-labels', ProductLabelController::class);

        Route::resource('badwords', \App\Http\Controllers\Admin\BadWordController::class);


        Route::resource('product-labels', ProductLabelController::class);


        Route::resource('brands', BrandController::class);
        Route::resource('tags', TagController::class);
        //Blog
        Route::resource('blogs', BlogController::class)->names('blogs');
        Route::post('blogs/generate-slug', [BlogController::class, 'generateSlug'])->name('blogs.generate-slug');
        Route::resource('blog-categories', BlogCategoryController::class)->names('blog-categories');
        //Shipping
        Route::resource('shipping-fees', ShippingFeeController::class)->except(['show'])->names('shipping-fees');
        Route::post('/shipping-zones/quick-add', [ShippingZoneController::class, 'quickAdd'])->name('shipping-zones.quick-add');
        Route::post('/shipping-methods/quick-add', [ShippingMethodController::class, 'quickAdd'])->name('shipping-methods.quick-add');


        Route::resource('brands', BrandController::class);
        Route::resource('tags', TagController::class);
        //Blog
        Route::resource('blogs', BlogController::class)->names('blogs');
        Route::post('blogs/generate-slug', [BlogController::class, 'generateSlug'])->name('blogs.generate-slug');
        Route::resource('blog-categories', BlogCategoryController::class)->names('blog-categories');


        // Variant Attributes
        Route::resource('variant_attributes', VariantAttributeController::class);
        // Setting
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{id}', [SettingController::class, 'destroy'])->name('settings.destroy');

        // Banking 
        Route::get('/recharge-bank', [BankController::class, 'view_payment'])->name('bank.view_payment');
        Route::get('/recharge-bank-config', [BankController::class, 'config'])->name('bank.config');
        Route::put('/recharge-bank-config', [BankController::class, 'config_update_two'])->name('bank.config_update_two');
        Route::post('/recharge-bank-config', [BankController::class, 'config_add'])->name('bank.config_add');
        Route::delete('/recharge-bank-config/{id}', [BankController::class, 'destroy'])->name('bank.destroy');
        Route::get('/recharge-bank-config/{id}/edit', [BankController::class, 'config_edit'])->name('bank.config_edit');
        Route::put('/recharge-bank-config/{id}/edit', [BankController::class, 'config_update'])->name('bank.config_update');
        Route::get('/create', [BankController::class, 'create'])->name('create');
        Route::post('/', [BankController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BankController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BankController::class, 'update'])->name('update');
        Route::delete('/{id}', [BankController::class, 'destroy'])->name('destroy');
    });

Route::get('/cron/sync-bank-transactions', function (Request $request) {
    $keyFromDb = Setting::where('name', 'cron_bank_security')->value('value');
    $keyFromRequest = $request->query('key');

    if ($keyFromRequest !== $keyFromDb) {
        abort(403, 'Không được phép.');
    }

    // Kiểm tra trạng thái Auto Bank
    $isAutoBankEnabled = Setting::where('name', 'bank_status')->value('value') === '1';
    if (!$isAutoBankEnabled) {
        return response('⛔ Auto Bank đang tắt, không xử lý giao dịch.', 200);
    }

    $banks = Bank::all();
    $service = new BankTransactionService();

    foreach ($banks as $bank) {
        $transactions = $service->fetchTransactionsFromWeb2M($bank);
        if ($transactions) {
            $service->processTransactions($transactions, $bank);
        }
    }

    return '✅ Đã chạy xong cron nạp tiền!';
});
