<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\LoginResponse as CustomLoginResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Actions\Fortify\RegisterResponse as CustomRegisterResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use App\Actions\Fortify\ResetPasswordViewResponse as CustomResetPasswordViewResponse;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\CustomLoginValidation;
use App\Actions\Fortify\ResetPasswordResponse as CustomResetPasswordResponse;
// Removed import for non-existent ResetPasswordRequest
use App\Http\Requests\CustomResetPasswordRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

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
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        // Gán view cho các bước Fortify
        Fortify::loginView(fn() => view('client.auth.login'));
        Fortify::registerView(fn() => view('client.auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('client.auth.request-reset-password'));
        Fortify::verifyEmailView(fn() => view('client.auth.verify-email'));
        // Gán view reset mật khẩu từ token (bắt buộc để fix lỗi)
        $this->app->singleton(
            ResetPasswordViewResponse::class,
            CustomResetPasswordViewResponse::class
        );
        // Custom xác thực
        Fortify::authenticateUsing(function (Request $request) {
            app(CustomLoginValidation::class)($request);
            return User::where('email', $request->email)->first();
        });
        // ✅ Auto load settings và cache trong 1 giờ
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::all()->pluck('value', 'name');
        });

        // ✅ Chia sẻ cho tất cả view
        View::share('settings', $settings);
    }
}
