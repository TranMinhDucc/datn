<?php
namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Nếu chưa xác minh → không cho vào hệ thống
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Bạn cần xác minh email trước khi sử dụng hệ thống.');
        }

        return redirect()->route('client.home')
            ->with('success', 'Đăng nhập thành công!');
    }
}

