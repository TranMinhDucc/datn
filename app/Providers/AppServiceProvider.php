<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use App\Actions\Fortify\ResetUserPassword;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Actions\Fortify\CustomLoginValidation;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Requests\CustomResetPasswordRequest;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
// Removed import for non-existent ResetPasswordRequest
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use App\Actions\Fortify\LoginResponse as CustomLoginResponse;
use App\Actions\Fortify\RegisterResponse as CustomRegisterResponse;
use App\Actions\Fortify\ResetPasswordResponse as CustomResetPasswordResponse;
use App\Actions\Fortify\ResetPasswordViewResponse as CustomResetPasswordViewResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(ResetsUserPasswords::class, ResetUserPassword::class);
        // Removed binding for ResetPasswordRequest as it does not exist in Fortify
    }

    public function boot()
    {
        Paginator::useBootstrapFive();
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        Fortify::loginView(fn() => view('client.auth.login'));
        Fortify::registerView(fn() => view('client.auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('client.auth.request-reset-password'));
        Fortify::verifyEmailView(fn() => view('client.auth.verify-email'));

        $this->app->singleton(
            ResetPasswordViewResponse::class,
            CustomResetPasswordViewResponse::class
        );

        Fortify::authenticateUsing(function (Request $request) {
            app(CustomLoginValidation::class)($request);
            return User::where('email', $request->email)->first();
        });

        // ✅ Chia sẻ cho tất cả view
       View::composer('*', function ($view) {
    $headerMenus = Menu::with('children')
                        ->where('position', 'header')
                        ->where('active', 1)
                        ->whereNull('parent_id')
                        ->orderBy('sort_order')
                        ->get();

    $footerMenus = Menu::with('children')
                        ->where('position', 'footer')
                        ->where('active', 1)
                        ->whereNull('parent_id')
                        ->orderBy('sort_order')
                        ->get();

    $sidebarMenus = Menu::with('children')
                         ->where('position', 'sidebar')
                         ->where('active', 1)
                         ->whereNull('parent_id')
                         ->orderBy('sort_order')
                         ->get();

    $view->with(compact('headerMenus', 'footerMenus', 'sidebarMenus'));
});


        // ✅ Kiểm tra bảng settings có tồn tại không trước khi truy cập
        if (Schema::hasTable('settings')) {
            $settings = Cache::remember('global_settings', 3600, function () {
                return Setting::all()->pluck('value', 'name');
            });

            View::share('settings', $settings);
        }
    }
}
