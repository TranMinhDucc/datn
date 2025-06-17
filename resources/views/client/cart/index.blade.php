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
                        <h6>Đơn hàng  (<span id="item-count">0</span> đơn)</h6>
                        <ul>
                            <li>
                                <p>	Tạm tính </p><span id="bag-total">0</span>
                            </li>
                            <li>
                                <p>Giảm giá </p><span id="bag-savings" class="theme-color">-$20.00</span>
                            </li>
                            <li>
                                <p>Mã giảm giá </p><span id="coupon-discount" class="Coupon">Apply Coupon</span>
                            </li>
                            <li>
                                <p>Phí vận chuyển </p><span id="delivery-fee">$50.00</span>
                            </li>
                        </ul>
                    </div>

                    <div class="cart-bottom">
                        <p><i class="iconsax me-1" data-icon="tag-2"></i>Ưu đãi đặc biệt (<span id="special-offer">-$1.49</span>)</p>
                        <h6>Tạm tính <span id="subtotal">$0</span></h6>
                        <span>	Thuế và phí vận chuyển sẽ được tính khi thanh toán</span>
                    </div>

                    <div class="coupon-box">
                        <h6>Coupon</h6>
                        <ul>
                            <li>
                                <span>
                                    <input type="text" id="coupon-input" placeholder="Apply Coupon">
                                    <i class="iconsax me-1" data-icon="tag-2"></i>
                                </span>
                                <button class="btn" id="apply-coupon-btn">Apply</button>
                            </li>
                            <li>
                                @guest
                                <span>
                                    <a class="theme-color" href="{{ route('login') }}">Login</a> to see best coupon for you
                                </span>
                                @endguest

                            </li>
                        </ul>
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

        fullReload(); // Gọi khi load lần đầu

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
            if (!cartBody || !cartTable) {
                console.warn('⛔ cart-body hoặc cart-table không tồn tại trong DOM');
                return;
            }

            cartBody.innerHTML = '';

            if (!cartItems.length) {
                cartTable.style.display = 'none'; // Ẩn bảng
                cartBody.innerHTML = `<tr><td colspan="5" class="text-center">Giỏ hàng đang trống.</td></tr>`;
                return;
            }

            cartTable.style.display = 'table'; // Hiện lại bảng khi có hàng

            cartItems.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                const tr = document.createElement('tr');
                tr.innerHTML = `
        <td>
            <div class="cart-box">
                <a href="product.html">
                    <img src="${item.image}" alt="" style="width: 90px; height: 90px; object-fit: cover; border-radius: 6px;">
                </a>
                <div>
                    <a href="product.html"><h5 class="mb-1">${item.name}</h5></a>
                    <p class="mb-0">Sold By: <span>${item.seller || 'Unknown'}</span></p>
                    <p class="mb-0">Size: <span>${item.size || 'Default'}</span></p>
                    <p class="mb-0">Color: <span>${item.color || 'Default'}</span></p>
                </div>
            </div>
        </td>
        <td class="align-middle">$${item.price.toFixed(2)}</td>
        <td class="align-middle">
            <div class="quantity d-flex align-items-center gap-2">
                <button class="minus btn btn-sm btn-outline-secondary" data-index="${index}">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number" value="${item.quantity}" min="1" max="99"
                    data-index="${index}" class="form-control form-control-sm text-center quantity-input" style="width: 60px;">
                <button class="plus btn btn-sm btn-outline-secondary" data-index="${index}">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </td>
        <td class="align-middle">$${itemTotal.toFixed(2)}</td>
        <td class="align-middle">
            <a class="deleteButton text-danger" href="javascript:void(0)" data-index="${index}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
        `;
                cartBody.appendChild(tr);
            });

            bindEvents();
            toggleEmptyMessage(); // Luôn gọi sau khi render
            updateCartSummary(); // ← thêm dòng này sau render

            console.log('[renderCart] Items count:', cartItems.length);
            console.log('[renderCart] Target:', document.getElementById('cart-body'));

        }


        function updateCartSummary() {
            let bagTotal = 0;
            let itemCount = 0;

            cartItems.forEach(item => {
                const total = item.price * item.quantity;
                bagTotal += total;
                itemCount += item.quantity;
            });

            const savings = 20.00;
            const delivery = bagTotal >= 300 ? 0 : 50.00;
            const specialOffer = 1.49;
            const coupon = 0.00;

            const subtotal = bagTotal - savings - coupon + delivery - specialOffer;

            const $ = (id) => document.getElementById(id);
            if (!$('bag-total')) return; // bảo vệ nếu phần bên phải chưa hiển thị

            $('bag-total').textContent = `$${bagTotal.toFixed(2)}`;
            $('item-count').textContent = itemCount;
            $('bag-savings').textContent = `-$${savings.toFixed(2)}`;
            $('delivery-fee').textContent = `$${delivery.toFixed(2)}`;
            $('special-offer').textContent = `-$${specialOffer.toFixed(2)}`;
            $('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            $('coupon-discount').textContent = coupon > 0 ? `-$${coupon.toFixed(2)}` : 'Apply Coupon';

            // Tính số tiền còn thiếu để được free shipping
            const threshold = 300;
            const remaining = Math.max(0, threshold - bagTotal);
            $('free-shipping-remaining').textContent = `$${remaining.toFixed(2)}`;
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
                const key = `${item.id}_${item.size}_${item.color}`;
                const li = document.createElement('li');

                li.innerHTML = `
        <a href="#"><img src="${item.image}" alt="" style="width: 70px; height: 70px; object-fit: cover;"></a>
        <div>
            <h6 class="mb-0">${item.name}</h6>
            <p class="mb-1">$${item.price.toLocaleString()}
                ${item.originalPrice ? `<del>$${item.originalPrice.toLocaleString()}</del>` : ''}
                <span class="btn-cart">$<span class="btn-cart__total">${(item.price * quantity).toLocaleString()}</span></span>
            </p>
            <p class="mb-1">Size: <span>${item.size}</span></p>
            <p class="mb-2">Color: <span>${item.color}</span></p>
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
        <i class="fa fa-trash delete-icon" data-key="${key}" style="font-size: 18px; color: #888; cursor: pointer;"></i>
        `;

                li.querySelector('.delete-icon').addEventListener('click', function() {
                    const key = this.dataset.key;
                    cartItems = cartItems.filter(p => `${p.id}_${p.size}_${p.color}` !== key);
                    saveAndRender();
                });

                li.querySelector('.btn-control__add').addEventListener('click', function() {
                    const key = this.dataset.key;
                    const item = cartItems.find(p => `${p.id}_${p.size}_${p.color}` === key);
                    if (item) {
                        item.quantity += 1;
                        saveAndRender();
                    }
                });

                li.querySelector('.btn-control__remove').addEventListener('click', function() {
                    const key = this.dataset.key;
                    const item = cartItems.find(p => `${p.id}_${p.size}_${p.color}` === key);
                    if (item && item.quantity > 1) {
                        item.quantity -= 1;
                        saveAndRender();
                    }
                });

                miniCart.appendChild(li);
            });
        }

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




    });
</script>


@endsection