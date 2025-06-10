<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return redirect()->route('login')->with([
            'success' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.',
            'action' => 'register' // 👈 Thêm dòng này để JS biết loại hành động
        ]);
    }
}
