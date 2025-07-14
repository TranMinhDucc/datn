@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')

    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Thanh toán</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="index.html">Trang chủ </a></li>
                            <li class="breadcrumb-item active"> <a href="#">Thanh toán</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0">
        <div class="custom-container container">
            <div class="row">
                <div class="col-xxl-9 col-lg-8">
                    <div class="left-sidebar-checkout sticky">
                        <div class="address-option">
                            <div class="address-title">
                                <h4>Địa chỉ giao hàng</h4>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#address-modal" title="add product"
                                    tabindex="0">+ Add New Address</a>
                            </div>
                            <div class="row">
                                @foreach ($addresses as $address)
                                    <div class="col-xxl-4 mb-3">
                                        <label for="address-{{ $address->id }}">
                                            <span class="delivery-address-box">
                                                <span class="form-check">
                                                    <input class="custom-radio" type="radio"
                                                        id="address-{{ $address->id }}" name="shipping_address_id"
                                                        value="{{ $address->id }}"
                                                        {{ $defaultAddress && $defaultAddress->id == $address->id ? 'checked' : '' }}>
                                                </span>
                                                <span class="address-detail">
                                                    <span class="address">
                                                        <span class="address-title">{{ $address->title }}</span>
                                                    </span>
                                                    <span class="address">
                                                        <span class="address-home">
                                                            <span class="address-tag">Địa chỉ:</span>
                                                            {{ $address->address }}, {{ $address->city }},
                                                            {{ $address->state }}, {{ $address->country }}
                                                        </span>
                                                    </span>
                                                    <span class="address">
                                                        <span class="address-home">
                                                            <span class="address-tag">Người nhận:</span>
                                                            {{ $address->full_name }}
                                                        </span>
                                                    </span>
                                                    <span class="address">
                                                        <span class="address-home">
                                                            <span class="address-tag">Điện thoại :</span>
                                                            {{ $address->phone }}
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="payment-options">
                            <h4 class="mb-3">Phương thức thanh toán</h4>
                            <div class="row gy-3">
                                @foreach ($paymentMethods as $method)
                                    <div class="col-sm-6">
                                        <div class="payment-box">
                                            <input class="custom-radio me-2" id="payment_{{ $method->id }}"
                                                type="radio" name="payment_method_id" value="{{ $method->id }}"
                                                {{ $loop->first ? 'checked' : '' }}>
                                            <label for="payment_{{ $method->id }}">{{ $method->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4">
                    <div class="right-sidebar-checkout">
                        <h4>Thanh toán</h4>
                        <div class="cart-listing">
                            <ul id="checkout-cart-items">
                            </ul>
                            <div class="summary-total">
                                <ul>
                                    <li>
                                        <p>Tạm tính</p> <span class="subtotal-amount">$0.00</span>
                                    </li>
                                    <li>
                                        <p>Phí vận chuyển</p>
                                        <span>
                                            {{ $shippingFee > 0 ? number_format($shippingFee) . ' ₫' : 'Chọn địa chỉ để tính phí' }}
                                        </span>

                                    </li>
                                    <li>
                                        <p>Thuế</p>
                                        <span id="tax-value" data-vat="{{ $settings['vat'] ?? 0 }}">
                                            0 ₫
                                        </span>
                                    </li>
                                    <li>
                                        <p>Ví của bạn</p>
                                        <span>{{ number_format(auth()->user()->balance) }} ₫</span>
                                    </li>
                                </ul>
                                <div class="coupon-code">
                                    <input type="text" placeholder="Enter Coupon Code">
                                    <button class="btn">Apply</button>
                                </div>
                            </div>
                            <div class="total">
                                <h6>Tổng : </h6>
                                <h6>$ 37.73</h6>
                            </div>
                            <div class="order-button"><button type="button" class="btn btn_black sm w-100 rounded">Place
                                    Order</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal theme-modal fade address-modal" id="address-modal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Thêm địa chỉ</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('client.checkout.address.store') }}"  method="POST" class="row g-3"
                        id="address-form">
                        @csrf

                        <div class="col-6">
                            <label class="form-label">Loại địa chỉ</label>
                            <select class="form-select @error('title') is-invalid @enderror" name="title">
                                <option value="">-- Chọn loại --</option>
                                <option value="Nhà riêng" {{ old('title') == 'Nhà riêng' ? 'selected' : '' }}>Nhà
                                    riêng
                                </option>
                                <option value="Công ty" {{ old('title') == 'Công ty' ? 'selected' : '' }}>Công ty
                                </option>
                                <option value="Khác" {{ old('title') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tên người nhận</label>
                            <input class="form-control @error('full_name') is-invalid @enderror" name="full_name"
                                value="{{ old('full_name') }}">
                            @error('full_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">Điện thoại</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">Mã bưu chính</label>
                            <input class="form-control @error('pincode') is-invalid @enderror" name="pincode"
                                value="{{ old('pincode') }}">
                            @error('pincode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" class="form-control" name="country" value="Vietnam">
                        <div class="col-4">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" name="province_id" id="province-select" required>
                                <option value="">-- Chọn tỉnh --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Quận/Huyện</label>
                            <select class="form-select" name="district_id" id="district-select" required>
                                <option value="">-- Chọn huyện --</option>
                            </select>
                            @error('district_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Phường/Xã</label>
                            <select class="form-select" name="ward_id" id="ward-select" required>
                                <option value="">-- Chọn xã --</option>
                            </select>
                            @error('ward_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Địa chỉ chi tiết</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg px-5 py-2 fw-semibold">
                            Submit
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        const placeOrderUrl = "{{ route('client.checkout.place-order') }}"; // phải khớp tên
        sessionStorage.setItem('originalShippingFee', {{ $shippingFee ?? 0 }});
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUser = localStorage.getItem('currentUser') || 'guest';
            const cartKey = `cartItems_${currentUser}`;
            const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];
            console.log("📦 Cart Items hiện tại:", cartItems);

            // ✅ Giá gốc sản phẩm
            let originalSubtotal = 0;
            cartItems.forEach(item => {
                originalSubtotal += item.price * item.quantity;
            });

            // ✅ Mã giảm giá sản phẩm
            const productDiscount = parseFloat(sessionStorage.getItem('productDiscountAmount')) || 0;
            const subtotalAfterDiscount = Math.max(0, originalSubtotal - productDiscount);

            // // ✅ Phí ship gốc và giảm
            // const originalShippingFee = parseFloat(sessionStorage.getItem('originalShippingFee')) || 0;
            // const shippingDiscount = parseFloat(sessionStorage.getItem('shippingDiscountAmount')) || 0;
            // const actualShipping = Math.max(0, originalShippingFee - shippingDiscount);
            // // SHIP
            // const shippingDisplay = document.querySelector('.summary-total ul li:nth-child(2) span');
            // if (shippingDisplay) {
            //     shippingDisplay.textContent = actualShipping.toLocaleString('vi-VN', {
            //         style: 'currency',
            //         currency: 'VND'
            //     });
            // }
            // ✅ Phí ship gốc và giảm
            const originalShippingFee = parseFloat(sessionStorage.getItem('originalShippingFee')) || 0;
            const shippingDiscount = parseFloat(sessionStorage.getItem('shippingDiscountAmount')) || 0;
            const actualShipping = Math.max(0, originalShippingFee - shippingDiscount);

            // ✅ Hiển thị phí ship
            const shippingDisplay = document.querySelector('.summary-total ul li:nth-child(2) span');
            if (shippingDisplay) {
                shippingDisplay.textContent = actualShipping.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            // ✅ VAT
            const taxElement = document.getElementById('tax-value');
            const vatRate = parseFloat(taxElement?.dataset.vat || 0);
            const taxAmount = Math.round((subtotalAfterDiscount + actualShipping) * vatRate / 100);


            // ✅ Tổng tiền thanh toán
            const total = Math.max(0, subtotalAfterDiscount + actualShipping + taxAmount);

            // ✅ Gán vào giao diện

            // Subtotal
            const subtotalEl = document.querySelector('.subtotal-amount');
            if (subtotalEl) {
                subtotalEl.textContent = subtotalAfterDiscount.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            // Shipping
            // const shippingEl = document.querySelector('.summary-total ul li:nth-child(2) span');
            // if (shippingEl) {
            //     if (shippingDiscount > 0) {
            //         shippingEl.textContent = `– ${shippingDiscount.toLocaleString('vi-VN')} ₫`;
            //         shippingEl.classList.add('text');
            //     } else {
            //         shippingEl.textContent = 'Không có mã freeship';
            //     }
            // }

            // VAT
            if (taxElement) {
                taxElement.textContent = `${taxAmount.toLocaleString('vi-VN')} ₫`;
            }

            // Tổng
            const totalEl = document.querySelector('.total h6:last-child');
            if (totalEl) {
                totalEl.textContent = total.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            // ✅ Danh sách sản phẩm
            const cartList = document.getElementById('checkout-cart-items');
            if (cartList) {
                cartList.innerHTML = '';
                if (cartItems.length > 0) {
                    cartItems.forEach(item => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                    <img src="${item.image}" width="50" alt="${item.name}">
                    <div>
                        <h6>${item.name}</h6>
                        <span>${Object.entries(item.attributes || {}).map(([k, v]) => `
                        ${
                            k
                        }: ${
                            v
                        }
                        `).join(' / ')}</span>
                    </div>
                    
                `;
                        cartList.appendChild(li);
                    });
                } else {
                    cartList.innerHTML = `<li><p>Giỏ hàng trống.</p></li>`;
                }
            }
        });


        if (window.location.href.includes('/checkout')) {
            const newOrder = sessionStorage.getItem('orderJustPlaced');
            if (newOrder === 'true') {
                sessionStorage.removeItem('shippingCoupon');
                sessionStorage.removeItem('productCoupon');
                sessionStorage.removeItem('appliedCoupon');
                sessionStorage.removeItem('orderJustPlaced');
            }
        }

        document.querySelector('.order-button button').addEventListener('click', function(e) {
            e.preventDefault();

            const currentUser = localStorage.getItem('currentUser') || 'guest';
            const cartItems = JSON.parse(localStorage.getItem(`cartItems_${currentUser}`)) || [];

            const selectedShippingAddress = document.querySelector('input[name="shipping_address_id"]:checked')
                ?.value;
            const selectedPaymentMethodId = document.querySelector('input[name="payment_method_id"]:checked')
                ?.value;

            // Lấy code phương thức thanh toán
            const paymentMethods = @json($paymentMethods);
            const selectedPaymentMethod = paymentMethods.find(m => m.id == selectedPaymentMethodId);
            const paymentCode = selectedPaymentMethod ? selectedPaymentMethod.code : '';

            const productCoupon = JSON.parse(sessionStorage.getItem('productCoupon') || '{}');
            const productCouponId = productCoupon.id || null;

            const shippingCoupon = JSON.parse(sessionStorage.getItem('shippingCoupon') || '{}');
            const shippingCouponId = shippingCoupon.id || null;

            const subtotal = cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0);
            const discount = parseFloat(sessionStorage.getItem('productDiscountAmount')) || 0;
            const shippingFee = parseFloat(sessionStorage.getItem('originalShippingFee')) || 0;
            const shippingDiscount = parseFloat(sessionStorage.getItem('shippingDiscountAmount')) || 0;
            const actualShipping = Math.max(0, shippingFee - shippingDiscount);
            const subtotalAfterDiscount = Math.max(0, subtotal - discount);

            const taxEl = document.getElementById('tax-value');
            const vatRate = parseFloat(taxEl?.dataset.vat || 0);
            const taxAmount = Math.round((subtotalAfterDiscount + actualShipping) * vatRate / 100);

            const dataToSend = {
                cartItems,
                shipping_address_id: selectedShippingAddress,
                payment_method_id: selectedPaymentMethodId,
                coupon_id: productCouponId,
                shipping_coupon_id: shippingCouponId,
                discount_amount: parseFloat(sessionStorage.getItem('productDiscountAmount')) || 0,
                shipping_fee: parseFloat(sessionStorage.getItem('originalShippingFee')) || 0,
                tax_amount: taxAmount,
                total_amount: subtotalAfterDiscount + actualShipping + taxAmount
            };

            if (paymentCode === 'momo' || paymentCode === 'vnpay') {
                fetch("{{ route('client.checkout.initiate-payment') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(dataToSend)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.url) {
                            window.location.href = data.url;
                        } else {
                            alert('❌ ' + (data.message || 'Lỗi khởi tạo thanh toán online'));
                        }
                    })
                    .catch(err => {
                        console.error('❌ Lỗi fetch:', err);
                        alert('Lỗi khi gửi đơn hàng');
                    });
            } else {
                // Thanh toán thường (ví, COD...)
                fetch(placeOrderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(dataToSend)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            localStorage.removeItem(`cartItems_${currentUser}`);
                            sessionStorage.removeItem('shippingCoupon');
                            sessionStorage.removeItem('productCoupon');
                            window.location.href = '/order-success';
                        } else {
                            alert('❌ ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error('❌ Lỗi fetch:', err);
                        alert('Lỗi khi gửi đơn hàng');
                    });
            }
        });
    </script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#province-select').on('change', function() {
            const provinceId = $(this).val();
            $('#district-select').html('<option value="">-- Đang tải huyện --</option>');
            $('#ward-select').html('<option value="">-- Chọn xã --</option>');
            if (provinceId) {
                $.get(`/api/districts?province_id=${provinceId}`, function(data) {
                    let html = '<option value="">-- Chọn huyện --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#district-select').html(html);
                });
            }
        });

        $('#district-select').on('change', function() {
            const districtId = $(this).val();
            $('#ward-select').html('<option value="">-- Đang tải xã --</option>');
            if (districtId) {
                $.get(`/api/wards?district_id=${districtId}`, function(data) {
                    let html = '<option value="">-- Chọn xã --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#ward-select').html(html);
                });
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#province-select').on('change', function() {
            const provinceId = $(this).val();
            $('#district-select').html('<option value="">-- Đang tải huyện --</option>');
            $('#ward-select').html('<option value="">-- Chọn xã --</option>');
            if (provinceId) {
                $.get(`/api/districts?province_id=${provinceId}`, function(data) {
                    let html = '<option value="">-- Chọn huyện --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#district-select').html(html);
                });
            }
        });

        $('#district-select').on('change', function() {
            const districtId = $(this).val();
            $('#ward-select').html('<option value="">-- Đang tải xã --</option>');
            if (districtId) {
                $.get(`/api/wards?district_id=${districtId}`, function(data) {
                    let html = '<option value="">-- Chọn xã --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#ward-select').html(html);
                });
            }
        });
    </script>
@endsection
