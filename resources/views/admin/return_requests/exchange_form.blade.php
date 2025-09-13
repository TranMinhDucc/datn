@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tạo Đơn Hàng Mới cho Yêu Cầu #{{ $returnRequest->id }}</h1>

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
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $returnRequest->order->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_method_id">Phương thức thanh toán</label>
                        <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="shipping_method">Phương thức vận chuyển</label>
                        <select name="shipping_method" id="shipping_method" class="form-control" required>
                            @foreach ($shippingMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address_id">Địa chỉ giao hàng</label>
                        <select name="address_id" id="address_id" class="form-control" required>
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}"
                                    {{ $returnRequest->order->shipping_address_id == $address->id ? 'selected' : '' }}>
                                    {{ $address->full_name }} - {{ $address->address }}
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
                </div>
                <div class="card-body">
                    <div id="items-container">
                        @foreach ($returnRequest->items as $index => $item)
                            <div class="item-row">
                                <div class="form-group col-md-4">
                                    <label for="product_id_{{ $index }}">Sản phẩm</label>
                                    <select name="items[{{ $index }}][product_id]"
                                        id="product_id_{{ $index }}" class="form-control product-select" required>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $item->orderItem->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="variant_id_{{ $index }}">Biến thể (nếu có)</label>
                                    <select name="items[{{ $index }}][variant_id]"
                                        id="variant_id_{{ $index }}" class="form-control variant-select">
                                        <option value="">Không có biến thể</option>
                                        @foreach ($item->orderItem->product->variants as $variant)
                                            <option value="{{ $variant->id }}"
                                                {{ $item->orderItem->variant_id == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->color }} - {{ $variant->size }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="quantity_{{ $index }}">Số lượng</label>
                                    <input type="number" name="items[{{ $index }}][quantity]"
                                        id="quantity_{{ $index }}" class="form-control" min="1"
                                        value="{{ $item->quantity }}" required>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-item">Xóa</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Tạo đơn hàng</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var productVariants = @json($productVariants);

        $(document).ready(function() {
            $('.product-select').each(function(index, element) {
                var selectId = $(element).attr('id').split('_')[1];
                bindProductChangeHandler(selectId);
            });

            function bindProductChangeHandler(index) {
                $('#product_id_' + index).change(function() {
                    const productId = $(this).val();
                    const variantSelect = $('#variant_id_' + index);
                    variantSelect.empty();

                    if (productId && productVariants[productId]) {
                        const variants = productVariants[productId];

                        if (variants.length > 0) {
                            $.each(variants, function(i, variant) {
                                variantSelect.append('<option value="' + variant.id + '">' + variant
                                    .variant_name + ' (Giá: ' + variant.price + ' VNĐ)</option>'
                                );
                            });
                            variantSelect.val(variants[0].id);
                        } else {
                            variantSelect.append('<option value="">Không có biến thể</option>');
                        }
                    } else {
                        variantSelect.append('<option value="">Không có biến thể</option>');
                    }
                });
            }
        });
    </script>
@endsection
