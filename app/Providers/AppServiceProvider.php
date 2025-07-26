<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
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
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use App\Actions\Fortify\LoginResponse as CustomLoginResponse;
use App\Actions\Fortify\RegisterResponse as CustomRegisterResponse;
use App\Actions\Fortify\ResetPasswordResponse as CustomResetPasswordResponse;
use App\Actions\Fortify\ResetPasswordViewResponse as CustomResetPasswordViewResponse;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchHistory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(ResetsUserPasswords::class, ResetUserPassword::class);
    }

    public function boot()
    {
        Paginator::useBootstrapFive();
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

Fortify::loginView(fn () => view('client.auth.login'));
Fortify::registerView(fn () => view('client.auth.register'));
Fortify::requestPasswordResetLinkView(fn () => view('client.auth.request-reset-password'));
Fortify::verifyEmailView(fn () => view('client.auth.verify-email'));

View::composer('*', function ($view) {
    $popularSearches = SearchHistory::orderByDesc('count')->limit(10)->get();
    $view->with('popularSearches', $popularSearches);
});

        $this->app->singleton(
            ResetPasswordViewResponse::class,
            CustomResetPasswordViewResponse::class
        );

        Fortify::authenticateUsing(function (Request $request) {
            app(CustomLoginValidation::class)($request);
            return User::where('email', $request->email)->first();
        });

        // ✅ Composer menus (header, footer, sidebar)
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

        // ✅ Kiểm tra bảng settings tồn tại rồi chia sẻ
        if (Schema::hasTable('settings')) {
            $settings = Cache::remember('global_settings', 3600, function () {
                return Setting::all()->pluck('value', 'name');
            });

            View::share('settings', $settings);
        }

        // ✅ Composer thông báo chưa đọc
        View::composer('*', function ($view) {
            $notifications = collect();

            if (Auth::check()) {
                $notifications = DatabaseNotification::where('notifiable_id', Auth::id())
                    ->where('notifiable_type', \App\Models\User::class)
                    ->whereNull('read_at')
                    ->get();
            }

            $view->with('unreadNotifications', $notifications);
        });
    }
}