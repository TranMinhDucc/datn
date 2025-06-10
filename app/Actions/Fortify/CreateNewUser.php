<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $fail('Email không đúng định dạng, ví dụ: example@gmail.com');
                    }

                    if (!str_contains($value, '@')) {
                        return $fail('Email phải chứa ký tự "@".');
                    }

                    if (!preg_match('/\.[a-z]{2,}$/', $value)) {
                        return $fail('Email phải có đuôi tên miền như ".com", ".vn"...');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
