@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')

    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Check Out</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                            <li class="breadcrumb-item active"> <a href="#">Check Out</a></li>
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
                                <h4>Shipping Address</h4>
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
                                                            <span class="address-tag">Address:</span>
                                                            {{ $address->address }}, {{ $address->city }},
                                                            {{ $address->state }}, {{ $address->country }}
                                                        </span>
                                                    </span>
                                                    <span class="address">
                                                        <span class="address-home">
                                                            <span class="address-tag">Pin Code:</span>
                                                            {{ $address->pincode }}
                                                        </span>
                                                    </span>
                                                    <span class="address">
                                                        <span class="address-home">
                                                            <span class="address-tag">Phone :</span>
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
                        <h4>Checkout</h4>
                        <div class="cart-listing">
                            <ul id="checkout-cart-items">
                            </ul>
                            <div class="summary-total">
                                <ul>
                                    <li>
                                        <p>Subtotal</p> <span class="subtotal-amount">$0.00</span>
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
                                <h6>Total : </h6>
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Address</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="address-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="title1">Title</label>
                                    <input class="form-control" id="title1" type="text" placeholder="Enter Title">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address </label>
                                    <input class="form-control" id="address" type="text"
                                        placeholder="Enter Address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Country</label>
                                    <select class="form-select" id="cars" name="cars">
                                        <option value="volvo">Surat</option>
                                        <option value="saab">Ahmadabad</option>
                                        <option value="mercedes">Vadodara</option>
                                        <option value="audi">Vapi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">State</label>
                                    <select class="form-select" id="cars" name="cars">
                                        <option value="volvo">Gujarat</option>
                                        <option value="saab">Karnataka</option>
                                        <option value="mercedes">Madhya Pradesh</option>
                                        <option value="audi">Maharashtra</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="title1">City</label>
                                    <input class="form-control" id="title1" type="text" placeholder="Enter City">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Pincode</label>
                                    <input class="form-control" id="address" type="text"
                                        placeholder="Enter Pincode">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="address">Phone Number</label>
                                    <input class="form-control" id="address" type="number"
                                        placeholder="Enter Phone Number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn cancel" type="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button class="btn submit" type="submit" data-bs-dismiss="modal" aria-label="Close">Submit</button>
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
                    <p>${(item.price * item.quantity).toLocaleString('vi-VN', {
                        style: 'currency', currency: 'VND'
                    })}</p>
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

            const productCoupon = JSON.parse(sessionStorage.getItem('productCoupon') || '{}');
            const productCouponId = productCoupon.id || null;

            const shippingCoupon = JSON.parse(sessionStorage.getItem('shippingCoupon') || '{}');
            const shippingCouponId = shippingCoupon.id || null;

            const dataToSend = {
                cartItems,
                shipping_address_id: selectedShippingAddress,
                payment_method_id: selectedPaymentMethodId,
                coupon_id: productCouponId,
                shipping_coupon_id: shippingCouponId,
                discount_amount: parseFloat(sessionStorage.getItem('productDiscountAmount')) || 0,
                shipping_fee: parseFloat(sessionStorage.getItem('originalShippingFee')) || 0,
            };

            console.log("📦 Dữ liệu gửi đi:", dataToSend);

            fetch(placeOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: JSON.stringify(dataToSend)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('🛒 Đặt hàng thành công!');
                        localStorage.removeItem(`cartItems_${currentUser}`);

                        // ✅ CHỈ XÓA KHI ĐẶT HÀNG THÀNH CÔNG
                        sessionStorage.removeItem('shippingCoupon');
                        sessionStorage.removeItem('productCoupon');
                        window.location.href = '/orders';
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('❌ Lỗi fetch:', err);
                    alert('Lỗi khi gửi đơn hàng');
                });

            sessionStorage.removeItem('shippingCoupon');
            sessionStorage.removeItem('productCoupon');
        });
    </script>


@endsection
