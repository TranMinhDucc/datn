@extends('layouts.client')

@section('title', 'Trang chủ')
<style>
    .slideshow-container {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .fade-slide {
        opacity: 0;
        transition: opacity 1s ease-in-out;
        position: absolute;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 0;
        display: none;
    }


    .fade-slide.active {
        opacity: 1;
        z-index: 1;
        position: relative;
        display: block;
    }

    .section-t-space {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .fashion-images {
        margin-bottom: 0;
        /* giảm khoảng cách với phần tiếp theo */
    }

    .swiper-wrapper {
        height: auto !important;
    }

    .small-product-img {
        width: 120px;
        height: auto;
        object-fit: contain;
    }

    .product-2 .product {
        max-width: 250px;
    }

    .custom-product-img {
        width: 103px;
        height: 104px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 5px 6px rgba(0, 0, 0, 0.1);
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    /* Product 2 row: tên bên trái, giá bên phải */
    .product-2 .product-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .product-2 .product-details>div {
        flex: 1 1 auto;
        min-width: 0;
        /* cho phép co lại */
    }

    /* Tên sản phẩm: 2 dòng + ellipsis */
    .product-2 .product-details .prod-name {
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* 1 -> 2 dòng tùy muốn */
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
        /* cắt từ dài */
    }

    /* Badge giá không bị xuống dòng */
    .product-2 .product-details .price-badge {
        flex: 0 0 auto;
        white-space: nowrap;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 600;
    }

    @media (max-width:576px) {
        .product-2 .product-details .prod-name {
            -webkit-line-clamp: 1;
        }

        /* mobile: 1 dòng */
    }

    .btn_outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .half-arrow {
        width: 12px;
        height: 12px;
        display: inline-block;
        border-right: 2px solid currentColor;
        border-top: 2px solid currentColor;
        transform: rotate(45deg);
        /* nghiêng lên-phải */
        margin-left: 2px;
        transition: transform .2s;
    }

    .btn_outline:hover .half-arrow {
        transform: rotate(45deg) translate(2px, -2px);
    }

    /* Category (product 2): clamp 2 dòng */
    .product-2 .product-details h6 {
        margin: 0 0 6px;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        /* số dòng muốn hiển thị */
        line-clamp: 2;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }

    /* Mobile: chỉ 1 dòng cho gọn (tùy chọn) */
    @media (max-width: 576px) {
        .product-2 .product-details h6 {
            -webkit-line-clamp: 1;
            line-clamp: 1;
        }
    }

    /* .btn_outline{display:inline-flex;align-items:center;gap:8px;} */

    /* CARD SP1: ảnh trái – nội dung phải (giống mẫu) */
    .home-section-4 .product-1 .product {
        display: flex !important;
        align-items: center;
        gap: 14px;
        padding: 12px;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        width: 280px;
        /* cố định bề ngang (chỉnh 320–360 tùy layout) */
    }

    /* Ảnh vuông cố định */
    .home-section-4 .product-1 .custom-product-img {
        flex: 0 0 100px;
        /* = kích thước ảnh */
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
        display: block;
        background: #f5f5f5;
    }

    /* Vùng chữ có thể co giãn */
    .home-section-4 .product-1 .product-details {
        flex: 1 1 auto;
        min-width: 0;
        /* QUAN TRỌNG để ellipsis hoạt động */
    }

    /* Tên SP1 – kẹp 1 (hoặc 2) dòng */
    .home-section-4 .product-1 .product-details .prod1-name {
        margin: 0;
        white-space: normal !important;
        display: -webkit-box !important;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        /* đổi thành 2 nếu muốn 2 dòng */
        overflow: hidden !important;
        text-overflow: ellipsis;
        word-break: break-word;
    }

    /* Danh mục SP1 — 1 dòng, cắt ngắn gọn trong card */
    .home-section-4 .product-1 .product-details>p {
        margin: 2px 0 6px;
        color: #6a6a6a;
        font-size: 12px;
        /* nhỏ hơn chút để gọn */
        line-height: 1.2;
        max-width: 100%;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis;
    }

    /* Rating gọn */
    .home-section-4 .product-1 .product-details .rating {
        display: flex;
        gap: 4px;
        margin: 0 0 6px;
        padding: 0;
        list-style: none;
        color: #f39c12;
    }

    /* Hàng giá: không tràn, có badge giảm giá */
    .home-section-4 .product-1 .product-details h5 {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
        margin: 0;
    }

    .home-section-4 .product-1 .product-details h5 del {
        color: #999;
    }

    .home-section-4 .product-1 .product-details h5 span {
        background: #E7A76D;
        color: #fff;
        border-radius: 999px;
        padding: 4px 10px;
        font-weight: 700;
        font-size: 12px;
        white-space: nowrap;
    }

    /* ép SP1 căn trái, ghi đè text-center của theme */
    .home-section-4 .product-1 .product.text-center {
        text-align: left !important;
    }

    /* khối chữ xếp dọc và bám trái */
    .home-section-4 .product-1 .product-details {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        /* sát trái */
    }

    /* tên & danh mục */
    .home-section-4 .product-1 .product-details .prod1-name {
        margin: 0 0 2px;
    }

    .home-section-4 .product-1 .product-details>p {
        margin: 0 0 6px;
    }

    /* rating bám trái */
    .home-section-4 .product-1 .product-details .rating {
        justify-content: flex-start;
    }

    /* hàng giá đã flex sẵn: giữ trái */
    .home-section-4 .product-1 .product-details h5 {
        align-self: flex-start;
    }

    /* Mobile: card co theo chiều ngang */
    @media (max-width:576px) {
        .home-section-4 .product-1 .product {
            width: 100%;
        }

        .home-section-4 .product-1 .product-details .prod1-name {
            -webkit-line-clamp: 2;
        }
    }
</style>
@section('content')
    <section class="section-space home-section-4">
        <div class="custom-container container">
            <div class="row">
                <div class="col-12">
                    <div class="home-content">
                        @php $current = $banners->first(); @endphp
                        @php
                            $p1 = $current['product1'] ?? null;
                            $p2 = $current['product2'] ?? null;
                        @endphp
                        <p>
                            Tạo phong cách riêng của bạn
                            <span></span>
                        </p>
                        <h2>{{ $current['subtitle'] ?? '' }}</h2>
                        <h1>{{ $current['title'] ?? '' }}</h1>
                        <h6>{!! $current['description'] ?? '' !!}</h6>
                        @php
                            $btnTitle = $current['btn_title'] ?? 'Shop Now';
                            $btnLink = $current['btn_link'] ?? route('client.category.index');
                        @endphp

                        <a class="btn btn_outline" href="{{ $btnLink }}">
                            {{ $btnTitle }}
                            <span class="half-arrow"></span>
                        </a>

                    </div>
                    <div class="product-1">
                        @if ($p1)
                            <a href="{{ $p1['url'] }}" style="display:block; text-decoration:none; color:inherit;">
                                <div class="product text-center">
                                    <img class="img-fluid custom-product-img" src="{{ $p1['image'] }}"
                                        alt="{{ $p1['name'] }}">
                                    <div class="product-details">
                                        <h6 class="prod1-name">{{ $p1['name'] }}</h6>
                                        <p>{{ $p1['category'] ?? 'Uncategorized' }}</p>

                                        @php
                                            $avgRating = round($p1['avg_rating'] ?? 0, 1);
                                            $full = floor($avgRating);
                                            $half = $avgRating - $full >= 0.5;
                                        @endphp
                                        <ul class="rating">
                                            @for ($i = 0; $i < $full; $i++)
                                                <li><i class="fa-solid fa-star"></i></li>
                                            @endfor
                                            @if ($half)
                                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            @endif
                                            @for ($i = $full + ($half ? 1 : 0); $i < 5; $i++)
                                                <li><i class="fa-regular fa-star"></i></li>
                                            @endfor
                                        </ul>

                                        @php $price = $p1['sale_price'] ?? $p1['price']; @endphp
                                        <h5>
                                            {{ number_format($price, 0, ',', '.') }}₫
                                            @if (!empty($p1['sale_price']) && !empty($p1['price']))
                                                <del>{{ number_format($p1['price'], 0, ',', '.') }}₫</del>
                                                <span>-{{ round(100 - ($p1['sale_price'] / $p1['price']) * 100) }}%</span>
                                            @endif
                                        </h5>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>


                    <div class="product-2">
                        @if ($p2)
                            <a href="{{ $p2['url'] }}" style="display:block;">
                                <div class="product">
                                    <img class="img-fluid" src="{{ $p2['image'] }}" alt="{{ $p2['name'] }}">
                                    <div class="product-details">
                                        <div>
                                            <h6>{{ $p2['category'] ?? 'Category' }}</h6>
                                            <h5 class="prod-name">{{ $p2['name'] }}</h5> {{-- thêm class --}}
                                        </div>
                                        @php $price2 = $p2['sale_price'] ?? $p2['price']; @endphp
                                        <span class="price-badge">{{ number_format($price2, 0, ',', '.') }}₫</span>
                                        {{-- thêm class --}}
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>

                    <div class="home-images">
                        @php
                            $img = !empty($current['main_image'])
                                ? asset('storage/' . $current['main_image'])
                                : asset('assets/client/images/layout-4/1.png');
                        @endphp

                        {{-- <img class="img-fluid" src="{{ $img }}" alt=""> --}}

                        <div class="main-images"></div>
                        {{-- <img class="img-fluid" src="{{ $img }}" alt=""> --}}
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
                    <div class="shape-images">
                        <img class="img-1 img-fluid" src="{{ asset('assets/client/images/layout-4/s-1.png') }}"
                            alt=""><img class="img-2 img-fluid"
                            src="{{ asset('assets/client/images/layout-4/s-2.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>

    </section>

   <section class="section-t-space">
        <div class="container-fluid fashion-images">
            <div class="swiper fashion-images-slide">
                <div class="swiper-wrapper ratio_square-2">
                    @foreach ($categories as $category)
                        <div class="swiper-slide text-center">
            <div class="fashion-box mb-2">
                {{-- Chuyển sang filter và truyền danh mục dạng query param --}}
                <a href="{{ route('client.products.filterSidebar') }}?category[]={{ $category->id }}">
                    <img class="img-fluid rounded-circle category-circle-img"
                        src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                </a>
            </div>
            <h5>{{ $category->name }}</h5>
        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </section>
    <section class="section-t-space">
        <div class="custom-container container product-contain">
            <div class="title">
                <h3>Sản phẩm nổi bật</h3>
                <svg>
                    <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
                </svg>
            </div>
            <div class="row trending-products">
                <div class="col-12">
                    <div class="theme-tab-1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#features-products"
                                    role="tab" aria-controls="features-products" aria-selected="true">
                                    <h6>Sản phẩm nổi bật</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#latest-products" role="tab"
                                    aria-controls="latest-products" aria-selected="false">
                                    <h6>Sản phẩm mới nhất</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#seller-products" role="tab"
                                    aria-controls="seller-products" aria-selected="false">
                                    <h6>Sản phẩm bán chạy nhất</h6>
                                </a>
                            </li>
                        </ul>
                    </div>


                    <div class="row">
                        <div class="col-12 ratio_square">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="features-products" role="tabpanel"
                                    tabindex="0">
                                    <div class="row g-4">
                                        @foreach ($products as $product)
                                            <div class="col-xxl-3 col-md-4 col-6">
                                                <div class="product-box">
                                                    <div class="img-wrapper">
                                                        @if ($product->labels->count())
                                                            <div class="label-block">
                                                                @foreach ($product->labels as $product_label)
                                                                    <div class="label-item-wrapper"
                                                                        style="display:inline-block;max-width:60px;margin-right:10px">
                                                                        <img style="width:100%"
                                                                            class="{{ $product_label->position }}"
                                                                            src="{{ asset($product_label->image) }}"
                                                                            alt="label">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif


                                                        <a href="{{ route('client.products.show', $product->slug) }}"
                                                            style="display: block;">

                                                        </a>
                                                        <div class="cart-info-icon">
                                                            <a class="wishlist-icon add-to-wishlist"
                                                                href="javascript:void(0)" data-id="{{ $product->id }}">
                                                                <i class="iconsax" data-icon="heart" aria-hidden="true"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-title="Add to Wishlist"></i>
                                                            </a>
                                                        </div>
                                                        <div class="product-image">
                                                            <a class="pro-first"
                                                                href="{{ route('client.products.show', $product->slug) }}">
                                                                <img class="bg-img"
                                                                    src="{{ asset('storage/' . $product->image) }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>

                                                        <div class="countdown" style="bottom: 5px;"
                                                            data-starttime="{{ optional($product->starts_at ? \Carbon\Carbon::parse($product->starts_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}"
                                                            data-endtime="{{ optional($product->ends_at ? \Carbon\Carbon::parse($product->ends_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}">
                                                            <ul>
                                                                <li>
                                                                    <div class="timer">
                                                                        <div class="days"></div>
                                                                    </div><span class="title">Days</span>
                                                                </li>
                                                                <li class="dot"><span>:</span></li>
                                                                <li>
                                                                    <div class="timer">
                                                                        <div class="hours"></div>
                                                                    </div><span class="title">Hours</span>
                                                                </li>
                                                                <li class="dot"><span>:</span></li>
                                                                <li>
                                                                    <div class="timer">
                                                                        <div class="minutes"></div>
                                                                    </div><span class="title">Min</span>
                                                                </li>
                                                                <li class="dot"><span>:</span></li>
                                                                <li>
                                                                    <div class="timer">
                                                                        <div class="seconds"></div>
                                                                    </div><span class="title">Sec</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-detail">

                                                        <div class="color-box">
                                                            <ul class="color-variant">
                                                                <li class="bg-color-purple"></li>
                                                                <li class="bg-color-blue"></li>
                                                                <li class="bg-color-red"></li>
                                                                <li class="bg-color-yellow"></li>
                                                            </ul>
                                                            <span>
                                                                {{ $product->reviews_avg_rating ? number_format($product->reviews_avg_rating, 1) : '0.0' }}
                                                                <i class="fa-solid fa-star"></i>
                                                            </span>

                                                        </div>
                                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                                            <h6 class="product-title">{{ $product->name }}</h6>
                                                        </a>

                                                        @php
                                                            $now = \Carbon\Carbon::now();
                                                            $start = $product->starts_at
                                                                ? \Carbon\Carbon::parse($product->starts_at)
                                                                : null;
                                                            $end = $product->ends_at
                                                                ? \Carbon\Carbon::parse($product->ends_at)
                                                                : null;

                                                            $isInDiscountTime =
                                                                $start && $end ? $now->between($start, $end) : false;
                                                            $finalPrice = $isInDiscountTime
                                                                ? $product->base_price *
                                                                    (1 - $product->sale_times / 100)
                                                                : $product->sale_price ?? $product->base_price;
                                                        @endphp
                                                        <div class="price-box">
                                                            <span class="final-price">{{ number_format($finalPrice) }}
                                                                đ</span>
                                                            @if ($product->sale_price || $isInDiscountTime)
                                                                <span class="old-price"><del>{{ number_format($product->base_price) }}
                                                                        đ</del></span>
                                                            @endif
                                                        </div>

                                                        @if ($isInDiscountTime)
                                                            <span>-{{ $product->sale_times }}%</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- Latest Products Tab --}}
                                <div class="tab-pane fade" id="latest-products" role="tabpanel" tabindex="0">
                                    <div class="row g-4">
                                        @foreach ($latestProducts as $product)
                                            @include('client.components.product-box', [
                                                'product' => $product,
                                            ])
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Best Seller Products Tab --}}
                                <div class="tab-pane fade" id="seller-products" role="tabpanel" tabindex="0">
                                    <div class="row g-4">
                                        @foreach ($bestSellerProducts as $product)
                                            @include('client.components.product-box', [
                                                'product' => $product,
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($bestSeller)
    {{-- Nếu có dữ liệu trong DB thì render động --}}
    <section class="section-t-space">
        <div class="custom-container container best-seller">
            <div class="row">
                <div class="col-xl-9">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <div class="best-seller-img ratio_square-3">
                                <a href="{{ $bestSeller->btn_url ?? '#' }}">
                                    <img class="bg-img"
                                         src="{{ $bestSeller->left_image ? asset('storage/'.$bestSeller->left_image) : asset('assets/client/images/layout-4/main-category/1.png') }}"
                                         alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7 ratio_landscape">
                            <div class="style-content">
                                <h6>{{ $bestSeller->title_small }}</h6>
                                <h2>{{ $bestSeller->title_main }}</h2>
                                <h4>{{ $bestSeller->subtitle }}</h4>
                                <div class="link-hover-anim underline">
                                    <a class="btn btn_underline link-strong link-strong-unhovered"
                                       href="{{ $bestSeller->btn_url ?? '#' }}">
                                        {{ $bestSeller->btn_text }}
                                        <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                    </a>
                                    <a class="btn btn_underline link-strong link-strong-hovered"
                                       href="{{ $bestSeller->btn_url ?? '#' }}">
                                        {{ $bestSeller->btn_text }}
                                        <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ $bestSeller->btn_url ?? '#' }}">
                                <img class="bg-img"
                                     src="{{ $bestSeller->right_image ? asset('storage/'.$bestSeller->right_image) : asset('assets/client/images/layout-4/main-category/2.jpg') }}"
                                     alt="">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-3 d-none d-xl-block">
                    <div class="best-seller-box">
                        <div class="offer-banner">
                            <a href="{{ $bestSeller->btn_url ?? '#' }}">
                                <h2>{{ $bestSeller->side_offer_title }}</h2><span> </span>
                                <p>{{ $bestSeller->side_offer_desc }}</p>
                                <div class="btn">
                                    <h6>Use Code: <span>{{ $bestSeller->side_offer_code }}</span></h6>
                                </div>
                            </a>
                        </div>
                        <div class="best-seller-content">
                            <h3>{{ $bestSeller->side_title }}</h3><span> </span>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline link-strong link-strong-unhovered"
                                   href="{{ $bestSeller->btn_url ?? '#' }}">
                                    {{ $bestSeller->btn_text }}
                                    <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                </a>
                                <a class="btn btn_underline link-strong link-strong-hovered"
                                   href="{{ $bestSeller->btn_url ?? '#' }}">
                                    {{ $bestSeller->btn_text }}
                                    <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @else
    {{-- Nếu trong DB KHÔNG có dữ liệu thì hiển thị nội dung mặc định (tiếng Việt) --}}
    <section class="section-t-space">
        <div class="custom-container container best-seller">
            <div class="row">
                <div class="col-xl-9">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <div class="best-seller-img ratio_square-3">
                                <a href="collection-left-sidebar.html">
                                    <img class="bg-img" src="{{ asset('assets/client/images/layout-4/main-category/1.png') }}" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7 ratio_landscape">
                            <div class="style-content">
                                <h6>Thời trang của bạn</h6>
                                <h2>Tạo phiên bản mới của chính mình</h2>
                                <h4>Về việc mua sắm thời trang trực tuyến</h4>
                                <div class="link-hover-anim underline">
                                    <a class="btn btn_underline link-strong link-strong-unhovered" href="collection-left-sidebar.html">
                                        Xem bộ sưu tập
                                        <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                    </a>
                                    <a class="btn btn_underline link-strong link-strong-hovered" href="collection-left-sidebar.html">
                                        Xem bộ sưu tập
                                        <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                    </a>
                                </div>
                            </div>
                            <a href="collection-left-sidebar.html">
                                <img class="bg-img" src="{{ asset('assets/client/images/layout-4/main-category/2.jpg') }}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-3 d-none d-xl-block">
                    <div class="best-seller-box">
                        <div class="offer-banner">
                            <a href="collection-left-sidebar.html">
                                <h2>Giảm thêm 15%</h2><span> </span>
                                <p>Thương hiệu thiết kế giảm giá theo mùa, áp dụng tại cửa hàng & trực tuyến trong thời gian có hạn</p>
                                <div class="btn">
                                    <h6>Mã sử dụng: <span>KHUTRD***</span></h6>
                                </div>
                            </a>
                        </div>
                        <div class="best-seller-content">
                            <h3>Giúp bạn trông thoải mái và sang trọng</h3><span> </span>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline link-strong link-strong-unhovered" href="collection-left-sidebar.html">
                                    Xem bộ sưu tập
                                    <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                </a>
                                <a class="btn btn_underline link-strong link-strong-hovered" href="collection-left-sidebar.html">
                                    Xem bộ sưu tập
                                    <svg><use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endif

