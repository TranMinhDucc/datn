<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use App\Actions\Fortify\ResetPasswordViewResponse as CustomResetPasswordViewResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ResetPasswordViewResponse::class, CustomResetPasswordViewResponse::class);
    }

    public function boot()
    {
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                session()->flash('login_error', 'Email không tồn tại.');
                return null;
            }

            if (!Hash::check($request->password, $user->password)) {
                session()->flash('login_error', 'Mật khẩu không đúng.');
                return null;
            }

            return $user;
        });
    }
}
