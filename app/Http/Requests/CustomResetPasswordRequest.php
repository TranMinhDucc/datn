<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        abort(500, 'Đã vào CustomResetPasswordRequest');
    }

    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email không tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.regex' => 'Mật khẩu phải chứa 1 chữ in hoa, 1 số và 1 ký tự đặc biệt.',
        ];
    }
}
