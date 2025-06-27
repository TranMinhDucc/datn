<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetUserPassword extends Controller
{
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',

            'email' => [

                'email',
                'max:255',
                'exists:users,email',
                function ($attribute, $value, $fail) {
                    // ❗Có thể bỏ nếu đã dùng 'email' rule ở trên
                    if (!str_contains($value, '@')) {
                        return $fail('Email phải chứa ký tự "@".');
                    }

                    if (!preg_match('/\.[a-z]{2,}$/', $value)) {
                        return $fail('Email phải có đuôi tên miền hợp lệ như ".com", ".vn"...');
                    }
                },
            ],

            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',   // ít nhất 1 chữ in hoa
                'regex:/[a-z]/',   // ít nhất 1 chữ thường
                'regex:/[0-9]/',   // ít nhất 1 số
                'regex:/[\W_]/',   // ít nhất 1 ký tự đặc biệt
            ],
        ], [
            // EMAIL

            'email.email'       => 'Email không đúng định dạng.',
            'email.exists'      => 'Email không tồn tại trong hệ thống.',

            // PASSWORD
            'password.required'   => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed'  => 'Xác nhận mật khẩu không khớp.',
            'password.min'        => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex'      => 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Xác minh token
        $user = User::where('email', $request->email)->first();

        if (!$user || !Password::tokenExists($user, $request->token)) {
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // ✅ Cập nhật mật khẩu
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
