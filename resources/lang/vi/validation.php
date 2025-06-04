<?php 
return [
    'required' => 'Trường :attribute không được để trống.',
    'email' => 'Định dạng :attribute không hợp lệ.',
    'confirmed' => ':attribute xác nhận không khớp.',
    'exists' => ':attribute không tồn tại trong hệ thống.',
    'unique' => ':attribute đã được sử dụng.',
    'min' => [
        'string' => ':attribute phải có ít nhất :min ký tự.',
    ],
    'max' => [
        'string' => ':attribute không được vượt quá :max ký tự.',
    ],

    // ✅ Tuỳ chỉnh thêm thông báo lỗi xác thực đăng nhập
    'auth' => [
        'failed_user' => 'Tài khoản không tồn tại.',
        'failed_password' => 'Mật khẩu không đúng.',
    ],

    'attributes' => [
        'email' => 'Email',
        'password' => 'Mật khẩu',
        'username' => 'Tên đăng nhập',
    ],
];
