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
                                tabindex="0">+ Thêm địa chỉ mới</a>
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
                                    <input
                                        class="custom-radio me-2"
                                        id="payment_{{ $method->id }}"
                                        type="radio"
                                        name="payment_method_id"
                                        value="{{ $method->id }}"
                                        data-code="{{ $method->code }}" {{-- QUAN TRỌNG --}}
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
                                {{-- <li>
                                        <p>Phí vận chuyển</p>
                                        <span>
                                            {{ $shippingFee > 0 ? number_format($shippingFee) . ' ₫' : 'Chọn địa chỉ để tính phí' }}
                                </span>

                                </li> --}}
                                
<li class="product-coupon-line d-none d-flex justify-content-between align-items-center">
  <div class="flex-grow-1 text-truncate">
    Giảm giá SP <small class="text-muted coupon-code-text"></small>
  </div>
  <div class="coupon-amount text-danger fw-semibold text-nowrap">- 0 đ</div>
</li>

<li class="shipping-coupon-line d-none d-flex justify-content-between align-items-center">
  <div class="flex-grow-1 text-truncate">
    Giảm phí ship <small class="text-muted coupon-code-text"></small>
  </div>
  <div class="coupon-amount text-danger fw-semibold text-nowrap">- 0 đ</div>
</li>


                                <li>
                                    <p>Phí vận chuyển:</p>
                                    <span class="shipping-fee-amount ">
                                        {{ number_format((float) data_get($shippingFee, 'data.total', 0), 0, ',', '.') }}đ
                                    </span>
                                </li>



                                <li>
                                    <p>Thuế</p>
                                    <span id="tax-value" data-vat="{{ $settings['vat'] ?? 0 }}">
                                        0 ₫
                                    </span>
                                </li>
                                
                            </ul>
                            {{-- <div class="coupon-code">
                                    <input type="text" placeholder="Enter Coupon Code">
                                    <button class="btn">Apply</button>
                                </div> --}}
                        </div>
                        <div class="total">
                            <h6>Tổng : </h6>
                            <h6>$ 37.73</h6>
                        </div>
                        <div class="order-button"><button type="button" class="btn btn_black sm w-100 rounded">Đặt
                                hàng</button></div>
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
                <form action="{{ route('client.checkout.address.store') }}" method="POST" class="row g-3"
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
    const placeOrderUrl = "{{ route('client.checkout.place-order') }}";

    // Khởi tạo sessionStorage với giá trị mặc định 0, sẽ được cập nhật sau
    sessionStorage.setItem('originalShippingFee', 0);
</script>

