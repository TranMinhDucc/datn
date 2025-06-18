<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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

    @yield('css')


</head>



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
                                        <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                                        <input type="number" value="1" min="1" max="20">
                                        <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
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
                <li> <a href="#"> <img src="{{ asset('assets/client/images/cart/1.jpg') }}" alt=""></a>
                    <div>
                        <h6 class="mb-0">Shirts Men's Clothing</h6>
                        <p>$35
                            <del>$40</del><span class="btn-cart">$<span class="btn-cart__total"
                                    id="total">105</span></span>
                        </p>
                        <div class="btn-containter">
                            <div class="btn-control">
                                <button class="btn-control__remove" id="btn-remove">&minus;</button>
                                <div class="btn-control__quantity">
                                    <div id="quantity-previous">2</div>
                                    <div id="quantity-current">3</div>
                                    <div id="quantity-next">4</div>
                                </div>
                                <button class="btn-control__add" id="btn-add">+</button>
                            </div>
                        </div>
                    </div><i class="iconsax delete-icon" data-icon="trash"></i>
                </li>
                <li> <a href="#"> <img src="{{ asset('assets/client/images/cart/2.jpg') }}" alt=""></a>
                    <div>
                        <h6 class="mb-0">Shirts Men's Clothing</h6>
                        <p>$35
                            <del>$40</del><span class="btn-cart">$<span class="btn-cart__total"
                                    id="total1">105</span></span>
                        </p>
                        <div class="btn-containter">
                            <div class="btn-control">
                                <button class="btn-control__remove" id="btn-remove1">&minus;</button>
                                <div class="btn-control__quantity">
                                    <div id="quantity1-previous">2</div>
                                    <div id="quantity1-current">3</div>
                                    <div id="quantity1-next">4</div>
                                </div>
                                <button class="btn-control__add" id="btn-add1">+</button>
                            </div>
                        </div>
                    </div><i class="iconsax delete-icon" data-icon="trash"></i>
                </li>
                <li> <a href="#"> <img src="{{ asset('assets/client/images/cart/3.jpg') }}" alt=""></a>
                    <div>
                        <h6 class="mb-0">Shirts Men's Clothing</h6>
                        <p>$35
                            <del>$40</del><span class="btn-cart">$<span class="btn-cart__total"
                                    id="total2">105</span></span>
                        </p>
                        <div class="btn-containter">
                            <div class="btn-control">
                                <button class="btn-control__remove" id="btn-remove2">&minus;</button>
                                <div class="btn-control__quantity">
                                    <div id="quantity2-previous">2</div>
                                    <div id="quantity2-current">3</div>
                                    <div id="quantity2-next">4</div>
                                </div>
                                <button class="btn-control__add" id="btn-add2">+</button>
                            </div>
                        </div>
                    </div><i class="iconsax delete-icon" data-icon="trash"></i>
                </li>
                <li> <a href="#"> <img src="{{ asset('assets/client/images/cart/4.jpg') }}" alt=""></a>
                    <div>
                        <h6 class="mb-0">Shirts Men's Clothing</h6>
                        <p>$35
                            <del>$40</del><span class="btn-cart">$<span class="btn-cart__total"
                                    id="total3">105</span></span>
                        </p>
                        <div class="btn-containter">
                            <div class="btn-control">
                                <button class="btn-control__remove" id="btn-remove3">&minus;</button>
                                <div class="btn-control__quantity">
                                    <div id="quantity3-previous">2</div>
                                    <div id="quantity3-current">3</div>
                                    <div id="quantity3-next">4</div>
                                </div>
                                <button class="btn-control__add" id="btn-add3">+</button>
                            </div>
                        </div>
                    </div><i class="iconsax delete-icon" data-icon="trash"></i>
                </li>
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
                <h6>Total :</h6>
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
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax" data-icon="search-normal-2"></i>Jeans
                            Women</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Blazer Women</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax" data-icon="search-normal-2"></i>Jeans
                            Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>Blazer Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax"
                                data-icon="search-normal-2"></i>T-Shirts Men</a></li>
                    <li> <a href="collection-left-sidebar.html"><i class="iconsax" data-icon="search-normal-2"></i>Shoes
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
                <h4>You Might Like</h4>
                <div class="row gy-4 ratio_square-2 preemptive-search">
                    <div class="col-xl-2 col-sm-4 col-6">
                        <div class="product-box-6">
                            <div class="img-wrapper">
                                <div class="product-image"><a href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-2/blazers/1.jpg') }}"
                                            alt="product"></a></div>
                            </div>
                            <div class="product-detail">
                                <div><a href="product.html">
                                        <h6> Women's Stylish Top</h6>
                                    </a>
                                    <p>$50.00 </p>
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
                    <div class="col-xl-2 col-sm-4 col-6">
                        <div class="product-box-6">
                            <div class="img-wrapper">
                                <div class="product-image"><a href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-2/blazers/2.jpg') }}"
                                            alt="product"></a></div>
                            </div>
                            <div class="product-detail">
                                <div><a href="product.html">
                                        <h6> Women's Stylish Top</h6>
                                    </a>
                                    <p>$95.00
                                        <del>$140.00</del>
                                    </p>
                                    <ul class="rating">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        <li><i class="fa-regular fa-star"></i></li>
                                        <li><i class="fa-regular fa-star"></i></li>
                                        <li>3+</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-6">
                        <div class="product-box-6">
                            <div class="img-wrapper">
                                <div class="product-image"><a href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-2/blazers/3.jpg') }}"
                                            alt="product"></a></div>
                            </div>
                            <div class="product-detail">
                                <div><a href="product.html">
                                        <h6> Women's Stylish Top</h6>
                                    </a>
                                    <p>$80.00
                                        <del>$140.00</del>
                                    </p>
                                    <ul class="rating">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        <li><i class="fa-regular fa-star"></i></li>
                                        <li>4</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-6">
                        <div class="product-box-6">
                            <div class="img-wrapper">
                                <div class="product-image"><a href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-2/blazers/4.jpg') }}"
                                            alt="product"></a></div>
                            </div>
                            <div class="product-detail">
                                <div><a href="product.html">
                                        <h6> Women's Stylish Top</h6>
                                    </a>
                                    <p>$90.00 </p>
                                    <ul class="rating">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        <li><i class="fa-regular fa-star"></i></li>
                                        <li>2+</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-6">
                        <div class="product-box-6">
                            <div class="img-wrapper">
                                <div class="product-image"><a href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-2/blazers/5.jpg') }}"
                                            alt="product"></a></div>
                            </div>
                            <div class="product-detail">
                                <div><a href="product.html">
                                        <h6> Women's Stylish Top</h6>
                                    </a>
                                    <p>$180.00
                                        <del>$140.00</del>
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
                    <div class="col-xl-2 col-sm-4 col-6">
                        <div class="product-box-6">
                            <div class="img-wrapper">
                                <div class="product-image"><a href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-2/blazers/6.jpg') }}"
                                            alt="product"></a></div>
                            </div>
                            <div class="product-detail"><a href="product.html">
                                    <h6> Women's Stylish Top</h6>
                                </a>
                                <p>$120.00 </p>
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
            </div>
        </div>
    </div>
    {{-- <div class="wrapper">
        <div class="title-box"> <img src="{{ asset('assets/client/images/other-img/cookie.png') }}" alt="">
            <h3>Cookies Consent</h3>
        </div>
        <div class="info">
            <p>We use cookies to improve our site and your shopping experience. By continuing to browse our site you
                accept our cookie policy.</p>
        </div>
        <div class="buttons">
            <button class="button btn btn_outline sm" id="acceptBtn">Accept</button>
            <button class="button btn btn_black sm">Decline</button>
        </div>
    </div> --}}
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
            let action = "{{ session('action') ?? '' }}";
            let timeout = 3000;

            // ⏱ Tuỳ chỉnh thời gian hiển thị thông báo theo hành động
            switch (action) {
                case "register":
                    timeout = 5000;
                    break;
                case "logout":
                    timeout = 800;
                    break;
                case "reset":
                    timeout = 3500;
                    break;
                case "avatar":
                    timeout = 1000; // ✅ Hiển thị rất nhanh cho cập nhật avatar
                    break;
                default:
                    timeout = 3000;
            }

            Swal.fire({
                icon: action === 'avatar' ? 'success' : 'info',
                title: action === 'avatar' ? '🎉 Ảnh đại diện đã được cập nhật!' : '🎉 {{ session('success') }}',
                html: "Đóng sau <b></b> ms.",
                timer: timeout,
                timerProgressBar: true,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    const b = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        if (b) b.textContent = Swal.getTimerLeft();
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            });
        </script>
    @endif
</body>
<!-- Mirrored from themes.pixelstrap.net/katie/template/layout-4.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 08 Jun 2025 03:58:47 GMT -->
</html>