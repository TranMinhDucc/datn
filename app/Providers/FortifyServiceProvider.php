<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use App\Actions\Fortify\ResetPasswordViewResponse as CustomResetPasswordViewResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CustomLoginValidation;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ResetPasswordViewResponse::class, CustomResetPasswordViewResponse::class);
    }

    public function boot()
    {
        // ✅ Override login logic
        Fortify::authenticateUsing(function (Request $request) {

            // 🟡 Gọi validate thủ công để áp dụng rules và messages trong CustomLoginValidation
            (new CustomLoginValidation)($request);

            // ✅ Tìm user theo email
            $user = User::where('email', $request->email)->first();

            // ✅ Kiểm tra mật khẩu
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // ❌ Nếu không đúng, Laravel sẽ tự redirect lại form và bạn có thể xử lý thông báo lỗi tại đó (nếu cần)
            return null;
        });

        // ✅ Custom view cho Reset Password
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
    }
}
