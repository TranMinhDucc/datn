<?php
return [
    'required' => 'Trường :attribute là bắt buộc.',
    'min' => [
    'numeric' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
],
    'max' => [
        'file' => 'Trường :attribute không được lớn hơn :max KB.',
        'string' => 'Trường :attribute không được dài hơn :max ký tự.',

    ],
    'unique' => 'Trường :attribute đã tồn tại.',
    'image' => 'Trường :attribute phải là tệp hình ảnh.',
    'mimes' => 'Trường :attribute phải có định dạng: :values.',
    'numeric' => 'Trường :attribute phải là số.',
    'integer' => 'Trường :attribute phải là số nguyên.',
    'exists' => 'Giá trị được chọn cho :attribute không hợp lệ.',
    
    'attributes' => [
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
    ],
];