<!-- Fashikart specials -->
<section class="section-t-space">
    <div class="custom-container container product-contain">
        <div class="title">
            <h3>Ưu đãi đặc biệt</h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>
        <div class="swiper fashikart-slide">
            <div class="swiper-wrapper trending-products ratio_square">
                @foreach ($specialOfferProducts as $prd)            

                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                        @if ($product->labels->count())
                        <div class="label-block">
                            @foreach ($product->labels as $product_label)
                            <div class="label-item-wrapper"
                                style="display:inline-block;max-width:60px;margin-right:10px">
                                <img style="width:100%"
                                    class="{{ $product_label->position }}"
                                    src="{{ asset($product_label->image) }}"
                                    alt="label">
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <a href="{{ route('client.products.show', $product->slug) }}"
                            style="display: block;">

                        </a>
                        <div class="cart-info-icon">
                            <a class="wishlist-icon add-to-wishlist"
                                href="javascript:void(0)" data-id="{{ $product->id }}">
                                <i class="iconsax" data-icon="heart" aria-hidden="true"
                                    data-bs-toggle="tooltip"
                                    data-bs-title="Add to Wishlist"></i>
                            </a>
                        </div>
                        <div class="product-image">
                            <a class="pro-first"
                                href="{{ route('client.products.show', $product->slug) }}">
                                <img class="bg-img"
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}">
                            </a>
                        </div>

                        <div class="countdown" style="bottom: 5px;"
                            data-starttime="{{ optional($product->starts_at ? \Carbon\Carbon::parse($product->starts_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}"
                            data-endtime="{{ optional($product->ends_at ? \Carbon\Carbon::parse($product->ends_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}">
                            <ul>
                                <li>
                                    <div class="timer">
                                        <div class="days"></div>
                                    </div><span class="title">Days</span>
                                </li>
                                <li class="dot"><span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="hours"></div>
                                    </div><span class="title">Hours</span>
                                </li>
                                <li class="dot"><span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="minutes"></div>
                                    </div><span class="title">Min</span>
                                </li>
                                <li class="dot"><span>:</span></li>
                                <li>
                                    <div class="timer">
                                        <div class="seconds"></div>
                                    </div><span class="title">Sec</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-detail">

                        <div class="color-box">
                            <ul class="color-variant">
                                <li class="bg-color-purple"></li>
                                <li class="bg-color-blue"></li>
                                <li class="bg-color-red"></li>
                                <li class="bg-color-yellow"></li>
                            </ul>
                            <span>
                                {{ $product->reviews_avg_rating ? number_format($product->reviews_avg_rating, 1) : '0.0' }}
                                <i class="fa-solid fa-star"></i>
                            </span>

                        </div>
                        <a href="{{ route('client.products.show', $product->slug) }}">
                            <h6 class="product-title">{{ $product->name }}</h6>
                        </a>

                        @php
                        $now = \Carbon\Carbon::now();
                        $start = $product->starts_at
                        ? \Carbon\Carbon::parse($product->starts_at)
                        : null;
                        $end = $product->ends_at
                        ? \Carbon\Carbon::parse($product->ends_at)
                        : null;

                        $isInDiscountTime =
                        $start && $end ? $now->between($start, $end) : false;
                        $finalPrice = $isInDiscountTime
                        ? $product->base_price *
                        (1 - $product->sale_times / 100)
                        : $product->sale_price ?? $product->base_price;
                        @endphp
                        <div class="price-box">
                            <span class="final-price">{{ number_format($finalPrice) }}
                                đ</span>
                            @if ($product->sale_price || $isInDiscountTime)
                            <span class="old-price"><del>{{ number_format($product->base_price) }}
                                    đ</del></span>
                            @endif
                        </div>

                        @if ($isInDiscountTime)
                        <span>-{{ $product->sale_times }}%</span>
                        @endif
                    </div>
