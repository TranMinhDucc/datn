<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        // ✅ VALIDATE theo phong cách giống RegisterController
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => [
                'required',
                'string',
                'max:255',
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
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W]/',
            ],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ cái in hoa, 1 số và 1 ký tự đặc biệt.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Kiểm tra email có tồn tại
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.'])->withInput();
        }

        // ✅ Kiểm tra token
        if (!Password::tokenExists($user, $request->token)) {
            return back()->withErrors(['token' => 'Mã token không hợp lệ hoặc đã hết hạn.'])->withInput();
        }

        // ✅ Cập nhật mật khẩu
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
