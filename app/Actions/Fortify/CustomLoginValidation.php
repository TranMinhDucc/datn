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
                'exists:users,email'
            ],
            'password' => ['required', 'string'],
        ];

        $messages = [
            'email.required' => 'Vui lòng nhập email đăng nhập.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email này chưa được đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();
    }
}
