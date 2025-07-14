<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/client/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/client/images/favicon.png') }}" type="image/x-icon">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/vendors/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/vendors/iconsax.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/vendors/swiper-slider/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/vendors/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .toast-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            background: #dc3545;
            color: white;
            font-weight: 500;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            min-width: 260px;
            max-width: 300px;
            animation: fade-in 0.3s ease;

        }

        .toast-box .icon {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .toast-box .close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            font-weight: bold;
        }

        .toast-box .icon span:first-child {
            font-size: 18px;
            opacity: 0.9;
        }

        .toast-box .icon span:last-child {
            color: #ffffff;
            font-size: 14px;
        }
    </style>
    @yield('style')
</head>

<script>
    @auth
    localStorage.setItem('currentUser', '{{ auth()->user()->id }}');
    @else
        localStorage.setItem('currentUser', 'guest');
    @endauth
</script>

<script>
    @auth
    const userId = '{{ auth()->user()->id }}';
    const guestKey = 'cartItems_guest';
    const userKey = `cartItems_${userId}`;

    const guestCart = JSON.parse(localStorage.getItem(guestKey)) || [];
    const userCart = JSON.parse(localStorage.getItem(userKey)) || [];

    // Hàm merge
    function mergeCarts(userCart, guestCart) {
        guestCart.forEach(gItem => {
            const index = userCart.findIndex(
                uItem => uItem.id === gItem.id && uItem.size === gItem.size && uItem.color === gItem.color
            );

            if (index !== -1) {
                userCart[index].quantity += gItem.quantity;
            } else {
                userCart.push(gItem);
            }
        });

        return userCart;
    }

    const mergedCart = mergeCarts(userCart, guestCart);

    localStorage.setItem(userKey, JSON.stringify(mergedCart));
    localStorage.removeItem(guestKey); // xoá cart guest
    localStorage.setItem('currentUser', userId); // cập nhật currentUser
    @endauth
</script>

