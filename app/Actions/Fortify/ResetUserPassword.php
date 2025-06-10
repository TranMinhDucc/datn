<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetUserPassword implements ResetsUserPasswords
{
    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        // ✅ Băm mật khẩu trước khi lưu
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
