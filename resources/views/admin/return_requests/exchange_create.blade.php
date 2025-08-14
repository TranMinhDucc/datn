@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div class="container-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="h3 mb-4 text-gray-800">Tạo Đơn Hàng Mới cho Yêu Cầu #{{ $returnRequest->id }}</h1>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">



                    <div class="card-body pt-0">
                        <form action="{{ route('admin.return-requests.exchange.create', ['id' => $returnRequest->id]) }}"
                            method="POST">
                            @csrf


                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="user_id">Người dùng</label>
                                        <select name="user_id" id="user_id" class="form-control" required>
                                            <option value="">Chọn người dùng</option>
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
                                        <select name="payment_method_id" id="payment_method_id" class="form-control"
                                            required>
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
                                            @foreach ($addresses as $address)
                                                <option value="{{ $address->id }}"
                                                    {{ (string) $address->id === (string) old('address_id', $selectedAddressId) ? 'selected' : '' }}>
                                                    {{ $address->full_name }} - {{ $address->address }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm trong đơn hàng</h6>
                                    <button type="button" class="btn btn-primary btn-sm" id="add-item">Thêm sản
                                        phẩm</button>
                                </div>
                                <div class="card-body">
                                    <div id="items-container">
                                        @foreach ($returnRequest->items as $index => $item)
                                            {{-- @php
                                        dd($item->orderItem->toArray());
                                        dump([
                                            'Index' => $index,
                                            'Variant cần chọn' => optional($item->orderItem->variant)->id,
                                            'Các ID biến thể có sẵn' => $item->orderItem->product->variants
                                                ->pluck('id')
                                                ->toArray(),
                                            'Tên sản phẩm' => $item->orderItem->product->name,
                                        ]);
                                    @endphp --}}

                                            @php
                                                $product = $item->orderItem->product;
                                                $variant = $item->orderItem->variant;
                                            @endphp
                                            <div class="item-row row mb-3 align-items-end">
                                                <div class="form-group col-md-4">
                                                    <label>Sản phẩm</label>
                                                    <select name="items[{{ $index }}][product_id]"
                                                        id="product_id_{{ $index }}"
                                                        class="form-control product-select" required>
                                                        <option value="">Chọn sản phẩm</option>
                                                        @foreach ($products as $p)
                                                            <option value="{{ $p->id }}"
                                                                {{ (int) $p->id === (int) optional($item->orderItem->product)->id ? 'selected' : '' }}>
                                                                {{ $p->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Biến thể (nếu có)</label>
                                                    <select name="items[{{ $index }}][variant_id]"
                                                        id="variant_id_{{ $index }}"
                                                        class="form-control variant-select">
                                                        @if ($product->variants->isEmpty())
                                                            <option value="">Không có biến thể - Tồn kho:
                                                                {{ $product->stock_quantity ?? 0 }}</option>
                                                        @endif
                                                        @foreach ($product->variants as $v)
                                                            <option value="{{ $v->id }}"
                                                                {{ (int) $item->orderItem->product_variant_id === (int) $v->id ? 'selected' : '' }}
                                                                {{ ($v->quantity ?? 0) == 0 ? 'disabled' : '' }}>
                                                                {{ $v->variant_name }} ({{ number_format($v->price, 0) }}
                                                                VNĐ - Tồn
                                                                kho:
                                                                {{ $v->quantity ?? 0 }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Số lượng</label>
                                                    <input type="number" name="items[{{ $index }}][quantity]"
                                                        class="form-control" min="1"
                                                        value="{{ $item->orderItem->quantity }}" required>
                                                </div>
                                                <div class="form-group col-md-2 d-flex">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm mt-auto remove-item">Xóa</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Tạo đơn hàng</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let itemIndex = {{ count($returnRequest->items) }};

            $('#add-item').click(function() {
                itemIndex++;
                const newRow = `
                <div class="item-row row mb-3 align-items-end">
                    <div class="form-group col-md-4">
                        <label>Sản phẩm</label>
                        <select name="items[${itemIndex}][product_id]" id="product_id_${itemIndex}" class="form-control product-select" required>
                            <option value="">Chọn sản phẩm</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Biến thể (nếu có)</label>
                        <select name="items[${itemIndex}][variant_id]" id="variant_id_${itemIndex}" class="form-control variant-select">
                            <option value="">Không có biến thể</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Số lượng</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control" min="1" required>
                    </div>
                    <div class="form-group col-md-2 d-flex">
                        <button type="button" class="btn btn-danger btn-sm mt-auto remove-item">Xóa</button>
                    </div>
                </div>
            `;
                $('#items-container').append(newRow);
                updateVariants(itemIndex);
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });

            function updateVariants(index) {
                $('#product_id_' + index).change(function() {
                    const productId = $(this).val();
                    const variantSelect = $('#variant_id_' + index);
                    variantSelect.empty();

                    if (productId && productVariants[productId]) {
                        const variants = productVariants[productId];

                        if (variants.length > 0) {
                            variantSelect.append('<option value="">Chọn biến thể</option>');
                            $.each(variants, function(i, variant) {
                                const isDisabled = variant.quantity == 0 ? 'disabled' : '';
                                variantSelect.append('<option value="' + variant.id + '" ' +
                                    isDisabled + '>' +
                                    variant.variant_name + ' (' + Number(variant.price)
                                    .toLocaleString() + ' VNĐ - Tồn kho: ' + variant.quantity +
                                    ')' +
                                    '</option>');
                            });
                        } else {
                            let stock = 0;
                            @foreach ($products as $p)
                                if ({{ $p->id }} == productId) {
                                    stock = {{ $p->stock_quantity ?? 0 }};
                                }
                            @endforeach
                            variantSelect.append('<option value="">Không có biến thể - Tồn kho: ' + stock +
                                '</option>');
                        }
                    } else {
                        variantSelect.append('<option value="">Không có biến thể</option>');
                    }
                });
            }

            @foreach ($returnRequest->items as $index => $item)
                updateVariants({{ $index }});
            @endforeach
        });

        var productVariants = @json($productVariants);
    </script>
@endsection