<body>
    <div class="tap-top">
        <div><i class="fa-solid fa-angle-up"></i></div>
    </div><span class="cursor"><span class="cursor-move-inner"><span class="cursor-inner"></span></span><span
            class="cursor-move-outer"><span class="cursor-outer"></span></span></span>
    <header>
        @include('layouts.partials.client.top-header')
        @include('layouts.partials.client.header')

    </header>
    @yield('content')
    @include('layouts.partials.client.footer')
    <div class="modal theme-modal fade" id="quick-view" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xs-12">
                            <div class="quick-view-img">
                                <div class="swiper modal-slide-1">
                                    <div class="swiper-wrapper ratio_square-2">
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/1.jpg') }}" alt="">
                                        </div>
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/2.jpg') }}" alt="">
                                        </div>
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/3.jpg') }}" alt="">
                                        </div>
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/4.jpg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper modal-slide-2">
                                    <div class="swiper-wrapper ratio3_4">
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/5.jpg') }}" alt="">
                                        </div>
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/6.jpg') }}" alt="">
                                        </div>
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/7.jpg') }}" alt="">
                                        </div>
                                        <div class="swiper-slide"><img class="bg-img"
                                                src="{{ asset('assets/client/images/pro/8.jpg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 rtl-text">
                            <div class="product-right">
                                <h3>Women Pink Shirt</h3>
                                <h5>$32.96
                                    <del>$50.12</del>
                                </h5>
                                <ul class="color-variant">
                                    <li class="bg-color-brown"></li>
                                    <li class="bg-color-chocolate"></li>
                                    <li class="bg-color-coffee"></li>
                                    <li class="bg-color-black"></li>
                                </ul>
                                <div class="border-product">
                                    <h6>Product details</h6>
                                    <p>Western yoke on an Indigo shirt made of 100% cotton. Ideal for informal
                                        gatherings, this top will ensure your comfort and style throughout the day.</p>
                                </div>
                                <div class="product-description">
                                    <div class="size-box">
                                        <ul>
                                            <li class="active"><a href="#">s</a></li>
                                            <li><a href="#">m</a></li>
                                            <li><a href="#">l</a></li>
                                            <li><a href="#">xl</a></li>
                                        </ul>
                                    </div>
                                    <h6 class="product-title">Quantity</h6>
                                    <div class="quantity">
                                        <button class="minus" type="button"><i
                                                class="fa-solid fa-minus"></i></button>
                                        <input type="number" value="1" min="1" max="20">
                                        <button class="plus" type="button"><i
                                                class="fa-solid fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="product-buttons"><a class="btn btn-solid" href="cart.html">Add to
                                        cart</a><a class="btn btn-solid" href="product.html">View detail</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal theme-modal fade cart-modal" id="addtocart" tabindex="-1" role="dialog" aria-modal="true">
        @include('layouts.partials.client.model')
    </div>
    <div class="offcanvas offcanvas-end shopping-details" id="offcanvasRight" tabindex="-1"
        aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" id="offcanvasRightLabel">Shopping Cart</h4>
            <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body theme-scrollbar">
            <ul class="offcanvas-cart">

            </ul>
        </div>
        <div class="offcanvas-footer">
            <p>Spend <span>$ 14.81 </span>more and enjoy <span>FREE SHIPPING!</span></p>
            <div class="footer-range-slider">
                <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="46"
                    aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-striped progress-bar-animated theme-default"
                        style="width: 46%"></div>
                </div>
            </div>
            <div class="price-box">
                <h6>Tổng :</h6>
                <p>$ 49.59 USD</p>
            </div>
            <div class="cart-button"> <a class="btn btn_outline" href="{{ route('client.cart.index') }}"> View
                    Cart</a><a class="btn btn_black" href="check-out.html"> Checkout</a></div>
        </div>
    </div>
    <div class="offcanvas offcanvas-top search-details" id="offcanvasTop" tabindex="-1"
        aria-labelledby="offcanvasTopLabel">
        <div class="offcanvas-header">
            <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="offcanvas-body theme-scrollbar">
            <div class="container">
                <h3>What are you trying to find?</h3>
                <div class="search-box">
                    <input type="search" name="text" placeholder="I'm looking for…"><i class="iconsax"
                        data-icon="search-normal-2"></i>
                </div>
                <h4>Popular Searches</h4>
                <ul class="rapid-search">
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Jeans
                            Women</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Blazer Women</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Jeans
                            Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Blazer Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>T-Shirts Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Shoes
                            Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>T-Shirts Women</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Bags</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Sneakers Women</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Dresses</a></li>
                </ul>
                <h4>Có thể bạn sẽ thích</h4>
                <div class="row gy-4 ratio_square-2 preemptive-search">
                    @foreach ($recommendedProducts as $item)
                        <div class="col-xl-2 col-sm-4 col-6">
                            <div class="product-box-6">
                                <div class="img-wrapper">
                                    <div class="product-image">
                                        <a href="{{ route('client.products.show', $item->slug) }}">
                                            <img class="bg-img" src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->name }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="product-detail">
                                    <div>
                                        <a href="{{ route('client.products.show', $item->slug) }}">
                                            <h6>{{ $item->name }}</h6>
                                        </a>
                                        <p>{{ number_format($item->sale_price ?? $item->base_price, 0, ',', '.') }}₫
                                        </p>
                                        <ul class="rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            <li><i class="fa-regular fa-star"></i></li>
                                            <li>4+</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <div class="theme-btns">
        <button class="btntheme" id="dark-btn"><i class="fa-regular fa-moon"></i>
            <div class="text">Dark</div>
        </button>
        <button class="btntheme rtlBtnEl" id="rtl-btn"><i class="fa-solid fa-repeat"></i>
            <div class="rtl">Rtl</div>
        </button>
    </div>

    <script src="{{ asset('assets/client/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/iconsax.js') }}"></script>
    <script src="{{ asset('assets/client/js/stats.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/cursor.js') }}"></script>


    <!-- Swiper Slider -->
    <script src="{{ asset('assets/client/js/swiper-slider/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/swiper-slider/swiper-custom.js') }}"></script>
    <script src="{{ asset('assets/client/js/countdown.js') }}"></script>
    <script src="{{ asset('assets/client/js/touchspin.js') }}"></script>
    <script src="{{ asset('assets/client/js/cookie.js') }}"></script>
    <script src="{{ asset('assets/client/js/toastify.js') }}"></script>
    <script src="{{ asset('assets/client/js/theme-setting.js') }}"></script>
    @yield('js')
    <script src="{{ asset('assets/client/js/script.js') }}"></script>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.4/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- SweetAlert2 JS (bắt buộc để Swal.fire hoạt động) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            let timerInterval;
            let timeout = 3000;
            let action = "{{ session('action') }}";

            if (action === "register") timeout = 5000;
            else if (action === "logout") timeout = 600;
            else if (action === "reset") timeout = 4000;

            Swal.fire({
                title: "🎉 {{ session('success') }}",
                html: "Sẽ tự đóng trong <b></b> ms.",
                timer: timeout,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = Swal.getTimerLeft();
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            });
        </script>
    @endif

    <div id="toast-container"
        style="
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
">
    </div>
