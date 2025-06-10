<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\ResetPasswordResponse as ResetPasswordResponseContract;

class ResetPasswordResponse implements ResetPasswordResponseContract
{
    public function toResponse($request)
    {
        return redirect()->route('login')->with([
            'success' => 'Mật khẩu đã được cập nhật thành công!',
            'action' => 'reset'
        ]);
    }
}
