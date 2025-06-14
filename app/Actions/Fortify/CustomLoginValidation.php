<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Fortify;

class CustomLoginValidation
{
    public function __invoke(Request $request)
    {
        $rules = [
            Fortify::username() => [
                'required',
                'string',
                'email',
                'max:255',
                'exists:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
            ],
        ];

        $messages = [
            // ✅ Ghi đè đầy đủ message, Laravel sẽ dùng những dòng này thay vì lang/vi/validation.php

            'email.required' => 'Vui lòng nhập email.',
            'email.string' => 'Email không hợp lệ.',
            'email.email' => 'Email phải đúng định dạng (ví dụ: example@gmail.com).',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.exists' => 'Email này chưa được đăng ký trong hệ thống.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu không hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ in hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();
    }
}