</body>

<script>
    // ================================
    // 1. BIẾN TOÀN CỤC
    // ================================
    const cartList = document.querySelector('.offcanvas-cart');
    let currentUser = localStorage.getItem('currentUser') || 'guest';
    let cartKey = `cartItems_${currentUser}`;
    let cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

    // ================================
    // 2. HÀM CHÍNH
    // ================================
    function renderCartItems() {
        cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];
        cartList.innerHTML = '';
        cartItems.forEach(item => renderCartItem(item));
        updateTotal();
    }

    function renderCartItem(item) {
        const li = document.createElement('li');
        const attributesHTML = Object.entries(item.attributes || {}).map(([key, value]) =>
            `${key.charAt(0).toUpperCase() + key.slice(1)}: <span>${value}</span>`
        ).join('<br>');

        li.innerHTML = `
            <a href="#"><img src="${item.image}" alt=""></a>
            <div>
                <h6 class="mb-0">${item.name}</h6>
                <p>$${item.price.toLocaleString()}
                    <del>$${item.originalPrice.toLocaleString()}</del>
                    <span class="btn-cart">$<span class="btn-cart__total">${(item.price * item.quantity).toLocaleString()}</span></span>
                </p>
                <p class="attributes">${attributesHTML}</p>
                <div class="btn-containter">
                    <div class="btn-control">
                        <button class="btn-control__remove">&minus;</button>
                        <div class="btn-control__quantity">
                            <div id="quantity-previous">${item.quantity - 1}</div>
                            <div id="quantity-current">${item.quantity}</div>
                            <div id="quantity-next">${item.quantity + 1}</div>
                        </div>
                        <button class="btn-control__add">+</button>
                    </div>
                </div>
            </div>
            <i class="fa fa-trash delete-icon" style="font-size: 18px; color: #888; cursor: pointer;"></i>
        `;

        li.querySelector('.btn-control__add').addEventListener('click', () => {
            item.quantity += 1;
            saveAndRender();
        });

        li.querySelector('.btn-control__remove').addEventListener('click', () => {
            if (item.quantity > 1) {
                item.quantity -= 1;
                saveAndRender();
            }
        });

        li.querySelector('.delete-icon').addEventListener('click', () => {
            cartItems = cartItems.filter(p =>
                !(p.id === item.id && JSON.stringify(p.attributes || {}) === JSON.stringify(item.attributes || {}))
            );
            saveAndRender();
        });

        cartList.appendChild(li);
    }

    function updateTotal() {
        let total = 0;
        cartItems.forEach(item => {
            total += item.price * item.quantity;
        });
        const totalElement = document.querySelector('.price-box p');
if (totalElement) {
    totalElement.textContent = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(total);
}
    }

    function saveAndRender() {
        localStorage.setItem(cartKey, JSON.stringify(cartItems));
        renderCartItems();
    }

    // ================================
    // 3. DOMContentLoaded: GÁN SỰ KIỆN
    // ================================
    document.addEventListener('DOMContentLoaded', function () {
        renderCartItems();

        // Bấm nút thêm vào giỏ hàng


        // Xử lý chọn size
        const sizeItems = document.querySelectorAll('.size-box ul li');
        sizeItems.forEach(function (item) {
            item.addEventListener('click', function () {
                sizeItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Xử lý chọn màu
        const colorItems = document.querySelectorAll('.color-variant li');
        colorItems.forEach(function (item) {
            item.addEventListener('click', function () {
                colorItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });

    // Reload lại nếu quay lại bằng Back/Forward
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
            window.location.reload();
        }
    });

    // Cho phép gọi từ ngoài bằng sự kiện tùy chỉnh
    document.addEventListener('cartUpdated', function () {
        renderCartItems();
    });
</script>


<script>
    document.addEventListener('cartUpdated', function() {
        if (typeof renderCartItems === 'function') {
            renderCartItems();
        }
    });
</script>


<!-- Mirrored from themes.pixelstrap.net/katie/template/layout-4.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 08 Jun 2025 03:58:47 GMT -->

</html>