</div>
@endforeach
<div class="swiper-button-prev"></div>
<div class="swiper-button-next"></div>
</div>
</div>
</section> --}}
<section class="section-t-space">
    <div class="custom-container container">
        <div class="title">
            <h3>Blog Mới Nhất</h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>

            </div>
        </div>
    </div>
</section>
<section class="section-t-space">
    <div class="custom-container container">
        <div class="title">
            <h3>Blog Mới Nhất</h3>
            <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#main-line"></use>
            </svg>
        </div>
        <div class="swiper blog-slide">
            <div class="swiper-wrapper">
                @foreach ($latestBlogs as $blog)
                <div class="swiper-slide blog-main">
                    <div class="blog-box ratio3_2">
                        <a class="blog-img" href="{{ route('client.blog.show', $blog->slug) }}">
                            <img class="bg-img" src="{{ asset('storage/' . $blog->thumbnail) }}"
                                alt="{{ $blog->title }}">
                        </a>
                    </div>
                    <div class="blog-txt">
                        <p>
                            Tác giả: {{ $blog->author->username ?? 'Admin' }} -
                            {{ $blog->published_at->format('d/m/Y') }}
                        </p>
                        <a href="{{ route('client.blog.show', $blog->slug) }}">
                            <h5>{{ Str::limit($blog->title, 60) }}</h5>
                        </a>
                        <br>
                        <div class="link-hover-anim underline">
                            <a class="btn btn_underline link-strong link-strong-unhovered"
                                href="{{ route('client.blog.show', $blog->slug) }}">
                                Đọc thêm
                                <svg>
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg>
                            </a>
                            <a class="btn btn_underline link-strong link-strong-hovered"
                                href="{{ route('client.blog.show', $blog->slug) }}">
                                Đọc thêm
                                <svg>
                                    <use
                                        href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow">
                                    </use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
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
    </section> --}}

    <section class="section-b-space">
        <div class="custom-container container">
            <div class="swiper logo-slider">
                <div class="swiper-wrapper">
                    @if (isset($brands) && $brands->count())
                        @foreach ($brands as $brand)
                            <div class="swiper-slide">
                                <a href="#">
                                    <div
                                        style="
        width:125px;
        height:125px;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:auto;
        background:#fff;
        border-radius:8px;
        overflow:hidden;
    ">
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                </a>


                            </div>
                        @endforeach
                    @else
                        <div class="swiper-slide">
                            <span class="text-muted">Chưa có thương hiệu nào</span>
                        </div>
                    @endif
                </div>

            </div>
    </section>




