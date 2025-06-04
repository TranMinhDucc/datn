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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::verifyEmailView(fn() => view('client.auth.verify-email'));

        // ✅ Cho phép đăng nhập bằng email hoặc username
        Fortify::authenticateUsing(function (Request $request) {
                $login = $request->input('email'); // Có thể là email hoặc username
    $user = User::where('email', $login)
                ->orWhere('username', $login)
                ->first();

    if (!$user) {
        throw ValidationException::withMessages([
            'email' => ['Tài khoản không tồn tại.'],
        ]);
    }

    if (!Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'password' => ['Mật khẩu không đúng.'],
        ]);
    }

    return $user;
        });



        // Đăng ký các service liên quan
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);
        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);
    }
}
