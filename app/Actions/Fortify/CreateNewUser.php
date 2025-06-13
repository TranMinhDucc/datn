<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input)
    {
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'fullname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!str_contains($value, '@')) {
                        return $fail('Email phải chứa ký tự "@".');
                    }

                    [$local, $domain] = explode('@', $value, 2) + [null, null];

                    if (empty($domain)) {
                        return $fail('Email thiếu tên miền sau "@".');
                    }

                    if (!str_contains($domain, '.')) {
                        return $fail('Tên miền email phải có dấu "." như gmail.com');
                    }

                    if (!preg_match('/\.[a-z]{2,}$/', $domain)) {
                        return $fail('Email phải có đuôi tên miền hợp lệ như ".com", ".vn"...');
                    }

                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $fail('Email không hợp lệ theo chuẩn RFC.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',  // Chữ in hoa
                'regex:/[0-9]/',  // Số
                'regex:/[\W]/',   // Ký tự đặc biệt
            ],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ in hoa, 1 số và 1 ký tự đặc biệt.',
        ])->validate();

        return User::create([
            'username' => $input['username'],
            'fullname' => $input['fullname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'registered_at' => now(),
        ]);
    }
}