@endsection
<style>
    .product-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* số dòng tối đa */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4em;
        /* khoảng cách dòng */
        max-height: calc(1.4em * 2);
        /* 2 dòng */
    }

    .price-box {
        display: flex;
        align-items: center;
        gap: 8px;
        /* khoảng cách giữa 2 giá */
    }

    .final-price {
        font-weight: bold;
        color: #d19f00;
        /* màu vàng gold hoặc tuỳ chỉnh */
        font-size: 1rem;
    }

    .old-price {
        color: #777;
        font-size: 0.9rem;
    }

    .label-block {
        display: flex;
        flex-wrap: wrap;
        /* Cho phép xuống hàng khi tràn */
        gap: 6px;
        /* khoảng cách giữa các nhãn */
    }

    .label-item-wrapper {
        max-width: 60px;
        flex: 0 0 auto;
    }

    @media (max-width: 576px) {
        .label-item-wrapper {
            max-width: 40px;
            /* nhỏ lại trên màn hình điện thoại */
        }
    }
</style>

@section('js')
    <script>
        const BANNERS = @json($banners); // mỗi item: có product1, product2 như trên

        (function() {
            if (!Array.isArray(BANNERS) || !BANNERS.length) return;

            const root = document.querySelector('.home-section-4');
            if (!root) return;

            const h2 = root.querySelector('.home-content h2');
            const h1 = root.querySelector('.home-content h1');
            const h6 = root.querySelector('.home-content h6');
            const btn = root.querySelector('.home-content .btn.btn_outline');
            const imgs = root.querySelectorAll('.home-images img.img-fluid');

            // 2 box sản phẩm
            const box1 = root.querySelector('.product-1');
            const box2 = root.querySelector('.product-2');

            // prev/next “dấu chấm” của theme
            const prevDot = document.querySelector('.home-box-1 span');
            const nextDot = document.querySelector('.home-box-2 span');

            let index = 0;
            const speed = 800;
            const autoplayDelay = 5000;
            const defaultUrl = '{{ asset('assets/client/images/layout-4/1.png') }}';

            // -------- helpers ----------
            const escapeHtml = (s) => (s || '').replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [m]));
            const fmt = (n) => (Number(n || 0)).toFixed(2);
            const discountPct = (sp, p) => (sp && p && Number(p) > 0) ? Math.round(100 - (Number(sp) / Number(p)) *
                100) : 0;

            const starsHtml = (avg = 0) => {
                avg = Math.max(0, Math.min(5, Number(avg) || 0));
                const full = Math.floor(avg);
                const half = (avg - full) >= 0.5;
                let out = '<ul class="rating">';
                for (let i = 0; i < full; i++) out += '<li><i class="fa-solid fa-star"></i></li>';
                if (half) out += '<li><i class="fa-solid fa-star-half-stroke"></i></li>';
                for (let i = full + (half ? 1 : 0); i < 5; i++) out +=
                    '<li><i class="fa-regular fa-star"></i></li>';
                out += '</ul>';
                return out;
            };

            function renderProductBoxes(b) {
                // helper: format VND
                const vnd = (n) => Number(n || 0).toLocaleString('vi-VN', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                // Product 1 (khối “đẹp” với rating/giảm giá)
                if (box1) {
                    if (!b.product1) {
                        box1.innerHTML = ''; // ẩn nếu không có
                    } else {
                        const p = b.product1;
                        const pct = discountPct(p.sale_price, p.price);
                        const nowPrice = (p.sale_price ?? p.price);

                        box1.innerHTML = `
        <a href="${p.url}" style="display:block; text-decoration:none; color:inherit;">
          <div class="product text-center">
            <img class="img-fluid custom-product-img" src="${p.image || ''}" alt="${escapeHtml(p.name)}">
            <div class="product-details">
              <h6 class="prod1-name">${escapeHtml(p.name)}</h6>
              <p>${escapeHtml(p.category || 'Uncategorized')}</p>
              ${starsHtml(p.avg_rating)}
              <h5>
                ${vnd(nowPrice)}₫
                ${
                  (p.sale_price && p.price)
                    ? `<del>${vnd(p.price)}₫</del><span> -${pct}%</span>`
                    : ''
                }
              </h5>
            </div>
          </div>
        </a>
      `;
                    }
                }

                // Product 2 (đơn giản hơn)
                if (box2) {
                    if (!b.product2) {
                        box2.innerHTML = '';
                    } else {
                        const p = b.product2;
                        const nowPrice = (p.sale_price ?? p.price);

                        box2.innerHTML = `
        <a href="${p.url}" style="display:block;">
          <div class="product">
            <img class="img-fluid" src="${p.image || ''}" alt="${escapeHtml(p.name)}">
            <div class="product-details">
  <div>
    <h6>${escapeHtml(p.category || 'Category')}</h6>
    <h5 class="prod-name">${escapeHtml(p.name)}</h5>
  </div>
  <span class="price-badge">${vnd(nowPrice)}₫</span>
</div>

          </div>
        </a>
      `;
                    }
                }
            }
            // ----------------------------

            const render = (i) => {
                const b = BANNERS[i];
                if (!b) return;

                imgs.forEach(el => {
                    el.style.transition = `opacity ${speed}ms`;
                    el.style.opacity = '0';
                });
                if (h2) h2.style.transition = `opacity ${speed}ms`;
                if (h1) h1.style.transition = `opacity ${speed}ms`;
                if (h6) h6.style.transition = `opacity ${speed}ms`;

                setTimeout(() => {
                    if (h2) h2.textContent = b.subtitle || '';
                    if (h1) h1.textContent = b.title || '';
                    if (h6) h6.innerHTML = b.description || '';

                    // bên trong function render(i)
                    if (btn) {
                        if (b.btn_link) btn.setAttribute('href', b.btn_link);

                        // đổi text nhưng giữ lại <svg>
                        const textNode = Array.from(btn.childNodes).find(n => n.nodeType === 3);
                        const newText = (b.btn_title || 'Shop Now') + ' ';
                        if (textNode) textNode.nodeValue = newText;
                        else btn.insertBefore(document.createTextNode(newText), btn.firstChild);
                    }

                    const url = (b.main_image && ('' + b.main_image).trim()) || defaultUrl;
                    imgs.forEach(el => {
                        el.setAttribute('src', url);
                        el.style.opacity = '1';
                    });
                    if (h2) h2.style.opacity = '1';
                    if (h1) h1.style.opacity = '1';
                    if (h6) h6.style.opacity = '1';

                    // >>> cập nhật 2 product theo banner hiện tại
                    renderProductBoxes(b);
                }, speed * 0.6);
            };

            // autoplay
            let timer = setInterval(() => {
                index = (index + 1) % BANNERS.length;
                render(index);
            }, autoplayDelay);
            const restart = () => {
                clearInterval(timer);
                timer = setInterval(() => {
                    index = (index + 1) % BANNERS.length;
                    render(index);
                }, autoplayDelay);
            };

            // prev/next

            if (document.querySelector('.home-box-2 span')) {
                const el = document.querySelector('.home-box-2 span');
                el.style.cursor = 'pointer';
                el.addEventListener('click', () => {
                    index = (index + 1) % BANNERS.length;
                    render(index);
                    restart();
                });
            }
            if (document.querySelector('.home-box-1 span')) {
                const el = document.querySelector('.home-box-1 span');
                el.style.cursor = 'pointer';
                el.addEventListener('click', () => {
                    index = (index - 1 + BANNERS.length) % BANNERS.length;
                    render(index);
                    restart();
                });
            }

            const bullets = document.querySelectorAll('.swiper-pagination-bullet');
            bullets.forEach((b, i) => b.addEventListener('click', () => {
                index = i % BANNERS.length;
                render(index);
                restart();
            }));

            // render đầu tiên
            render(0);
        })();
    </script>

    <script src="{{ asset('assets/client/js/newsletter.js') }}"></script>
    <script src="{{ asset('assets/client/js/skeleton-loader.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function getTimeRemaining(endtime) {
            const t = Date.parse(endtime) - Date.now();
            const seconds = Math.floor((t / 1000) % 60);
            const minutes = Math.floor((t / 1000 / 60) % 60);
            const hours = Math.floor((t / (1000 * 60 * 60)) % 24);
            const days = Math.floor(t / (1000 * 60 * 60 * 24));
            return {
                total: t,
                days,
                hours,
                minutes,
                seconds
            };
        }

        function initializeClock($clock, starttimeStr, endtimeStr) {
            const $days = $clock.find('.days');
            const $hours = $clock.find('.hours');
            const $minutes = $clock.find('.minutes');
            const $seconds = $clock.find('.seconds');

            function updateClock() {
                const now = Date.now();
                const start = Date.parse(starttimeStr);
                const end = Date.parse(endtimeStr);

                if (isNaN(start) || isNaN(end)) {
                    $clock.hide();
                    return;
                }
                if (now < start) {
                    // Chưa đến thời gian bắt đầu
                    $clock.hide();
                    return;
                }
                if (now > end) {
                    // Đã hết hạn
                    $clock.hide();
                    return;
                }
                const t = getTimeRemaining(endtimeStr);
                $clock.show();
                $days.text(String(t.days).padStart(2, '0'));
                $hours.text(String(t.hours).padStart(2, '0'));
                $minutes.text(String(t.minutes).padStart(2, '0'));
                $seconds.text(String(t.seconds).padStart(2, '0'));
            }
            updateClock();
            const interval = setInterval(function() {
                const now = Date.now();
                const end = Date.parse(endtimeStr);
                if (now > end) {
                    $clock.hide();
                    clearInterval(interval);
                    return;
                }
                updateClock();
            }, 1000);
        }

        $(document).ready(function() {
            $('.countdown[data-starttime][data-endtime]').each(function() {
                const $clock = $(this);
                const start = $clock.attr('data-starttime');
                const end = $clock.attr('data-endtime');
                if (!start || !end) {
                    $clock.hide();
                    return;
                }
                initializeClock($clock, start, end);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-wishlist').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.id;

                    fetch(`/account/wishlist/add/${productId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status == 'ok') {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: data.message
                                });
                            }
                        })
                        .catch(err => {
                            console.error('Lỗi:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Đã có lỗi xảy ra'
                            });
                        });
                });
            });
        });
    </script>


@endsection
