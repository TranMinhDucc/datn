@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('content')

<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Cart</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                        <li class="breadcrumb-item active"> <a href="#">Cart</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="row g-4">
            <div class="col-12">
                <div class="cart-countdown"><img src="{{ asset('assets/client/images/gif/fire-2.gif') }}"
                        alt="">
                    <h6>Please, hurry! Someone has placed an order on one of the items you have in the cart. We'll keep
                        it for you for<span id="countdown"></span>minutes.</h6>
                </div>
            </div>
            <div class="col-xxl-9 col-xl-8">
                <div class="cart-table">
                    <div class="table-title">
                        <h5>Cart<span id="cartTitle">(0)</span></h5>
                        <button id="clearAllButton">Clear All</button>
                    </div>
                    <div class="table-responsive theme-scrollbar">
                        <table class="table" id="cart-table">
                            <thead>
                                <tr>
                                    <th>Product </th>
                                    <th>Price </th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">


                            </tbody>
                        </table>
                    </div>
                    <div class="no-data" id="data-show"><img src="{{ asset('assets/client/images/cart/1.gif') }}"
                            alt="">
                        <h4>You have nothing in your shopping cart!</h4>
                        <p>Today is a great day to purchase the things you have been holding onto! or <span>Carry on
                                Buying</span></p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4">
                <div class="cart-items">
                    <div class="cart-progress">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 43%"
                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                <span><i class="iconsax" data-icon="truck-fast"></i></span>
                            </div>
                        </div>
                        <p>Almost there, add <span id="free-shipping-remaining">$267.00</span> more to get <span>FREE Shipping !!</span></p>
                    </div>

                    <div class="cart-body">
                        <h6>Đơn hàng (<span id="item-count">0</span> đơn)</h6>
                        <ul>
                            <li>
                                <p>Tạm tính</p>
                                <span id="bag-total" data-original="0">
                                    0đ
                                </span>
                            </li>


                            <li>
                                <p class="mb-1 fw-medium text-secondary">Mã giảm giá</p>
                                <div id="coupon-discount" class="d-flex flex-column gap-1">
                                    <span class="badge rounded-pill bg-light border text-secondary px-3 py-2 d-inline-flex align-items-center gap-1" style="font-size: 14px;">
                                        🎫 Apply Coupon
                                    </span>
                                </div>
                            </li>

                        </ul>
                    </div>

                    <div class="cart-bottom">
                        <h6>Tạm tính <span id="subtotal">$0</span></h6>
                        <span> Thuế và phí vận chuyển sẽ được tính khi thanh toán</span>
                    </div>


                    <div class="coupon-box">
                        <h6>Coupon</h6>


                        <!-- Nút mở hộp chọn voucher -->
                        <!-- Nút mở modal -->
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#voucherShopeeModal">
                            🎫 Chọn mã Shopee Voucher
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="voucherShopeeModal" tabindex="-1" aria-labelledby="voucherShopeeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content rounded-3">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Chọn Shopee Voucher</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-1">
    @php
    $user = auth()->user();
    $isNewUser = false;

    if ($user && $user->registered_at) {
        $isNewUser = \Carbon\Carbon::parse($user->registered_at)->diffInDays(now()) <= 7;
    }
    @endphp

    <!-- FREESHIP -->
    <div class="voucher-section mb-4">
        <h6 class="text-muted fw-bold">Mã Miễn Phí Vận Chuyển</h6>

        @foreach($availableCoupons->where('type', 'shipping_discount') as $coupon)
            @php
                $onlyNew = (int) ($coupon->only_for_new_users ?? 0);
                $canUse = $coupon->usage_limit == 0 || $coupon->used_count < $coupon->usage_limit;
            @endphp

            @if ($coupon->active && $canUse && (!$onlyNew || ($onlyNew && $isNewUser)))
                <label class="voucher-card d-flex position-relative"
                    data-code="{{ $coupon->code ?? '' }}"
                    data-applicable-product-ids="{{ e(json_encode($coupon->applicable_product_ids ?? [])) }}"
                    data-applicable-category-ids="{{ e(json_encode($coupon->applicable_category_ids ?? [])) }}">

                    <!-- Ribbon bên trái -->
                    <div class="voucher-ribbon bg-info text-white text-center px-2 py-3 d-flex flex-column justify-content-center align-items-center" style="width: 100px;">
                        <div class="fw-bold" style="font-size: 13px; line-height: 1.2;">FREE SHIP</div>
                        <div class="fw-semibold" style="font-size: 11px;">TOÀN NGÀNH<br>HÀNG</div>
                    </div>

                    <!-- Nội dung chính -->
                    <div class="voucher-body flex-grow-1 px-3 py-2">
                        <div>Giảm tối đa {{ number_format($coupon->discount_value) }}đ</div>
                        <div class="text-muted small">Đơn tối thiểu {{ number_format($coupon->min_order_amount ?? 0) }}đ</div>

                        @if ($onlyNew)
                            <div class="badge bg-danger small mt-1">Dành cho bạn mới</div>
                        @endif

                        @if ($coupon->is_exclusive)
                            <div class="badge bg-secondary small mt-1">Không dùng chung</div>
                        @endif

                        <div class="text-muted small">
                            HSD: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
                            <a href="#" class="text-primary small">Điều kiện</a>
                        </div>
                    </div>

                    <!-- x10 góc trên phải -->
                    <div class="position-absolute top-0 end-0 text-danger small me-2 mt-2">x10</div>

                    <!-- Input ẩn -->
                    <input type="radio" class="form-check-input align-self-center me-3 coupon-radio"
                        data-id="{{ $coupon->id }}"
                        name="shipping_coupon"
                        value="{{ $coupon->code }}"
                        data-type="{{ $coupon->type }}"
                        data-value="{{ $coupon->discount_value }}"
                        data-value-type="{{ $coupon->value_type }}"
                        data-max-discount="{{ $coupon->max_discount_amount }}"
                        data-min-order="{{ $coupon->min_order_amount }}"
                        data-start-date="{{ $coupon->start_date }}"
                        data-end-date="{{ $coupon->end_date }}"
                        data-active="{{ $coupon->active }}"
                        data-only-for-new-users="{{ $coupon->only_for_new_users }}"
                        data-is-exclusive="{{ $coupon->is_exclusive }}"
                        data-applicable-product-ids='@json($coupon->applicable_product_ids ?? [])'
                        data-applicable-category-ids='@json($coupon->applicable_category_ids ?? [])'>
                </label>
            @endif
        @endforeach
    </div>

    <!-- GIẢM GIÁ -->
    <div class="voucher-section product-discount">
        <h6 class="text-muted fw-bold">Giảm Giá</h6>

        @foreach($availableCoupons->where('type', 'product_discount') as $coupon)
            @php
                $onlyNew = (int) ($coupon->only_for_new_users ?? 0);
                $canUse = $coupon->usage_limit == 0 || $coupon->used_count < $coupon->usage_limit;
            @endphp

            @if ($coupon->active && $canUse && (!$onlyNew || ($onlyNew && $isNewUser)))
                <label class="voucher-card d-flex position-relative"
                    data-code="{{ $coupon->code ?? '' }}"
                    data-applicable-product-ids="{{ e(json_encode($coupon->applicable_product_ids ?? [])) }}"
                    data-applicable-category-ids="{{ e(json_encode($coupon->applicable_category_ids ?? [])) }}">

                    <div class="voucher-ribbon bg-warning text-white text-center px-2 py-3 d-flex flex-column justify-content-center align-items-center" style="width: 100px;">
                        <i class="fa fa-shopping-bag fa-lg mb-1"></i>
                        <div class="fw-semibold small" style="font-size: 12px;">Khách hàng mới</div>
                    </div>

                    <div class="voucher-body flex-grow-1 px-3 py-2">
                        <div>
                            @if($coupon->value_type === 'percentage')
                                Giảm {{ $coupon->discount_value }}%
                            @else
                                Giảm {{ number_format($coupon->discount_value) }}đ
                            @endif
                        </div>
                        <div class="text-muted small">Đơn tối thiểu {{ number_format($coupon->min_order_amount ?? 0) }}đ</div>

                        @if ($onlyNew)
                            <div class="badge bg-danger small mt-1">Dành cho bạn mới</div>
                        @endif

                        @if ($coupon->is_exclusive)
                            <div class="badge bg-secondary small mt-1">Không dùng chung</div>
                        @endif

                        <div class="text-muted small">
                            HSD: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
                            <a href="#" class="text-primary small">Điều kiện</a>
                        </div>
                    </div>

                    <input type="radio" class="form-check-input align-self-center me-3 coupon-radio"
                        data-id="{{ $coupon->id }}"
                        name="product_coupon"
                        value="{{ $coupon->code }}"
                        data-type="{{ $coupon->type }}"
                        data-value="{{ $coupon->discount_value }}"
                        data-value-type="{{ $coupon->value_type }}"
                        data-max-discount="{{ $coupon->max_discount_amount }}"
                        data-min-order="{{ $coupon->min_order_amount }}"
                        data-start-date="{{ $coupon->start_date }}"
                        data-end-date="{{ $coupon->end_date }}"
                        data-active="{{ $coupon->active }}"
                        data-only-for-new-users="{{ $coupon->only_for_new_users }}"
                        data-is-exclusive="{{ $coupon->is_exclusive }}"
                        data-applicable-product-ids='@json($coupon->applicable_product_ids ?? [])'
                        data-applicable-category-ids='@json($coupon->applicable_category_ids ?? [])'>
                </label>
            @endif
        @endforeach
    </div>
