@extends('layouts.client')

@section('title', 'Trang chủ')

@section('content')
{{-- <section class="section-space home-section-4">
        <div class="custom-container container">
            <div class="row">
                <div class="col-12">
                    <div class="home-content">
                        <p> </p>
                        <h2> </h2>
                        <h1> </h1>
                        <h6> </h6><a class="btn" href="#"></a>
                    </div>
                    <div class="product-1">
                        <div class="product">
                            <div class="img-fluid"></div>
                            <div class="product-details">
                                <h6> </h6>
                                <p> </p>
                                <ul></ul>
                            </div>
                        </div>
                    </div>
                    <div class="product-2">
                        <div class="product">
                            <div class="img-fluid"></div>
                        </div>
                    </div>
                    <div class="home-images">
                        <div class="main-images"></div>
                    </div>
                    <div class="home-box-1"> <span> </span></div>
                    <div class="home-box-2"> <span> </span></div>
                    <div class="marquee">
                        <div class="marquee__item">
                            <h4 class="animation-text">Collection</h4>
                        </div>
                        <div class="marquee__item">
                            <h4 class="animation-text">Collection</h4>
                        </div>
                        <div class="marquee__item">
                            <h4 class="animation-text">Collection</h4>
                        </div>
                    </div>
                    <div class="shape-images"> <img class="img-1 img-fluid"
                            src="{{ asset('assets/client/images/layout-4/s-1.png') }}" alt=""><img class="img-2 img-fluid"
    src="{{ asset('assets/client/images/layout-4/s-2.png') }}" alt=""></div>
</div>
</div>
</div>
</section> --}}
<section class="section-space home-section-4">
    <div class="custom-container container">
        <div class="row">
            <div class="col-12">
                <div class="home-content">
                    <p>Create Your Style<span></span></p>
                    <h2>New Style For</h2>
                    <h1>Spring & Summer</h1>
                    <h6>Amet minim mollit non deserunt dolor do amet sint. </h6><a class="btn btn_outline"
                        href="collection-left-sidebar.html">Shop Now
                        <svg>
                            <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use>
                        </svg></a>
                </div>
                <div class="product-1">
                    <div class="product"> <img class="img-fluid"
                            src="{{ asset('assets/client/images/layout-4/p-1.jpg') }}" alt="">
                        <div class="product-details">
                            <h6>Black Women Top</h6>
                            <p>Women's Style</p>
                            <ul class="rating">
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                <li><i class="fa-regular fa-star"></i></li>
                            </ul>
                            <h5>$48
                                <del>$68 </del><span>-40%</span>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="product-2">
                    <div class="product"><img class="img-fluid"
                            src="{{ asset('assets/client/images/layout-4/p-2.png') }}" alt="">
                        <div class="product-details">
                            <div>
                                <h6>Pursesess</h6>
                                <h5>Best Women Bag</h5>
                            </div><span>$65</span>
                        </div>
                    </div>
                </div>
                <div class="home-images">
                    <div class="main-images"></div><img class="img-fluid"
                        src="{{ asset('assets/client/images/layout-4/1.png') }}" alt="">
                </div>
                <div class="home-box-1"> <span> </span></div>
                <div class="home-box-2"> <span> </span></div>
                <div class="marquee">
                    <div class="marquee__item">
                        <h4 class="animation-text">Collection</h4>
                    </div>
                    <div class="marquee__item">
                        <h4 class="animation-text">Collection</h4>
                    </div>
                    <div class="marquee__item">
                        <h4 class="animation-text">Collection</h4>
                    </div>
                </div>
                <div class="shape-images"> <img class="img-1 img-fluid"
                        src="{{ asset('assets/client/images/layout-4/s-1.png') }}" alt=""><img class="img-2 img-fluid"
                        src="{{ asset('assets/client/images/layout-4/s-2.png') }}" alt=""></div>
            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="container-fluid fashion-images">
        <div class="swiper fashion-images-slide">
            <div class="swiper-wrapper ratio_square-2">
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/1.png') }}" alt=""></a>
                    </div>
                    <h5>Top Wear</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/2.png') }}" alt=""></a>
                    </div>
                    <h5>dresses</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/3.png') }}" alt=""></a>
                    </div>
                    <h5>bottom</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/4.png') }}" alt=""></a>
                    </div>
                    <h5>inner/sleep</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/5.png') }}" alt=""></a>
                    </div>
                    <h5>footwear</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/6.png') }}" alt=""></a>
                    </div>
                    <h5>sports/active</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/7.png') }}" alt=""></a>
                    </div>
                    <h5>Mini dresses</h5>
                </div>
                <div class="swiper-slide">
                    <div class="fashion-box"><a href="#"> <img class="img-fluid"
                                src="{{ asset('assets/client/images/fashion/category/3.png') }}" alt=""></a>
                    </div>
                    <h5>footwear</h5>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container product-contain">
        <div class="title">
            <h3>Fashikart specials </h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>
        <div class="row trending-products">
            <div class="col-12">
                <div class="theme-tab-1">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><a class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#features-products" role="tab" aria-controls="features-products"
                                aria-selected="true">
                                <h6>Featured Products</h6>
                            </a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#latest-products" role="tab" aria-controls="latest-products"
                                aria-selected="false">
                                <h6>Latest Products</h6>
                            </a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#seller-products" role="tab" aria-controls="seller-products"
                                aria-selected="false">
                                <h6>Best Seller Products </h6>
                            </a></li>
                    </ul>
                </div>

                <!-- Fashikart specials -->
                <div class="row">
                    <div class="col-12 ratio_square">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="features-products" role="tabpanel" tabindex="0">
                                <div class="row g-4">
                                    @foreach($products as $product)
                                    <div class="col-xxl-3 col-md-4 col-6">
                                        <div class="product-box">
                                            <div class="img-wrapper">
                                                <div class="label-block">
                                                    <img src="{{ asset('assets/client/images/product/3.png') }}" alt="lable">
                                                    <span>on <br>Sale!</span>
                                                </div>
                                                <a href="{{ route('client.products.show', $product->id) }}" style="display: block;">
                                                    <div class="product-image bg-size"
                                                        style="background-image: url('{{ asset('storage/' . $product->image) }}');
                               background-size: cover;
                               background-position: center;">
                                                    </div>
                                                </a>
                                                <div class="cart-info-icon">
                                                    <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                                        <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                                                    </a>
                                                    <a href="compare.html" tabindex="0">
                                                        <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                                    </a>
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0">
                                                        <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="product-detail">
                                                <div class="add-button">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" title="add product" tabindex="0">
                                                        <i class="fa-solid fa-plus"></i> Add To Cart
                                                    </a>
                                                </div>
                                                <div class="color-box">
                                                    <ul class="color-variant">
                                                        <li class="bg-color-purple"></li>
                                                        <li class="bg-color-blue"></li>
                                                        <li class="bg-color-red"></li>
                                                        <li class="bg-color-yellow"></li>
                                                    </ul>
                                                    <span>{{ $product->rating_avg ?? '0' }} <i class="fa-solid fa-star"></i></span>
                                                </div>
                                                <a href="{{ route('client.products.show', $product->id) }}">
                                                    <h6>{{ $product->name }}</h6>
                                                </a>
                                                <p>
                                                    ${{ number_format($product->sale_price ?? $product->base_price) }}
                                                    @if ($product->sale_price)
                                                    <del>${{ number_format($product->base_price) }}</del>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- end Fashikart specials -->
            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container best-seller">
        <div class="row">
            <div class="col-xl-9">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="best-seller-img ratio_square-3"><a href="collection-left-sidebar.html"> <img
                                    class="bg-img"
                                    src="{{ asset('assets/client/images/layout-4/main-category/1.png') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="col-md-7 ratio_landscape">
                        <div class="style-content">
                            <h6>Wear Your Style</h6>
                            <h2>Create New Version Of Yourself</h2>
                            <h4>About Online Fashion Purchases</h4>
                            <div class="link-hover-anim underline"><a
                                    class="btn btn_underline link-strong link-strong-unhovered"
                                    href="collection-left-sidebar.html">Shop Collection
                                    <svg>
                                        <use
                                            href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                        </use>
                                    </svg></a><a class="btn btn_underline link-strong link-strong-hovered"
                                    href="collection-left-sidebar.html">Shop Collection
                                    <svg>
                                        <use
                                            href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                        </use>
                                    </svg></a></div>
                        </div><a href="collection-left-sidebar.html"> <img class="bg-img"
                                src="{{ asset('assets/client/images/layout-4/main-category/2.jpg') }}" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-3 d-none d-xl-block">
                <div class="best-seller-box">
                    <div class="offer-banner"><a href="collection-left-sidebar.html">
                            <h2>Extra 15% OFF</h2><span> </span>
                            <p>Designer Brand Season off In-store & Online for a limited Time</p>
                            <div class="btn">
                                <h6>Use Code: <span>KHUTRD***</span></h6>
                            </div>
                        </a></div>
                    <div class="best-seller-content">
                        <h3>Make You Look Comfortable and Luxurious</h3><span> </span>
                        <div class="link-hover-anim underline"><a
                                class="btn btn_underline link-strong link-strong-unhovered"
                                href="collection-left-sidebar.html">Shop Collection
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a><a class="btn btn_underline link-strong link-strong-hovered"
                                href="collection-left-sidebar.html">Shop Collection
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container product-contain">
        <div class="title">
            <h3>Fashikart specials</h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>
        <div class="swiper fashikart-slide">
            <div class="swiper-wrapper trending-products ratio_square">
                <div class="swiper-slide product-box">
                    <div class="img-wrapper">
                        <div class="label-block"><img src="{{ asset('assets/client/images/product/2.png') }}"
                                alt="lable"><span>on <br>Sale!</span></div>
                        <div class="product-image"><a href="#"> <img class="bg-img"
                                    src="{{ asset('assets/client/images/product/product-4/7.jpg') }}" alt="product"></a>
                        </div>
                        <div class="cart-info-icon"> <a class="wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                    class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Add to Wishlist"></i></a><a href="compare.html" tabindex="0"><i
                                    class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                    data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                    data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Quick View"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="add-button"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                title="add product" tabindex="0"><i class="fa-solid fa-plus"></i> Add To Cart</a></div>
                        <div class="color-box">
                            <ul class="color-variant">
                                <li class="bg-color-purple"></li>
                                <li class="bg-color-blue"></li>
                                <li class="bg-color-red"></li>
                                <li class="bg-color-yellow"></li>
                            </ul><span>4.5 <i class="fa-solid fa-star"></i></span>
                        </div><a href="#">
                            <h6>ASIAN Women's Barfi-02 Shoes</h6>
                        </a>
                        <p>$100.00
                            <del>$140.00</del>
                        </p>
                    </div>
                </div>
                <div class="swiper-slide product-box">
                    <div class="img-wrapper">
                        <div class="product-image"><a href="#"> <img class="bg-img"
                                    src="{{ asset('assets/client/images/product/product-4/8.jpg') }}" alt="product"></a>
                        </div>
                        <div class="cart-info-icon"> <a class="wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                    class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Add to Wishlist"></i></a><a href="compare.html" tabindex="0"><i
                                    class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                    data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                    data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Quick View"></i></a></div>
                        <div class="countdown">
                            <ul class="clockdiv4">
                                <li>
                                    <div class="timer">
                                        <div class="days"></div>
                                    </div><span class="title">Days</span>
                                </li>
                                <li class="dot"> <span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="hours"></div>
                                    </div><span class="title">Hours</span>
                                </li>
                                <li class="dot"> <span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="minutes"></div>
                                    </div><span class="title">Min</span>
                                </li>
                                <li class="dot"> <span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="seconds"></div>
                                    </div><span class="title">Sec</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="add-button"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                title="add product" tabindex="0"><i class="fa-solid fa-plus"></i> Add To Cart</a></div>
                        <div class="color-box">
                            <ul class="color-variant">
                                <li class="bg-color-purple"></li>
                                <li class="bg-color-blue"></li>
                                <li class="bg-color-red"></li>
                                <li class="bg-color-yellow"></li>
                            </ul><span>3.5 <i class="fa-solid fa-star"></i></span>
                        </div><a href="#">
                            <h6>Women Rayon Solid Hat</h6>
                        </a>
                        <p>$120.00
                            <del>$140.00</del>
                        </p>
                    </div>
                </div>
                <div class="swiper-slide product-box">
                    <div class="img-wrapper">
                        <div class="label-block"><img src="{{ asset('assets/client/images/product/3.png') }}"
                                alt="lable"><span>on <br>Sale!</span></div>
                        <div class="product-image"><a href="#"> <img class="bg-img"
                                    src="{{ asset('assets/client/images/product/product-4/9.jpg') }}" alt="product"></a>
                        </div>
                        <div class="cart-info-icon"> <a class="wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                    class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Add to Wishlist"></i></a><a href="compare.html" tabindex="0"><i
                                    class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                    data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                    data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Quick View"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="add-button"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                title="add product" tabindex="0"><i class="fa-solid fa-plus"></i> Add To Cart</a></div>
                        <div class="color-box">
                            <ul class="color-variant">
                                <li class="bg-color-purple"></li>
                                <li class="bg-color-blue"></li>
                                <li class="bg-color-red"></li>
                                <li class="bg-color-yellow"></li>
                            </ul><span>2.5 <i class="fa-solid fa-star"></i></span>
                        </div><a href="#">
                            <h6>OJASS Men's Solid Regular Jacket</h6>
                        </a>
                        <p>$1300
                            <del>$140.00</del>
                        </p>
                    </div>
                </div>
                <div class="swiper-slide product-box">
                    <div class="img-wrapper">
                        <div class="product-image"><a href="#"> <img class="bg-img"
                                    src="{{ asset('assets/client/images/product/product-4/10.jpg') }}"
                                    alt="product"></a></div>
                        <div class="cart-info-icon"> <a class="wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                    class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Add to Wishlist"></i></a><a href="compare.html" tabindex="0"><i
                                    class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                    data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                    data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Quick View"></i></a></div>
                        <div class="countdown">
                            <ul class="clockdiv5">
                                <li>
                                    <div class="timer">
                                        <div class="days"></div>
                                    </div><span class="title">Days</span>
                                </li>
                                <li class="dot"> <span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="hours"></div>
                                    </div><span class="title">Hours</span>
                                </li>
                                <li class="dot"> <span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="minutes"></div>
                                    </div><span class="title">Min</span>
                                </li>
                                <li class="dot"> <span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="seconds"></div>
                                    </div><span class="title">Sec</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="add-button"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                title="add product" tabindex="0"><i class="fa-solid fa-plus"></i> Add To Cart</a></div>
                        <div class="color-box">
                            <ul class="color-variant">
                                <li class="bg-color-purple"></li>
                                <li class="bg-color-blue"></li>
                                <li class="bg-color-red"></li>
                                <li class="bg-color-yellow"></li>
                            </ul><span>3.5 <i class="fa-solid fa-star"></i></span>
                        </div><a href="#">
                            <h6>Fiesto Fashion Women's Handbag</h6>
                        </a>
                        <p>$120.00
                            <del>$140.00</del>
                        </p>
                    </div>
                </div>
                <div class="swiper-slide product-box">
                    <div class="img-wrapper">
                        <div class="product-image"><a href="#"> <img class="bg-img"
                                    src="{{ asset('assets/client/images/product/product-4/3.jpg') }}" alt="product"></a>
                        </div>
                        <div class="cart-info-icon"> <a class="wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                    class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Add to Wishlist"></i></a><a href="compare.html" tabindex="0"><i
                                    class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                    data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                    data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                    data-bs-title="Quick View"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="add-button"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                title="add product" tabindex="0"><i class="fa-solid fa-plus"></i> Add To Cart</a></div>
                        <div class="color-box">
                            <ul class="color-variant">
                                <li class="bg-color-purple"></li>
                                <li class="bg-color-blue"></li>
                                <li class="bg-color-red"></li>
                                <li class="bg-color-yellow"></li>
                            </ul><span>2.5 <i class="fa-solid fa-star"></i></span>
                        </div><a href="#">
                            <h6>Beautiful Lycra Solid Women's High Zipper </h6>
                        </a>
                        <p>$1300
                            <del>$140.00</del>
                        </p>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container">
        <div class="title">
            <h3>Latest Blog</h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>
        <div class="swiper blog-slide">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="blog-main">
                        <div class="blog-box ratio3_2"><a class="blog-img" href="blog-details.html"><img class="bg-img"
                                    src="{{ asset('assets/client/images/blog/layout-4/1.jpg') }}" alt=""></a></div>
                        <div class="blog-txt">
                            <p>By: Admin / 26th aug 2020</p><a href="blog-details.html">
                                <h5>Many desktop publishing pack-ages abd page editor...</h5>
                            </a>
                            <div class="link-hover-anim underline"><a
                                    class="btn btn_underline link-strong link-strong-unhovered" href="#">Read
                                    More
                                    <svg>
                                        <use
                                            href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                        </use>
                                    </svg></a><a class="btn btn_underline link-strong link-strong-hovered" href="#">Read
                                    More
                                    <svg>
                                        <use
                                            href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                        </use>
                                    </svg></a></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide blog-main">
                    <div class="blog-box ratio_55"><a class="blog-img" href="blog-details.html"><img class="bg-img"
                                src="{{ asset('assets/client/images/blog/layout-4/2.jpg') }}" alt=""></a>
                    </div>
                    <div class="blog-txt">
                        <p>By: Admin / 26th aug 2020</p><a href="blog-details.html">
                            <h5>Many desktop publishing pack-ages abd page editor...</h5>
                        </a>
                        <div class="link-hover-anim underline"><a
                                class="btn btn_underline link-strong link-strong-unhovered" href="#">Read
                                More
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a><a class="btn btn_underline link-strong link-strong-hovered" href="#">Read
                                More
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a></div>
                    </div>
                </div>
                <div class="swiper-slide blog-main">
                    <div class="blog-box ratio3_2"><a class="blog-img" href="blog-details.html"><img class="bg-img"
                                src="{{ asset('assets/client/images/blog/layout-4/3.jpg') }}" alt=""></a>
                    </div>
                    <div class="blog-txt">
                        <p>By: Admin / 26th aug 2020</p><a href="blog-details.html">
                            <h5>Many desktop publishing pack-ages abd page editor...</h5>
                        </a>
                        <div class="link-hover-anim underline"><a
                                class="btn btn_underline link-strong link-strong-unhovered" href="#">Read
                                More
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a><a class="btn btn_underline link-strong link-strong-hovered" href="#">Read
                                More
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a></div>
                    </div>
                </div>
                <div class="swiper-slide blog-main">
                    <div class="blog-box ratio_55"><a class="blog-img" href="blog-details.html"><img class="bg-img"
                                src="{{ asset('assets/client/images/blog/layout-4/4.jpg') }}" alt=""></a>
                    </div>
                    <div class="blog-txt">
                        <p>By: Admin / 26th aug 2020</p><a href="blog-details.html">
                            <h5>Many desktop publishing pack-ages abd page editor...</h5>
                        </a>
                        <div class="link-hover-anim underline"><a
                                class="btn btn_underline link-strong link-strong-unhovered" href="#">Read
                                More
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a><a class="btn btn_underline link-strong link-strong-hovered" href="#">Read
                                More
                                <svg>
                                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-t-space instashop-section">
    <div class="container-fluid">
        <div class="row row-cols-xl-5 row-cols-md-4 row-cols-2 ratio_square-1">
            <div class="col">
                <div class="instagram-box">
                    <div class="instashop-effect"><img class="bg-img"
                            src="{{ asset('assets/client/images/instagram/17.jpg') }}" alt="">
                        <div class="insta-txt">
                            <div>
                                <svg class="insta-icon">
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#instagram">
                                    </use>
                                </svg>
                                <p>Instashop</p>
                                <div class="link-hover-anim underline"><a
                                        class="btn btn_underline link-strong link-strong-unhovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a><a class="btn btn_underline link-strong link-strong-hovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-box">
                    <div class="instashop-effect"><img class="bg-img"
                            src="{{ asset('assets/client/images/instagram/18.jpg') }}" alt="">
                        <div class="insta-txt">
                            <div>
                                <svg class="insta-icon">
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#instagram">
                                    </use>
                                </svg>
                                <p>Instashop</p>
                                <div class="link-hover-anim underline"><a
                                        class="btn btn_underline link-strong link-strong-unhovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a><a class="btn btn_underline link-strong link-strong-hovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12">
                <div class="instagram-txt-box">
                    <div>
                        <div>
                            <div class="instashop-icon">
                                <svg>
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#instagram">
                                    </use>
                                </svg>
                                <h3>Instashop</h3>
                            </div><span> </span>
                            <p>A conscious collection made entirely from food crop waste, recycled cotton, other
                                more sustainable materials.</p>
                        </div>
                        <div>
                            <div class="link-hover-anim underline"><a
                                    class="btn btn_underline link-strong link-strong-unhovered"
                                    href="https://www.instagram.com/" target="_blank">Go To Instagram</a><a
                                    class="btn btn_underline link-strong link-strong-hovered"
                                    href="https://www.instagram.com/" target="_blank">Go To Instagram</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-box">
                    <div class="instashop-effect"><img class="bg-img"
                            src="{{ asset('assets/client/images/instagram/19.jpg') }}" alt="">
                        <div class="insta-txt">
                            <div>
                                <svg class="insta-icon">
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#instagram">
                                    </use>
                                </svg>
                                <p>Instashop</p>
                                <div class="link-hover-anim underline"><a
                                        class="btn btn_underline link-strong link-strong-unhovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a><a class="btn btn_underline link-strong link-strong-hovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="instagram-box">
                    <div class="instashop-effect"><img class="bg-img"
                            src="{{ asset('assets/client/images/instagram/20.jpg') }}" alt="">
                        <div class="insta-txt">
                            <div>
                                <svg class="insta-icon">
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#instagram">
                                    </use>
                                </svg>
                                <p>Instashop</p>
                                <div class="link-hover-anim underline"><a
                                        class="btn btn_underline link-strong link-strong-unhovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a><a class="btn btn_underline link-strong link-strong-hovered"
                                        href="product.html">Discover
                                        <svg>
                                            <use
                                                href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                            </use>
                                        </svg></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="custom-container container">
        <div class="swiper logo-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/1.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/2.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/3.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/4.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/5.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/6.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/7.png') }}" alt="logo"></a></div>
                <div class="swiper-slide"><a href="collection-left-sidebar.html"> <img
                            src="{{ asset('assets/client/images/logos/3.png') }}" alt="logo"></a></div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')


<script src="{{ asset('assets/client/js/newsletter.js') }}"></script>
<script src="{{ asset('assets/client/js/skeleton-loader.js') }}"></script>
@endsection