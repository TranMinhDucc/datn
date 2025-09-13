@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tạo Đơn Hàng Mới</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf

            <!-- Thông tin chung -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="user_id">Người dùng</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">Chọn người dùng</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_method_id">Phương thức thanh toán</label>
                        <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                            <option value="">Chọn phương thức</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shipping_method">Phương thức vận chuyển</label>
                        <select name="shipping_method" id="shipping_method" class="form-control" required>
                            <option value="">Chọn phương thức</option>
                            @foreach ($shippingMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address_id">Địa chỉ giao hàng</label>
                        <select name="address_id" id="address_id" class="form-control" required>
                            <option value="">Chọn địa chỉ</option>
                            @foreach (auth()->user()->shippingAddresses as $address)
                                <option value="{{ $address->id }}">{{ $address->full_name }} - {{ $address->address }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm trong đơn hàng</h6>
                    <button type="button" class="btn btn-primary btn-sm" id="add-item">Thêm sản phẩm</button>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <div class="item-row">
                            <div class="form-group col-md-4">
                                <label for="product_id_0">Sản phẩm</label>
                                <select name="items[0][product_id]" id="product_id_0" class="form-control product-select"
                                    required>
                                    <option value="">Chọn sản phẩm</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="variant_id_0">Biến thể (nếu có)</label>
                                <select name="items[0][variant_id]" id="variant_id_0" class="form-control variant-select">
                                    <option value="">Không có biến thể</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="quantity_0">Số lượng</label>
                                <input type="number" name="items[0][quantity]" id="quantity_0" class="form-control"
                                    min="1" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm remove-item">Xóa</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Tạo đơn hàng</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let itemIndex = 0;

            // Thêm sản phẩm
            $('#add-item').click(function() {
                itemIndex++;
                const newRow = `
                    <div class="item-row">
                        <div class="form-group col-md-4">
                            <label for="product_id_${itemIndex}">Sản phẩm</label>
                            <select name="items[${itemIndex}][product_id]" id="product_id_${itemIndex}" class="form-control product-select" required>
                                <option value="">Chọn sản phẩm</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="variant_id_${itemIndex}">Biến thể (nếu có)</label>
                            <select name="items[${itemIndex}][variant_id]" id="variant_id_${itemIndex}" class="form-control variant-select">
                                <option value="">Không có biến thể</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="quantity_${itemIndex}">Số lượng</label>
                            <input type="number" name="items[${itemIndex}][quantity]" id="quantity_${itemIndex}" class="form-control" min="1" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-item">Xóa</button>
                        </div>
                    </div>
                `;
                $('#items-container').append(newRow);
                updateVariants(itemIndex); // Cập nhật biến thể cho dòng mới
            });

            // Xóa sản phẩm
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });

            // Cập nhật biến thể khi chọn sản phẩm
            function updateVariants(index) {
                $('#product_id_' + index).change(function() {
                    const productId = $(this).val();
                    const variantSelect = $('#variant_id_' + index);
                    variantSelect.empty();

                    if (productId && typeof productVariants !== 'undefined' && productVariants[productId]) {
                        const variants = productVariants[productId];

                        if (variants.length > 0) {
                            // Có biến thể ⇒ chỉ hiển thị các biến thể
                            $.each(variants, function(i, variant) {
                                variantSelect.append('<option value="' + variant.id + '">' + variant
                                    .variant_name + ' (Giá: ' + variant.price + ' VNĐ)' +
                                    '</option>');
                            });
                            // Auto chọn biến thể đầu tiên
                            variantSelect.val(variants[0].id);
                        } else {
                            // Không có biến thể ⇒ thêm dòng "Không có biến thể"
                            variantSelect.append('<option value="">Không có biến thể</option>');
                        }
                    } else {
                        // Không có dữ liệu biến thể ⇒ thêm dòng "Không có biến thể"
                        variantSelect.append('<option value="">Không có biến thể</option>');
                    }
                });
            }


            // Khởi tạo cho dòng đầu tiên
            updateVariants(0);
        });

        // Dữ liệu biến thể từ server
        var productVariants = @json($productVariants);
    </script>
@endsection
