<?php 
return [
    'required' => 'Trường :attribute không được để trống.',
    'email' => 'Định dạng :attribute không hợp lệ.',
    'confirmed' => ':attribute xác nhận không khớp.',
    'exists' => 'Giá trị được chọn cho :attribute không hợp lệ.',
    'unique' => 'Trường :attribute đã tồn tại.',
    'image' => 'Trường :attribute phải là tệp hình ảnh.',
    'mimes' => 'Trường :attribute phải có định dạng: :values.',
    'numeric' => 'Trường :attribute phải là số.',
    'integer' => 'Trường :attribute phải là số nguyên.',
    
    'min' => [
        'numeric' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
        'string' => ':attribute phải có ít nhất :min ký tự.',
    ],
    'max' => [
        'file' => 'Trường :attribute không được lớn hơn :max KB.',
        'string' => 'Trường :attribute không được dài hơn hoặc vượt quá :max ký tự.',
    ],

    // ✅ Tuỳ chỉnh thêm thông báo lỗi xác thực đăng nhập
    'auth' => [
        'failed_user' => 'Tài khoản không tồn tại.',
        'failed_password' => 'Mật khẩu không đúng.',
    ],

    'attributes' => [
        // Cho sản phẩm
        'name' => 'tên sản phẩm',
        'code' => 'mã sản phẩm',
        'price' => 'giá',
        'quantity' => 'số lượng',
        'description' => 'mô tả',
        'category_id' => 'danh mục',
        'images' => 'ảnh sản phẩm',
        'status' => 'trạng thái',
        'short_desc' => 'mô tả ngắn',
        'min_purchase_quantity' => 'số lượng mua tối thiểu',
        'max_purchase_quantity' => 'số lượng mua tối đa',

        // Cho đăng nhập
        'email' => 'Email',
        'password' => 'Mật khẩu',
        'username' => 'Tên đăng nhập',

        // ✅ Cho đánh giá (review)
        'user_id' => 'người dùng',
        'product_id' => 'sản phẩm',
        'rating' => 'đánh giá',
        'comment' => 'bình luận',
        'verified_purchase' => 'xác minh mua hàng',
        'created_at' => 'thời gian đánh giá',
    ],
];

