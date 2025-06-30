<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CustomResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required', // vẫn cần để reset password hoạt động
            'email' => [
                'email',
                'exists:users,email',
            ],
            'password' => [
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Liên kết không hợp lệ hoặc đã hết hạn.',

            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Không tìm thấy email trong hệ thống.',

            'password.string' => 'Mật khẩu không hợp lệ.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.mixed' => 'Mật khẩu phải có chữ hoa và chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất 1 chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu đã bị rò rỉ. Vui lòng chọn mật khẩu khác.',
        ];
    }
}