</div>


                                    <div class="modal-footer border-0 justify-content-between">
                                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Trở lại</button>
                                        <button class="btn btn-danger" id="applySelectedCouponBtn">OK</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        @if (session()->has('coupon'))
                        <span id="coupon-data"
                            data-type="{{ session('coupon.type') }}"
                            data-value="{{ session('coupon.value') }}"
                            style="display:none;"></span>
                        @endif
                    </div>
                </div>


                <a class="btn btn_black w-100 rounded sm" href="{{ route('client.account.checkout') }}">Check Out</a>
            </div>
        </div>

        <div class="col-12">
            <div class="cart-slider">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h6>For a trendy and modern twist, especially during transitional seasons.</h6>
                        <p> <img class="me-2" src="{{ asset('assets/client/images/gif/discount.gif') }}"
                                alt="">You will
                            get 10% OFF on each product</p>
                    </div><a class="btn btn_outline sm rounded" href="product.html">View All
                        <svg>
                            <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use>
                        </svg></a>
                </div>
                <div class="swiper cart-slider-box">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="cart-box"> <a href="product.html"> <img
                                        src="{{ asset('assets/client/images/user/4.jpg') }}" alt=""></a>
                                <div> <a href="product.html">
                                        <h5>Polo-neck Body Dress</h5>
                                    </a>
                                    <h6>Sold By: <span>Brown Shop</span></h6>
                                    <div class="category-dropdown">
                                        <select class="form-select" name="carlist">
                                            <option value="">Best color</option>
                                            <option value="">White</option>
                                            <option value="">Black</option>
                                            <option value="">Green</option>
                                        </select>
                                    </div>
                                    <p>$19.90 <span>
                                            <del>$14.90 </del></span></p><a class="btn" href="#">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="cart-box"> <a href="product.html"> <img
                                        src="{{ asset('assets/client/images/user/5.jpg') }}" alt=""></a>
                                <div> <a href="product.html">
                                        <h5>Short Sleeve Sweater</h5>
                                    </a>
                                    <h6>Sold By: <span>Brown Shop</span></h6>
                                    <div class="category-dropdown">
                                        <select class="form-select" name="carlist">
                                            <option value="">Best color</option>
                                            <option value="">White</option>
                                            <option value="">Black</option>
                                            <option value="">Green</option>
                                        </select>
                                    </div>
                                    <p>$22.90 <span>
                                            <del>$24.90 </del></span></p><a class="btn" href="#">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="cart-box"> <a href="product.html"> <img
                                        src="{{ asset('assets/client/images/user/6.jpg') }}" alt=""></a>
                                <div> <a href="product.html">
                                        <h5>Oversized Cotton Short</h5>
                                    </a>
                                    <h6>Sold By: <span>Brown Shop</span></h6>
                                    <div class="category-dropdown">
                                        <select class="form-select" name="carlist">
                                            <option value="">Best color</option>
                                            <option value="">White</option>
                                            <option value="">Black</option>
                                            <option value="">Green</option>
                                        </select>
                                    </div>
                                    <p>$10.90 <span>
                                            <del>$18.90 </del></span></p><a class="btn" href="#">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="cart-box"> <a href="product.html"> <img
                                        src="{{ asset('assets/client/images/user/7.jpg') }}" alt=""></a>
                                <div> <a href="product.html">
                                        <h5>Oversized Women Shirt</h5>
                                    </a>
                                    <h6>Sold By: <span>Brown Shop</span></h6>
                                    <div class="category-dropdown">
                                        <select class="form-select" name="carlist">
                                            <option value="">Best color</option>
                                            <option value="">White</option>
                                            <option value="">Black</option>
                                            <option value="">Green</option>
                                        </select>
                                    </div>
                                    <p>$15.90 <span>
                                            <del>$20.90 </del></span></p><a class="btn" href="#">Add</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

