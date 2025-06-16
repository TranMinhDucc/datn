<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomLoginValidation
{
    public function __invoke(Request $request)
    {
        $loginInput = $request->input('login');

        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Bắt đầu validate
        $rules = [
            'login' => [
                'required',
                'string',
                'max:255',
                $loginField === 'email'
                    ? 'email'
                    : 'regex:/^[a-zA-Z0-9_]{4,20}$/', // validate username theo định dạng
                "exists:users,{$loginField}",
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',     // ít nhất 1 chữ in hoa
                'regex:/[a-z]/',     // ít nhất 1 chữ thường
                'regex:/[0-9]/',     // ít nhất 1 số
                'regex:/[\W_]/',     // ít nhất 1 ký tự đặc biệt hoặc gạch dưới
            ],
        ];

        $messages = [
            'login.required' => 'Vui lòng nhập email hoặc tên đăng nhập.',
            'login.string' => 'Thông tin đăng nhập không hợp lệ.',
            'login.max' => 'Thông tin đăng nhập không được vượt quá 255 ký tự.',
            'login.email' => 'Email không đúng định dạng (ví dụ: example@gmail.com).',
            'login.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số, dấu gạch dưới và từ 4–20 ký tự.',
            'login.exists' => 'Tài khoản này chưa được đăng ký trong hệ thống.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu không hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ in hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();
    }
}