<script>
    const momoPaymentUrl = "{{ route('client.checkout.init-momo') }}";
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

        // ✅ Phí ship gốc và giảm
        const originalShippingFee = parseFloat(sessionStorage.getItem('originalShippingFee')) || 0;
        const shippingDiscount = parseFloat(sessionStorage.getItem('shippingDiscountAmount')) || 0;
        const actualShipping = Math.max(0, originalShippingFee - shippingDiscount);

        // ✅ Hiển thị phí ship
        const shippingDisplay = document.querySelector('.shipping-fee-amount');
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

                    // ✅ Xử lý thuộc tính sản phẩm trước
                    const attributes = Object.entries(item.attributes || {})
                        .map(([k, v]) => `${k}: ${v}`)
                        .join(' / ');

                    // ✅ Gán HTML cho sản phẩm
                    const cut = (s, max=40) => s.length > max ? s.slice(0, max-1) + '…' : s;
                    li.innerHTML = `
        <img src="${item.image}" width="50" alt="${item.name}">
        <div>
            <h6>${cut(item.name, 40)}</h6>
            <span>${attributes}</span>
        </div>
    `;

                    cartList.appendChild(li);
                });

            } else {
                cartList.innerHTML = `<li><p>Giỏ hàng trống.</p></li>`;
            }
        }

        // Gọi API để tính phí ship ngay khi tải trang
        const defaultAddressRadio = document.querySelector('input[name="shipping_address_id"]:checked');
        if (defaultAddressRadio) {
            defaultAddressRadio.dispatchEvent(new Event('change'));
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const currentUser = localStorage.getItem('currentUser') || 'guest';
        const cartKey = `cartItems_${currentUser}`;
        const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

        function updateCheckoutSummary(shippingFee = null) {
            const currentUser = localStorage.getItem('currentUser') || 'guest';
            const cartKey = `cartItems_${currentUser}`;
            const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

            let subtotal = 0;
            cartItems.forEach(item => subtotal += item.price * item.quantity);

            const productDiscount = parseFloat(sessionStorage.getItem('productDiscountAmount')) || 0;
            const shippingDiscount = parseFloat(sessionStorage.getItem('shippingDiscountAmount')) || 0;

            const baseShipping = shippingFee !== null ?
                shippingFee :
                parseFloat(sessionStorage.getItem('originalShippingFee')) || 0;

            const actualShipping = Math.max(0, baseShipping - shippingDiscount);
            const subtotalAfterDiscount = Math.max(0, subtotal - productDiscount);
            const vatRate = parseFloat(document.getElementById('tax-value')?.dataset.vat || 0);
            const taxAmount = Math.round((subtotalAfterDiscount + actualShipping) * vatRate / 100);
            const total = Math.max(0, subtotalAfterDiscount + actualShipping + taxAmount);

            // Cập nhật UI
            const shippingEl = document.querySelector('.shipping-fee-amount');
            if (shippingEl) {
                shippingEl.textContent = actualShipping.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            const subtotalEl = document.querySelector('.subtotal-amount');
            if (subtotalEl) {
                subtotalEl.textContent = subtotalAfterDiscount.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            const vatEl = document.getElementById('tax-value');
            if (vatEl) {
                vatEl.textContent = `${taxAmount.toLocaleString('vi-VN')} ₫`;
            }

            const totalEl = document.querySelector('.total h6:last-child');
            if (totalEl) {
                totalEl.textContent = total.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }
        }

        // 👉 Sự kiện chọn địa chỉ sẽ cập nhật phí ship động
        document.querySelectorAll('input[name="shipping_address_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const addressId = this.value;

                // Lấy cart items từ localStorage
                const currentUser = localStorage.getItem('currentUser') || 'guest';
                const cartKey = `cartItems_${currentUser}`;
                const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

                fetch(`/shipping-fee/calculate?address_id=${addressId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            cartItems
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const newFee = parseFloat(data.total);
                            sessionStorage.setItem('originalShippingFee', newFee);
                            updateCheckoutSummary(newFee);
                        } else {
                            alert('❌ Không thể tính phí vận chuyển: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error('❌ Lỗi khi gọi API phí ship:', err);
                    });
            });
        });

        // Gọi cập nhật phí ship ban đầu nếu có địa chỉ mặc định
        const defaultAddressRadio = document.querySelector('input[name="shipping_address_id"]:checked');
        if (defaultAddressRadio) {
            const addressId = defaultAddressRadio.value;
            const currentUser = localStorage.getItem('currentUser') || 'guest';
            const cartKey = `cartItems_${currentUser}`;
            const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

            fetch(`/shipping-fee/calculate?address_id=${addressId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: JSON.stringify({
                        cartItems
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const newFee = parseFloat(data.total);
                        sessionStorage.setItem('originalShippingFee', newFee);
                        updateCheckoutSummary(newFee);
                    } else {
                        alert('❌ Không thể tính phí vận chuyển: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('❌ Lỗi khi gọi API phí ship:', err);
                });
        } else {
            updateCheckoutSummary(); // Cập nhật với phí 0 nếu không có địa chỉ mặc định
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

    document.querySelector('.order-button button').addEventListener('click', async function(e) {
        e.preventDefault();

        const btn = this;
        if (btn.dataset.loading === '1') return; // chống double click
        btn.dataset.loading = '1';

        try {
            const currentUser = localStorage.getItem('currentUser') || 'guest';
            const cartItems = JSON.parse(localStorage.getItem(`cartItems_${currentUser}`)) || [];

            // Lấy chọn lựa
            const selectedShippingAddress = document.querySelector('input[name="shipping_address_id"]:checked')?.value;
            const selectedPaymentMethodEl = document.querySelector('input[name="payment_method_id"]:checked');
            const selectedPaymentMethodId = selectedPaymentMethodEl?.value;
            const selectedPaymentMethodCode = selectedPaymentMethodEl?.dataset.code;

            if (!selectedShippingAddress || !selectedPaymentMethodId) {
                alert('Vui lòng chọn địa chỉ giao hàng và phương thức thanh toán.');
                return;
            }

            // Coupon (nếu có)
            const productCoupon = JSON.parse(sessionStorage.getItem('productCoupon') || '{}');
            const shippingCoupon = JSON.parse(sessionStorage.getItem('shippingCoupon') || '{}');
            const productCouponId = productCoupon.id || null;
            const shippingCouponId = shippingCoupon.id || null;

            // Tính tiền
            const subtotal = cartItems.reduce((s, it) => s + it.price * it.quantity, 0);
            const discount = parseFloat(sessionStorage.getItem('productDiscountAmount')) || 0;
            const shippingFee = parseFloat(sessionStorage.getItem('originalShippingFee')) || 0;
            const shippingDiscount = parseFloat(sessionStorage.getItem('shippingDiscountAmount')) || 0;
            const actualShipping = Math.max(0, shippingFee - shippingDiscount);
            const subtotalAfter = Math.max(0, subtotal - discount);

            const vatEl = document.getElementById('tax-value');
            const vatRate = parseFloat(vatEl?.dataset.vat || 0);
            const taxAmount = Math.round((subtotalAfter + actualShipping) * vatRate / 100);

            const totalAmount = Math.max(0, subtotalAfter + actualShipping + taxAmount);

            // ================= NHÁNH MOMO =================
            if (selectedPaymentMethodCode === 'momo_qr') {
                // tạo orderId mới MỖI LẦN click
                const momoOrderId = 'ORDER' + Date.now() + Math.floor(Math.random() * 1_000_000);
                // không dùng/không lấy lại từ localStorage để tránh trùng
                localStorage.removeItem('momo_order_id');

                if (totalAmount < 1000) {
                    alert('Số tiền tối thiểu để thanh toán MoMo là 1.000đ.');
                    return;
                }

                const payload = {
                    order_id: momoOrderId,
                    total_amount: Math.round(totalAmount),
                    shipping_address_id: selectedShippingAddress,
                    payment_method_id: selectedPaymentMethodId,
                    coupon_id: productCouponId,
                    shipping_coupon_id: shippingCouponId,
                    subtotal: subtotal,
                    shipping_fee: shippingFee,
                    discount_amount: discount,
                    tax_amount: taxAmount,
                    cartItems
                };

                const res = await fetch("{{ route('client.checkout.init-momo') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                console.log('MoMo init =>', data);

                if (data?.success && data?.payUrl) {
                    window.location.href = data.payUrl; // chuyển tới trang QR của MoMo
                    return; // DỪNG: không gọi placeOrder
                } else {
                    alert('Không thể tạo yêu cầu MoMo: ' + (data.message || 'Không nhận được payUrl'));
                    return;
                }
            }

            // ================= NHÁNH KHÔNG PHẢI MOMO =================
            const dataToSend = {
                cartItems,
                shipping_address_id: selectedShippingAddress,
                payment_method_id: selectedPaymentMethodId,
                coupon_id: productCouponId,
                shipping_coupon_id: shippingCouponId,
                discount_amount: discount,
                shipping_fee: shippingFee,
                tax_amount: taxAmount
            };

            const res = await fetch("{{ route('client.checkout.place-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(dataToSend)
            });
            const data = await res.json();
            console.log('Place order =>', data);

            if (data.success) {
                // dọn local/session
                localStorage.removeItem(`cartItems_${currentUser}`);
                sessionStorage.removeItem('shippingCoupon');
                sessionStorage.removeItem('productCoupon');

                window.location.href = '/order-success';
            } else {
                alert('❌ Đặt hàng thất bại: ' + (data.message || 'Đã xảy ra lỗi.'));
            }
        } catch (err) {
            console.error('Checkout error:', err);
            alert('❌ Không thể kết nối đến server. Vui lòng thử lại.');
        } finally {
            btn.dataset.loading = '0';
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
<!-- Loại bỏ script trùng lặp -->




<style>
  .btn.disabled, .btn[disabled] { pointer-events: none; opacity: .6; cursor: not-allowed; }
</style>

<script>
  // kiểm tra giỏ trống (không item hoặc tất cả quantity = 0)
  function isCartEmpty() {
    const currentUser = localStorage.getItem('currentUser') || 'guest';
    const cart = JSON.parse(localStorage.getItem(`cartItems_${currentUser}`) || '[]');
    const totalQty = cart.reduce((s, i) => s + (parseInt(i.quantity) || 0), 0);
    return cart.length === 0 || totalQty === 0;
  }

  // khoá/mở nút Đặt hàng tuỳ theo giỏ
  function lockPlaceOrderIfEmpty() {
    const btn = document.querySelector('.order-button button');
    if (!btn) return;
    const empty = isCartEmpty();
    btn.disabled = empty;
    btn.classList.toggle('disabled', empty);
  }

  document.addEventListener('DOMContentLoaded', () => {
    lockPlaceOrderIfEmpty(); // gọi khi vào trang

    // CHẶN khi click
    const placeBtn = document.querySelector('.order-button button');
    if (placeBtn) {
      placeBtn.addEventListener('click', function(e) {
        if (isCartEmpty()) {
          e.preventDefault();
          // dùng SweetAlert2 (bạn đã import) cho đẹp
          Swal.fire({
            icon: 'warning',
            title: 'Giỏ hàng trống',
            text: 'Vui lòng thêm sản phẩm trước khi đặt hàng.',
            confirmButtonText: 'Đã hiểu'
          });
          return;
        }
      }, { capture: true }); // capture để chạy trước logic gửi đơn
    }
  });
</script>


<script>
  const fVND = n => Number(n || 0).toLocaleString('vi-VN') + ' ₫';

  function renderCouponLines() {
    const productCoupon  = JSON.parse(sessionStorage.getItem('productCoupon')  || '{}');
    const shippingCoupon = JSON.parse(sessionStorage.getItem('shippingCoupon') || '{}');
    const productDiscount  = Number(sessionStorage.getItem('productDiscountAmount')  || 0);
    const shippingDiscount = Number(sessionStorage.getItem('shippingDiscountAmount') || 0);

    const productLine  = document.querySelector('.product-coupon-line');
    const shippingLine = document.querySelector('.shipping-coupon-line');

    // sản phẩm
    if (productCoupon?.code && productDiscount > 0) {
      productLine.classList.remove('d-none');
      productLine.querySelector('.coupon-code-text').textContent = `(${productCoupon.code})`;
      productLine.querySelector('.coupon-amount').textContent = '- ' + fVND(productDiscount);
    } else {
      productLine.classList.add('d-none');
    }

    // vận chuyển
    if (shippingCoupon?.code && shippingDiscount > 0) {
      shippingLine.classList.remove('d-none');
      shippingLine.querySelector('.coupon-code-text').textContent = `(${shippingCoupon.code})`;
      shippingLine.querySelector('.coupon-amount').textContent = '- ' + fVND(shippingDiscount);
    } else {
      shippingLine.classList.add('d-none');
    }
  }

  document.addEventListener('DOMContentLoaded', renderCouponLines);

  const fVNDNoWrap = n => Number(n || 0).toLocaleString('vi-VN') + '\u00A0đ';

function renderCouponLines() {
  const productCoupon  = JSON.parse(sessionStorage.getItem('productCoupon')  || '{}');
  const shippingCoupon = JSON.parse(sessionStorage.getItem('shippingCoupon') || '{}');
  const productDiscount  = Number(sessionStorage.getItem('productDiscountAmount')  || 0);
  const shippingDiscount = Number(sessionStorage.getItem('shippingDiscountAmount') || 0);

  const pLine = document.querySelector('.product-coupon-line');
  const sLine = document.querySelector('.shipping-coupon-line');

  if (pLine) {
    if (productCoupon?.code && productDiscount > 0) {
      pLine.classList.remove('d-none');
      pLine.querySelector('.coupon-code-text').textContent = `(${productCoupon.code})`;
      pLine.querySelector('.coupon-amount').textContent = `- ${fVNDNoWrap(productDiscount)}`;
    } else pLine.classList.add('d-none');
  }

  if (sLine) {
    if (shippingCoupon?.code && shippingDiscount > 0) {
      sLine.classList.remove('d-none');
      sLine.querySelector('.coupon-code-text').textContent = `(${shippingCoupon.code})`;
      sLine.querySelector('.coupon-amount').textContent = `- ${fVNDNoWrap(shippingDiscount)}`;
    } else sLine.classList.add('d-none');
  }
}

document.addEventListener('DOMContentLoaded', renderCouponLines);

</script>


@endsection