@endsection
@section('js')
<script src="{{ asset('assets/client/js/cart-timer.js') }}"></script>
<script src="{{ asset('assets/client/js/cart.js') }}"></script>

<script>
        let currentUser = localStorage.getItem('currentUser') || 'guest';
        let cartKey = `cartItems_${currentUser}`;
        let cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];



        document.addEventListener('DOMContentLoaded', function() {
            const cartBody = document.getElementById('cart-body');
            const clearAllButton = document.getElementById('clearAllButton');

            function toggleEmptyMessage() {
                const emptyBox = document.getElementById('data-show');
                const cartTable = document.getElementById('cart-table');
                if (!cartItems.length) {
                    if (emptyBox) emptyBox.style.display = 'block';
                    if (cartTable) cartTable.style.display = 'none';
                } else {
                    if (emptyBox) emptyBox.style.display = 'none';
                    if (cartTable) cartTable.style.display = 'table';
                }
            }

            function saveAndRender() {
                localStorage.setItem(cartKey, JSON.stringify(cartItems));
                const storedCart = JSON.parse(localStorage.getItem(cartKey)) || [];
                cartItems.length = 0;
                cartItems.push(...storedCart);
                renderCart();
                updateCartTitle();
                renderMiniCart();
                toggleEmptyMessage();
                updateCartSummary();
            }

            function fullReload() {
                const storedCart = JSON.parse(localStorage.getItem(cartKey)) || [];
                cartItems.length = 0;
                cartItems.push(...storedCart);
                renderCart();
                updateCartTitle();
                renderMiniCart();
                toggleEmptyMessage();
            }

            fullReload();

            if (clearAllButton) {
                clearAllButton.addEventListener('click', () => {
                    const confirmed = confirm('Bạn có chắc muốn xoá toàn bộ giỏ hàng không?');
                    if (confirmed) {
                        localStorage.removeItem(cartKey);
                        cartItems.length = 0;
                        saveAndRender();
                    } else {
                        const storedCart = JSON.parse(localStorage.getItem(cartKey)) || [];
                        cartItems.length = 0;
                        cartItems.push(...storedCart);
                        saveAndRender();
                    }
                });
            }

            function renderCart() {
                const cartBody = document.getElementById('cart-body');
                const cartTable = document.getElementById('cart-table');
                if (!cartBody || !cartTable) return;
                cartBody.innerHTML = '';
                if (!cartItems.length) {
                    cartTable.style.display = 'none';
                    cartBody.innerHTML = `<tr><td colspan="5" class="text-center">Giỏ hàng đang trống.</td></tr>`;
                    return;
                }
                cartTable.style.display = 'table';
                cartItems.forEach((item, index) => {
                    const itemTotal = item.price * item.quantity;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
    <td><div class="cart-box">
    <a href="product.html"><img src="${item.image}" alt="" style="width: 90px; height: 90px; object-fit: cover; border-radius: 6px;"></a>
    <div><a href="product.html"><h5 class="mb-1">${item.name}</h5></a>
    <p class="mb-0">Brand: <span>${item.brand || 'Unknown'}</span></p>
    ${Object.entries(item.attributes || {}).map(([key, value]) => {
    return `<p class = "mb-0"> ${
                        key.charAt(0).toUpperCase() + key.slice(1)
                    }: <span> ${
                        value
                    }</span></p>`;
    }).join('')}</div></div></td>
    <td class="align-middle">$${item.price.toFixed(2)}</td>
    <td class="align-middle">
    <div class="quantity d-flex align-items-center gap-2">
    <button class="minus btn btn-sm btn-outline-secondary" data-index="${index}"><i class="fa-solid fa-minus"></i></button>
    <input type="number" value="${item.quantity}" min="1" max="99" data-index="${index}" class="form-control form-control-sm text-center quantity-input" style="width: 60px;">
    <button class="plus btn btn-sm btn-outline-secondary" data-index="${index}"><i class="fa-solid fa-plus"></i></button>
    </div></td>
    <td class="align-middle">$${itemTotal.toFixed(2)}</td>
    <td class="align-middle"><a class="deleteButton text-danger" href="javascript:void(0)" data-index="${index}"><i class="fa fa-trash"></i></a></td>`;
                    cartBody.appendChild(tr);
                });
                bindEvents();
                toggleEmptyMessage();
                updateCartSummary();
            }

            function updateCartSummary() {
                const cartKey = `cartItems_${localStorage.getItem('currentUser') || 'guest'}`;
                const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

                let bagTotal = 0;
                let itemCount = 0;

                cartItems.forEach(item => {
                    bagTotal += item.price * item.quantity;
                    itemCount += item.quantity;
                });

                const now = new Date();
                const $ = id => document.getElementById(id);

                let shippingDiscount = 0;
                let productDiscount = 0;
                let shippingHTML = '';
                let productHTML = '';

                function validateAndCalculate(coupon, icon) {
                    const {
                        code,
                        value,
                        value_type,
                        max_discount_amount,
                        min_order_amount,
                        start_date,
                        end_date,
                        active
                    } = coupon;

                    const start = new Date(start_date);
                    const end = new Date(end_date);

                    // ❌ Không hợp lệ
                    if (now < start || now > end || active !== 1 || bagTotal < min_order_amount) {
                        alert(`❌ Mã "${code}" không hợp lệ (hết hạn, không đủ điều kiện hoặc bị khoá).`);
                        return {
                            amount: 0,
                            html: ''
                        };
                    }

                    // ✅ Hợp lệ
                    let discount = 0;
                    if (value_type === 'percentage') {
                        discount = bagTotal * value / 100;
                        if (max_discount_amount) {
                            discount = Math.min(discount, max_discount_amount);
                        }
                    } else {
                        discount = value < 1000 ? value * 1000 : value;
                    }

                    const formatted = value_type === 'percentage' ?
                        `-${value}%` :
                        `-${discount.toLocaleString('vi-VN')}đ`;

                    const html = `
                <div class="text-danger d-flex align-items-center gap-1">
                    ${icon} <strong>${code}</strong>: ${formatted}
                </div>`;

                    return {
                        amount: discount,
                        html
                    };
                }

                // 🚚 Shipping Coupon
                const savedShipping = sessionStorage.getItem('shippingCoupon');
                if (savedShipping) {
                    const coupon = JSON.parse(savedShipping);
                    const result = validateAndCalculate(coupon, '🚚');
                    if (result.amount > 0) {
                        shippingDiscount = result.amount;
                        shippingHTML = result.html;
                    } else {
                        sessionStorage.removeItem('shippingCoupon');
                    }
                }

                // 🎁 Product Coupon
                const savedProduct = sessionStorage.getItem('productCoupon');
                if (savedProduct) {
                    const coupon = JSON.parse(savedProduct);
                    const result = validateAndCalculate(coupon, '🎁');
                    if (result.amount > 0) {
                        productDiscount = result.amount;
                        productHTML = result.html;
                    } else {
                        sessionStorage.removeItem('productCoupon');
                    }
                }

                const totalDiscount = shippingDiscount + productDiscount;
                const subtotal = Math.max(0, bagTotal - totalDiscount);

                if ($('bag-total')) {
                    $('bag-total').textContent = bagTotal.toLocaleString('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    });
                }

                if ($('item-count')) $('item-count').textContent = itemCount;

                if ($('coupon-discount')) {
                    if (!shippingHTML && !productHTML) {
                        $('coupon-discount').innerHTML = `🎫 Apply Coupon`;
                    } else {
                        $('coupon-discount').innerHTML = `
                    <div class="d-flex flex-column gap-1">
                        ${shippingHTML}
                        ${productHTML}
                    </div>
                `;
                    }
                }

                if ($('subtotal')) {
                    $('subtotal').textContent = subtotal.toLocaleString('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    });
                }

            }




            function bindEvents() {
                document.querySelectorAll('.plus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const i = this.dataset.index;
                        cartItems[i].quantity += 1;
                        saveAndRender();
                    });
                });
                document.querySelectorAll('.minus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const i = this.dataset.index;
                        if (cartItems[i].quantity > 1) {
                            cartItems[i].quantity -= 1;
                            saveAndRender();
                        }
                    });
                });
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const i = this.dataset.index;
                        const value = parseInt(this.value);
                        if (value > 0) {
                            cartItems[i].quantity = value;
                            saveAndRender();
                        }
                    });
                });
                document.querySelectorAll('.deleteButton').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const i = this.dataset.index;
                        cartItems.splice(i, 1);
                        saveAndRender();
                    });
                });
            }

            function updateCartTitle() {
                const totalOrders = cartItems.length;
                const cartTitle = document.getElementById('cartTitle');
                if (cartTitle) {
                    cartTitle.textContent = `(${totalOrders})`;
                }
            }

            function renderMiniCart() {
                const miniCart = document.querySelector('ul.offcanvas-cart');
                if (!miniCart) return;
                miniCart.innerHTML = '';
                if (!cartItems.length) {
                    miniCart.innerHTML = `<li class="text-center p-3">Giỏ hàng trống.</li>`;
                    return;
                }
                cartItems.forEach((item) => {
                    const quantity = Number(item.quantity) > 0 ? item.quantity : 1;
                    const attributeString = Object.entries(item.attributes || {})
                        .map(([k, v]) => `${k}:${v}`).join('|');
                    const key = `${item.id}_${attributeString}`;
                    const li = document.createElement('li');
                    li.innerHTML = `
    <a href="#"><img src="${item.image}" alt="" style="width: 70px; height: 70px; object-fit: cover;"></a>
    <div>
    <h6 class="mb-0">${item.name}</h6>
    <p class="mb-1">$${item.price.toLocaleString()}
    ${item.originalPrice ? `<del>$${item.originalPrice.toLocaleString()}</del>` : ''}
    <span class="btn-cart">$<span class="btn-cart__total">${(item.price * quantity).toLocaleString()}</span></span></p>
    ${Object.entries(item.attributes || {}).map(([key, value]) => {
    return ` < p class = "mb-1" > $ {
                        key
                    }: < span > $ {
                        value
                    } < /span></p > `;
    }).join('')}
    <div class="btn-containter">
    <div class="btn-control">
    <button class="btn-control__remove" data-key="${key}">−</button>
    <div class="btn-control__quantity">
    <div id="quantity-previous">${quantity > 1 ? quantity - 1 : ''}</div>
    <div id="quantity-current">${quantity}</div>
    <div id="quantity-next">${quantity + 1}</div>
    </div>
    <button class="btn-control__add" data-key="${key}">+</button>
    </div>
    </div>
    </div>
    <i class="fa fa-trash delete-icon" data-key="${key}" style="font-size: 18px; color: #888; cursor: pointer;"></i>`;
                    li.querySelector('.delete-icon').addEventListener('click', function() {
                        const key = this.dataset.key;
                        cartItems = cartItems.filter(p => `${p.id}_${p.size}_${p.color}` !== key);
                        saveAndRender();
                    });
                    li.querySelector('.btn-control__add').addEventListener('click', function() {
                        const key = this.dataset.key;
                        const item = cartItems.find(p => {
                            const attrStr = Object.entries(p.attributes || {})
                                .map(([k, v]) => `${k}:${v}`).join('|');
                            return `${p.id}_${attrStr}` === key;
                        });
                        if (item) {
                            item.quantity += 1;
                            saveAndRender();
                        }
                    });
                    li.querySelector('.btn-control__remove').addEventListener('click', function() {
                        const key = this.dataset.key;
                        const item = cartItems.find(p => {
                            const attrStr = Object.entries(p.attributes || {})
                                .map(([k, v]) => `${k}:${v}`).join('|');
                            return `${p.id}_${attrStr}` === key;
                        });
                        if (item && item.quantity > 1) {
                            item.quantity -= 1;
                            saveAndRender();
                        }
                    });
                    miniCart.appendChild(li);
                });
            }


        });

        document.addEventListener('DOMContentLoaded', function() {

            const voucherModal = document.getElementById('voucherShopeeModal');
            if (voucherModal) {
                voucherModal.addEventListener('shown.bs.modal', () => {
                    setTimeout(() => {
                        console.log("🔁 Gọi lại filter sau khi modal render");
                        filterVouchersByCart();
                    }, 100);
                });
            }

            let currentUser = localStorage.getItem('currentUser') || 'guest';
            let cartKey = `cartItems_${currentUser}`;
            let cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

            function updateCartSummary() {
                const cartKey = `cartItems_${localStorage.getItem('currentUser') || 'guest'}`;
                const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

                let bagTotal = 0;
                let itemCount = 0;
                cartItems.forEach(item => {
                    bagTotal += item.price * item.quantity;
                    itemCount += item.quantity;
                });

                const now = new Date();
                const $ = id => document.getElementById(id);

                let shippingDiscount = 0;
                let productDiscount = 0;
                let shippingHTML = '';
                let productHTML = '';

                // ✅ Hàm phụ: format hiển thị
                function formatDisplay(coupon, amount) {
                    return coupon.value_type === 'percentage' ?
                        `-${coupon.value}%` :
                        `-${amount.toLocaleString('vi-VN')}đ`;
                }

                // ✅ Hàm phụ: tính toán giảm giá
                function calculateDiscount(coupon, total) {
                    let discount = 0;
                    if (coupon.value_type === 'percentage') {
                        discount = total * coupon.value / 100;
                        if (coupon.max_discount_amount) {
                            discount = Math.min(discount, coupon.max_discount_amount);
                        }
                    } else {
                        discount = coupon.value;
                    }
                    return discount;
                }

                // 🚚 Mã giảm giá vận chuyển
                const freeship = sessionStorage.getItem('shippingCoupon');
                if (freeship) {
                    const coupon = JSON.parse(freeship);
                    const {
                        code,
                        start_date,
                        end_date,
                        active,
                        min_order_amount
                    } = coupon;
                    const start = new Date(start_date);
                    const end = new Date(end_date);

                    if (now < start || now > end || active !== 1 || bagTotal < min_order_amount) {
                        alert(`❌ Mã Freeship "${code}" không hợp lệ.`);
                        sessionStorage.removeItem('shippingCoupon');
                    } else {
                        shippingDiscount = calculateDiscount(coupon, bagTotal);
                        shippingHTML = `
                    <div class="text-danger">
                        🚚 <strong>${code}</strong>: ${formatDisplay(coupon, shippingDiscount)}
                    </div>`;
                    }
                }

                // 🎁 Mã giảm giá sản phẩm
                const product = sessionStorage.getItem('productCoupon');
                if (product) {
                    const coupon = JSON.parse(product);
                    const {
                        code,
                        start_date,
                        end_date,
                        active,
                        min_order_amount,
                        only_for_new_users,
                        applicable_product_ids = [],
                        applicable_category_ids = []
                    } = coupon;

                    const productIdsInCoupon = applicable_product_ids.map(String);
                    const categoryIdsInCoupon = applicable_category_ids.map(String);

                    const start = new Date(start_date);
                    const end = new Date(end_date);

                    let isNewUser = true;
                    if (only_for_new_users === 1) {
                        const createdAt = localStorage.getItem('userCreatedAt');
                        if (createdAt) {
                            const created = new Date(createdAt);
                            const diffDays = (now - created) / (1000 * 60 * 60 * 24);
                            isNewUser = diffDays <= 7;
                        }
                    }

                    // ✅ Kiểm tra mã không còn phù hợp với giỏ hàng hiện tại
                    const hasIncompatibleProduct = productIdsInCoupon.length > 0 &&
                        cartItems.some(item => !productIdsInCoupon.includes(String(item.id)));

                    const hasIncompatibleCategory = categoryIdsInCoupon.length > 0 &&
                        cartItems.some(item => !categoryIdsInCoupon.includes(String(item.category_id)));


                    const isInvalid = now < start || now > end || active !== 1 ||
                        bagTotal < min_order_amount ||
                        (only_for_new_users === 1 && !isNewUser) ||
                        hasIncompatibleProduct ||
                        hasIncompatibleCategory;
                    console.log('📦 Cart Items:', cartItems.map(i => i.id));
                    console.log('🎫 Mã áp dụng sản phẩm:', productIdsInCoupon);
                    console.log('🎫 Mã áp dụng danh mục:', categoryIdsInCoupon);
                    if (isInvalid) {
                        alert(`❌ Mã sản phẩm "${code}" không còn hợp lệ với giỏ hàng.`);
                        sessionStorage.removeItem('productCoupon');
                    } else {
                        productDiscount = calculateDiscount(coupon, bagTotal);
                        productHTML = `
                <div class="text-danger">
                    🎁 <strong>${code}</strong>: ${formatDisplay(coupon, productDiscount)}
                </div>`;
                    }
                }


                const subtotal = Math.max(0, bagTotal - shippingDiscount - productDiscount);
                sessionStorage.setItem('subtotalAfterProductDiscount', subtotal); // cho hiển thị subtotal
                sessionStorage.setItem('productDiscountAmount', productDiscount); // lưu lại giá trị giảm sản phẩm
                sessionStorage.setItem('shippingDiscountAmount', shippingDiscount);


                if ($('bag-total')) {
                    $('bag-total').textContent = bagTotal.toLocaleString('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    });
                }

                if ($('coupon-discount')) {
                    $('coupon-discount').innerHTML = (shippingHTML + productHTML) || '🎫 Apply Coupon';
                }

                if ($('subtotal')) {
                    $('subtotal').textContent = subtotal.toLocaleString('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    });
                }

                if ($('item-count')) {
                    $('item-count').textContent = itemCount;
                }


            }




            // ✅ Hàm phụ để tính giảm theo phần trăm hoặc cố định
            function calculateDiscount(coupon) {
                let itemsToApply = [...cartItems];

                // Lọc theo sản phẩm
                if (coupon.applicable_product_ids?.length) {
                    itemsToApply = itemsToApply.filter(item =>
                        coupon.applicable_product_ids.includes(item.id)
                    );
                }

                // Lọc theo danh mục
                if (coupon.applicable_category_ids?.length) {
                    itemsToApply = itemsToApply.filter(item =>
                        coupon.applicable_category_ids.includes(item.category_id)
                    );
                }

                const total = itemsToApply.reduce((sum, item) => sum + item.price * item.quantity, 0);

                if (total < coupon.min_order_amount) return 0;

                let discount = 0;
                if (coupon.value_type === 'percentage') {
                    discount = total * coupon.value / 100;
                    if (coupon.max_discount_amount) {
                        discount = Math.min(discount, coupon.max_discount_amount);
                    }
                } else {
                    discount = coupon.value;
                }

                return Math.floor(discount);
            }



            // ✅ Xử lý click nút chọn mã
            const applyBtn = document.getElementById('applySelectedCouponBtn');
            if (applyBtn) {
                applyBtn.addEventListener('click', function() {
                    const shippingCoupon = document.querySelector('input[name="shipping_coupon"]:checked');
                    const productCoupon = document.querySelector('input[name="product_coupon"]:checked');



                    // ✅ Kiểm tra không dùng chung
                    const isExclusiveShipping = shippingCoupon ? Number(shippingCoupon.dataset.isExclusive || 0) : 0;
                    const isExclusiveProduct = productCoupon ? Number(productCoupon.dataset.isExclusive || 0) : 0;

                    if ((shippingCoupon && productCoupon) && (isExclusiveShipping || isExclusiveProduct)) {
                        alert('❌ Mã này không được dùng chung với mã khác.');
                        return;
                    }

                    // ✅ Xoá mã cũ trước khi lưu (phòng khi chọn lại)
                    sessionStorage.removeItem('shippingCoupon');
                    sessionStorage.removeItem('productCoupon');

                    // ✅ Lưu mã Freeship (nếu có)
                    if (shippingCoupon) {
                        sessionStorage.setItem('shippingCoupon', JSON.stringify({
                            id: Number(shippingCoupon.dataset.id),
                            code: shippingCoupon.value,
                            value: parseFloat(shippingCoupon.dataset.value),
                            value_type: shippingCoupon.dataset.valueType,
                            max_discount_amount: Number(shippingCoupon.dataset.maxDiscount),
                            min_order_amount: Number(shippingCoupon.dataset.minOrder),
                            start_date: shippingCoupon.dataset.startDate,
                            end_date: shippingCoupon.dataset.endDate,
                            active: Number(shippingCoupon.dataset.active),
                            only_for_new_users: Number(shippingCoupon.dataset.onlyForNewUsers),
                            is_exclusive: Number(shippingCoupon.dataset.isExclusive || 0),
                            applicable_product_ids: JSON.parse(shippingCoupon.dataset.applicableProductIds || '[]'),
                            applicable_category_ids: JSON.parse(shippingCoupon.dataset.applicableCategoryIds || '[]')
                        }));
                    }

                    // ✅ Lưu mã Giảm giá sản phẩm (nếu có)
                    if (productCoupon) {
                        sessionStorage.setItem('productCoupon', JSON.stringify({
                            id: Number(productCoupon.dataset.id),
                            code: productCoupon.value,
                            value: parseFloat(productCoupon.dataset.value),
                            value_type: productCoupon.dataset.valueType,
                            max_discount_amount: Number(productCoupon.dataset.maxDiscount),
                            min_order_amount: Number(productCoupon.dataset.minOrder),
                            start_date: productCoupon.dataset.startDate,
                            end_date: productCoupon.dataset.endDate,
                            active: Number(productCoupon.dataset.active),
                            only_for_new_users: Number(productCoupon.dataset.onlyForNewUsers),
                            is_exclusive: Number(productCoupon.dataset.isExclusive || 0),
                            applicable_product_ids: JSON.parse(productCoupon.dataset.applicableProductIds || '[]'),
                            applicable_category_ids: JSON.parse(productCoupon.dataset.applicableCategoryIds || '[]')
                        }));
                    }

                    // ✅ Đóng modal sau khi áp dụng
                    const modalEl = document.getElementById('voucherShopeeModal');
                    let modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (!modalInstance) modalInstance = new bootstrap.Modal(modalEl);
                    modalInstance.hide();

                    updateCartSummary();
                });

            }

            // ✅ Hiển thị lại nếu có mã đã lưu
            const couponSpan = document.getElementById('coupon-discount');
            const saved = sessionStorage.getItem('appliedCoupon');
            if (saved && couponSpan) {
                const {
                    code,
                    value
                } = JSON.parse(saved);
                const v = Number(value);
                couponSpan.innerHTML = `
                <span class="badge bg-light border text-danger px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                    🎫 <strong>${code}</strong> <span>(-${v.toLocaleString('vi-VN')}đ)</span>
                </span>`;
                updateCartSummary();
            }

            // ✅ Cho phép click để huỷ mã
            if (couponSpan) {
                couponSpan.addEventListener('click', function() {
                    if (sessionStorage.getItem('appliedCoupon')) {
                        if (confirm("Bạn muốn huỷ mã giảm giá này?")) {
                            sessionStorage.removeItem('appliedCoupon');
                            couponSpan.innerHTML = '🎫 Apply Coupon';
                            updateCartSummary();
                        }
                    }
                });
            }

            // ✅ Gọi ban đầu
            updateCartSummary();

            let lastCheckedRadio = null;

            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('click', function(e) {
                    // Nếu click lại cùng radio đang chọn thì huỷ chọn
                    if (lastCheckedRadio === this) {
                        this.checked = false;
                        lastCheckedRadio = null;
                    } else {
                        lastCheckedRadio = this;
                    }
                });
            });

            function getCartItemsFromLocalStorage() {
                const currentUser = localStorage.getItem('currentUser') || 'guest';
                const cartKey = `cartItems_${currentUser}`;
                return JSON.parse(localStorage.getItem(cartKey)) || [];
            }

            function filterVouchersByCart() {
                const cartItems = getCartItemsFromLocalStorage();
                const productIdsInCart = cartItems.map(item => String(item.id));
                const categoryIdsInCart = cartItems.map(item => String(item.category_id));

                // ✅ LỌC MÃ GIẢM GIÁ SẢN PHẨM
                const productVoucherCards = document.querySelectorAll('.voucher-section.product-discount .voucher-card[data-applicable-product-ids]');
                let hasMatchingProductVoucher = false;

                productVoucherCards.forEach(voucher => {
                    const applicableProductIds = JSON.parse(voucher.dataset.applicableProductIds || '[]').map(String);
                    const applicableCategoryIds = JSON.parse(voucher.dataset.applicableCategoryIds || '[]').map(String);

                    const productMatch = applicableProductIds.length === 0 ||
                        productIdsInCart.every(id => applicableProductIds.includes(id));

                    const categoryMatch = applicableCategoryIds.length === 0 ||
                        categoryIdsInCart.every(id => applicableCategoryIds.includes(id));

                    const isMatch = productMatch && categoryMatch;

                    voucher.style.setProperty('display', isMatch ? 'flex' : 'none', 'important');
                    if (isMatch) hasMatchingProductVoucher = true;
                });

                const productVoucherSection = document.querySelector('.voucher-section.product-discount');
                if (productVoucherSection) {
                    productVoucherSection.style.display = hasMatchingProductVoucher ? 'block' : 'none';
                }

                // ✅ LỌC MÃ MIỄN PHÍ VẬN CHUYỂN
                const shippingVoucherCards = document.querySelectorAll('.voucher-section .voucher-card[data-applicable-product-ids]');
                let hasMatchingShippingVoucher = false;

                shippingVoucherCards.forEach(voucher => {
                    const applicableProductIds = JSON.parse(voucher.dataset.applicableProductIds || '[]').map(String);
                    const applicableCategoryIds = JSON.parse(voucher.dataset.applicableCategoryIds || '[]').map(String);

                    const productMatch = applicableProductIds.length === 0 ||
                        productIdsInCart.every(id => applicableProductIds.includes(id));

                    const categoryMatch = applicableCategoryIds.length === 0 ||
                        categoryIdsInCart.every(id => applicableCategoryIds.includes(id));

                    const isMatch = productMatch && categoryMatch;

                    voucher.style.setProperty('display', isMatch ? 'flex' : 'none', 'important');
                    if (isMatch) hasMatchingShippingVoucher = true;
                });

                const shippingVoucherSection = document.querySelector('.voucher-section.mb-4');
                if (shippingVoucherSection) {
                    shippingVoucherSection.style.display = hasMatchingShippingVoucher ? 'block' : 'none';
                }

                console.log("✅ Đã lọc xong cả product + shipping voucher");
            }

        });

        document.getElementById('applySelectedCouponBtn').addEventListener('click', function() {
            const selectedProductCoupon = document.querySelector('input[name="product_coupon"]:checked');
            const selectedShippingCoupon = document.querySelector('input[name="shipping_coupon"]:checked');

            if (selectedProductCoupon) {
                const couponData = {
                    id: parseInt(selectedProductCoupon.dataset.id), // ✅ lấy ID
                    code: selectedProductCoupon.value,
                    value: parseFloat(selectedProductCoupon.dataset.value),
                    value_type: selectedProductCoupon.dataset.valueType,
                    max_discount_amount: parseFloat(selectedProductCoupon.dataset.maxDiscount),
                    min_order_amount: parseFloat(selectedProductCoupon.dataset.minOrder),
                    // các thông tin khác nếu cần
                };
                sessionStorage.setItem('productCoupon', JSON.stringify(couponData));
            }

            if (selectedShippingCoupon) {
                const shippingData = {
                    id: parseInt(selectedShippingCoupon.dataset.id), // ✅ lấy ID
                    code: selectedShippingCoupon.value,
                    value: parseFloat(selectedShippingCoupon.dataset.value),
                    value_type: selectedShippingCoupon.dataset.valueType,
                    max_discount_amount: parseFloat(selectedShippingCoupon.dataset.maxDiscount),
                    min_order_amount: parseFloat(selectedShippingCoupon.dataset.minOrder),
                    // các thông tin khác nếu cần
                };
                sessionStorage.setItem('shippingCoupon', JSON.stringify(shippingData));
            }

            // đóng modal sau khi chọn
            const modal = bootstrap.Modal.getInstance(document.getElementById('voucherShopeeModal'));
            modal.hide();
        });
</script>





@endsection