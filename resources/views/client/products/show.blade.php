@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Sản phẩm</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0 product-thumbnail-page">
        <div class="custom-container container">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="row sticky">
                        <div class="col-sm-2 col-3">
                            <div class="swiper product-slider product-slider-img">
                                <div class="swiper-wrapper">
                                    @foreach ($product->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                alt="Ảnh phụ của sản phẩm">
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-10 col-9">
                            <div class="swiper product-slider-thumb product-slider-img-1">
                                <div class="swiper-wrapper ratio_square-2">
                                    @foreach ($product->images as $image)
                                        <div class="swiper-slide">
                                            <img class="bg-img" src="{{ asset('storage/' . $image->image_url) }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-detail-box">
                        <div class="product-option">
                            <div class="move-fast-box d-flex align-items-center gap-1"><img
                                    src="{{ asset('assets/client/images/gif/fire.gif') }}" alt="">
                                <p>Move fast!</p>
                            </div>
                            @php
                                $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse($product->starts_at);
                                $end = \Carbon\Carbon::parse($product->ends_at);

                                $isInDiscountTime = $now->between($start, $end);
                                $finalPrice = $isInDiscountTime
                                    ? $product->base_price * (1 - $product->sale_times / 100)
                                    : $product->sale_price;
                            @endphp
                            <script>
                                const isInDiscountTime = @json($isInDiscountTime);
                                const saleTimes = @json($product->sale_times);
                            </script>
                            <h3>{{ $product->name }}</h3>
                            <p id="main-price">
                                @if ($minPrice == $maxPrice)
                                    {{ number_format($minPrice, 0, ',', '.') }} đ
                                @else
                                    {{ number_format($minPrice, 0, ',', '.') }} đ -
                                    {{ number_format($maxPrice, 0, ',', '.') }} đ
                                @endif

                                {{-- Nếu có base_price và sale_times --}}
                                @if ($product->base_price && $isInDiscountTime)
                                    <del>{{ number_format($product->base_price, 0, ',', '.') }} đ</del>
                                    <span>-{{ $product->sale_times }}%</span>
                                @endif
                            </p>

                            <p></p>
                            <div class="rating">
                                <ul class="rating">
                                    <li>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($rating_summary['avg_rating'] >= $i)
                                                <i class="fa-solid fa-star"></i>
                                            @elseif ($rating_summary['avg_rating'] >= $i - 0.5)
                                                <i class="fa-solid fa-star-half-stroke"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </li>
                                    <li>({{ number_format($rating_summary['avg_rating'], 1) }}) Rating</li>
                                </ul>
                                <div class="product-description">
                                    {!! $product->description !!}
                                </div>

                            </div>
                            <div class="buy-box border-buttom">
                                <ul>
                                    <li> <span data-bs-toggle="modal" data-bs-target="#size-chart" title="Quick View"
                                            tabindex="0"><i class="iconsax me-2" data-icon="ruler"></i>Bảng kích
                                            thước</span>
                                    </li>
                                    <li> <span data-bs-toggle="modal" data-bs-target="#terms-conditions-modal"
                                            title="Quick View" tabindex="0"><i class="iconsax me-2"
                                                data-icon="truck"></i>Chính sách giao hàng & đổi trả</span></li>
                                </ul>
                            </div>
                            @foreach ($attributeGroups as $groupName => $values)
                                <div class="variant-group mb-3" data-attribute="{{ strtolower($groupName) }}">
                                    <h6>{{ ucfirst($groupName) }}</h6>
                                    <ul class="variant-list d-flex gap-2">
                                        @foreach ($values as $val)
                                            <li class="variant-item px-3 py-1 border rounded"
                                                data-value="{{ $val }}" style="cursor: pointer;">
                                                {{ $val }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="variant-error text-danger small mt-1" style="display:none;"></div>
                                </div>
                            @endforeach
                            <div id="variant-info" class="mt-3" style="display: none;">
                                <p>Số lượng còn lại: <span id="variant-quantity"></span></p>
                            </div>
                            <div class="quantity-box d-flex align-items-center gap-3">
                                <div class="quantity">
                                    <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                                    <input type="number" value="1" min="1" max="20">
                                    <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <a href="#" class="btn btn_black sm add-to-cart-btn"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $product->sale_price }}"
                                        data-original-price="{{ $product->base_price }}"
                                        data-image="{{ asset('storage/' . $product->image) }}"
                                        data-brand="{{ $product->brand->name ?? 'Unknown' }}"
                                        data-slug="{{ $product->slug }}"
   data-product-url="{{ route('client.products.show', $product->slug) }}"
                                        aria-controls="offcanvasRight">
                                        
                                        Thêm vào giỏ hàng
                                    </a>

                                    <a href="#" class="btn btn_outline sm buy-now-btn"
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->sale_price }}"
                                        data-product-image="{{ asset('storage/' . $product->image) }}"
                                        data-product-brand="{{ $product->brand->name ?? 'Unknown' }}"
                                        data-max-quantity="{{ $product->stock_quantity }}"
                                        data-variant-id="{{ $selectedVariant->id ?? '' }}" {{-- nếu có variant --}}
                                        data-quantity="1">
                                        Mua ngay
                                    </a>

                                </div>
                            </div>
                            <div class="buy-box">
                                <ul>
                                    <li>
                                        <a class="add-to-wishlist" href="javascript:;" data-id="{{ $product->id }}">
                                            <i class="fa-regular fa-heart me-2"></i>
                                            Yêu thích
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="compare.html">
                                            <i class="fa-solid fa-arrows-rotate me-2"></i>
                                            Add To Compare
                                        </a>
                                    </li> --}}
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#social-box"
                                            title="Quick View" tabindex="0">
                                            <i class="fa-solid fa-share-nodes me-2"></i>
                                            Share
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="sale-box">

                                <div class="countdown"
                                    data-starttime="{{ optional($product->starts_at ? \Carbon\Carbon::parse($product->starts_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}"
                                    data-endtime="{{ optional($product->ends_at ? \Carbon\Carbon::parse($product->ends_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}">

                                    <div class="d-flex align-items-center gap-2"><img
                                            src="{{ asset('assets/client/images/gif/timer.gif') }}" alt="">
                                        <p>Limited Time Left! Hurry, Sale Ending!</p>
                                    </div>
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
                            <div class="dz-info">
                                <ul>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Sku:</h6>
                                            <p>{{ $product->sku }}</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Có sẵn:</h6>
                                            <p>{{ $product->stock_quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Tags:</h6>
                                            <p>
                                                @if ($product->tags)
                                                    {{ $product->tags->pluck('name')->implode(', ') }}
                                                @endif
                                            </p>
                                        </div>
                                    </li>
                                    {{-- <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Vendor:</h6>
                                            <p>{{ $product->vendor_name }}</p>
                                        </div>
                                    </li> --}}
                                </ul>
                            </div>
                            <div class="share-option">
                                <h5>Thanh toán an toàn</h5><img class="img-fluid"
                                    src="{{ asset('assets/client/images/other-img/secure_payments.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="product-section-box x-small-section pt-0">
            <div class="custom-container container">
                <div class="row">
                    <div class="col-12">
                        <ul class="product-tab theme-scrollbar nav nav-tabs nav-underline" id="Product" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="Description-tab" data-bs-toggle="tab"
                                    data-bs-target="#Description-tab-pane" role="tab"
                                    aria-controls="Description-tab-pane" aria-selected="true">Mô tả chi tiết</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specification-tab" data-bs-toggle="tab"
                                    data-bs-target="#specification-tab-pane" role="tab"
                                    aria-controls="specification-tab-pane" aria-selected="false">Thông số sản
                                    phẩm</button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="question-tab" data-bs-toggle="tab"
                                    data-bs-target="#question-tab-pane" role="tab" aria-controls="question-tab-pane"
                                    aria-selected="false">Q & A</button>
                            </li> --}}
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="Reviews-tab" data-bs-toggle="tab"
                                    data-bs-target="#Reviews-tab-pane" role="tab" aria-controls="Reviews-tab-pane"
                                    aria-selected="false">Đánh giá</button>
                            </li>
                        </ul>
                        <div class="tab-content product-content" id="ProductContent">
                            <div class="tab-pane fade show active" id="Description-tab-pane" role="tabpanel"
                                aria-labelledby="Description-tab" tabindex="0">
                                <div class="row gy-4">
                                    <div class="col-12">
                                        {!! $product->detailed_description !!}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="specification-tab-pane" role="tabpanel"
                                aria-labelledby="specification-tab" tabindex="0">

                                @if ($groupedDetails->isNotEmpty())
                                    <div class="table-responsive theme-scrollbar">
                                        <table class="specification-table table table-striped">
                                            <tbody>
                                                @foreach ($groupedDetails as $groupName => $items)
                                                    <!-- Nhóm (ví dụ: Thông tin chung, Kích thước, ...) -->
                                                    <tr>
                                                        <th colspan="2" class="bg-light fw-bold text-dark">
                                                            {{ $groupName }}
                                                        </th>
                                                    </tr>

                                                    <!-- Các item trong nhóm -->
                                                    @foreach ($items as $item)
                                                        <tr>
                                                            <th class="w-40">{{ $item->label }}</th>
                                                            <td>{{ $item->value ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p><strong>Không có số liệu kĩ thuật.</strong></p>
                                @endif

                            </div>
                            <div class="tab-pane fade" id="question-tab-pane" role="tabpanel"
                                aria-labelledby="question-tab" tabindex="0">
                                <div class="question-main-box">
                                    <h5>Have Doubts Regarding This Product ?</h5>
                                    <h6 data-bs-toggle="modal" data-bs-target="#question-modal" title="Quick View"
                                        tabindex="0">Post Your Question</h6>
                                </div>
                                <div class="question-answer">
                                    <ul>
                                        <li>
                                            <div class="question-box">
                                                <p>Q1 </p>
                                                <h6>Which designer created the little black dress?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>The little black dress (LBD) is often
                                                    attributed to the iconic fashion designer Coco Chanel. She popularized
                                                    the concept of the LBD in the 1920s, offering a simple, versatile, and
                                                    elegant garment that became a staple in women's fashion.</span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q2 </p>
                                                <h6>Which First Lady influenced women's fashion in the 1960s?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>The First Lady who significantly
                                                    influenced women's fashion in the 1960s was Jacqueline Kennedy, the wife
                                                    of President John F. Kennedy. She was renowned for her elegant and
                                                    sophisticated style, often wearing simple yet chic outfits that set
                                                    trends during her time in the White House. </span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q3 </p>
                                                <h6>What was the first name of the fashion designer Chanel?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0
                                                        </a></li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>The first name of the fashion designer
                                                    Chanel was Gabrielle. Gabrielle "Coco" Chanel was a pioneering French
                                                    fashion designer known for her timeless designs, including the iconic
                                                    Chanel suit and the little black dress.</span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q4 </p>
                                                <h6>Carnaby Street, famous in the 60s as a fashion center, is in which
                                                    capital?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>Carnaby Street, famous for its
                                                    association with fashion and youth culture in the 1960s, is located in
                                                    London, the capital of the United Kingdom.🎉</span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q5 </p>
                                                <h6>Threadless is a company selling unique what?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>Threadless is a company selling unique
                                                    T-shirts.</span></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Reviews-tab-pane" role="tabpanel"
                                aria-labelledby="Reviews-tab" tabindex="0">
                                <div class="row gy-4">
                                    <div class="col-lg-4">
                                        <div class="review-right">
                                            <div class="customer-rating">
                                                <div class="global-rating">
                                                    <div>
                                                        <h5>{{ number_format($rating_summary['avg_rating'], 2) }}</h5>
                                                    </div>
                                                    <div>
                                                        <h6>Average Ratings</h6>
                                                        <ul class="rating p-0 mb">
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-regular fa-star"></i></li>
                                                            <li><span>({{ $rating_summary['total_rating'] }})</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="rating-progess">
                                                    <li>
                                                        <p>5 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['5_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['5_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>4 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['4_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['4_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>3 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['3_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['3_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>2 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['2_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['2_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>1 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['1_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['1_star_percent'] }}%</p>
                                                    </li>
                                                </ul>
                                                {{-- <button class="btn reviews-modal" data-bs-toggle="modal"
                                                    data-bs-target="#Reviews-modal" title="Quick View"
                                                    tabindex="0">Write a
                                                    review</button> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="comments-box">
                                            <h5>Đánh giá </h5>
                                            <ul class="theme-scrollbar">
                                                @foreach ($reviews as $review)
                                                    <li style="width:100%">
                                                        <div class="comment-items">
                                                            <div class="user-img"> <img
                                                                    src="{{ $review->user_avatar ?? asset('assets/client/images/user/3.jpg') }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="user-content">
                                                                <div class="user-info">
                                                                    <div class="d-flex justify-content-between gap-3">
                                                                        <h6><i class="iconsax" data-icon="user-1"></i>
                                                                            {{ $review->user->fullname ?? 'Ẩn danh' }}
                                                                        </h6>


                                                                        <span> <i class="iconsax"
                                                                                data-icon="clock"></i>{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                                                    </div>
                                                                    <!-- Hiển thị số sao đánh giá -->
                                                                    <ul class="rating p-0 mb">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <li>
                                                                                <i
                                                                                    class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                                            </li>
                                                                        @endfor
                                                                    </ul>
                                                                    @if ($review->variant_values)
                                                                        @php
                                                                            $variant_values = json_decode(
                                                                                $review->variant_values,
                                                                            );
                                                                            if (is_string($variant_values)) {
                                                                                $variant_values = json_decode(
                                                                                    $variant_values,
                                                                                    true,
                                                                                );
                                                                            }
                                                                        @endphp
                                                                        @if (is_array($variant_values) && count($variant_values) > 0)
                                                                            <div>
                                                                                @foreach ($variant_values as $key => $value)
                                                                                    <span>{{ ucfirst($key) }}</span>:
                                                                                    <span>{{ $value }}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                @if ($review->approved)
                                                                    <p>{{ $review->comment }}</p>
                                                                @endif

                                                                {{-- <a href="#"> <span> <i class="iconsax" data-icon="undo"></i>
                                                                        Replay</span></a> --}}
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
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
    <section class="section-b-space pt-0">
        <div class="custom-container container product-contain">
            <div class="title text-start">
                <h3>Sản phẩm liên quan</h3>
                <svg>
                    <use href="{{ asset('assets/svg/icon-sprite.svg#main-line') }}"></use>

                </svg>
            </div>
            <div class="swiper special-offer-slide-2">
                <div class="swiper-wrapper ratio1_3">
                    @foreach ($product->related_products as $value)
                        <div class="swiper-slide">
                            <div class="product-box-3">
                                <div class="img-wrapper">
                                    <div class="label-block"><span class="lable-1">NEW</span><a
                                            class="label-2 wishlist-icon add-to-wishlist" data-id="{{ $value->id }}"
                                            href="javascript:void(0)" tabindex="0"><i class="iconsax"
                                                data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                                data-bs-title="Add to Wishlist"></i></a></div>
                                    <div class="product-image"><a class="pro-first"
                                            href="{{ route('client.products.show', $value->id) }}"> <img class="bg-img"
                                                src="{{ asset('storage/' . $value->image) }}"
                                                alt="Áo phông cucci LV collab"></a><a class="pro-sec"
                                            href="product.html"> <img class="bg-img"
                                                src="{{ asset('storage/' . $value->image) }}"
                                                alt="Áo phông cucci LV collab"></a></div>
                                    <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                                data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                                data-bs-title="Add to cart"> </i></a><a href="compare.html"
                                            tabindex="0"><i class="iconsax" data-icon="arrow-up-down"
                                                aria-hidden="true" data-bs-toggle="tooltip"
                                                data-bs-title="Compare"></i></a><a href="#" data-bs-toggle="modal"
                                            data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                                data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                                data-bs-title="Quick View"></i></a></div>
                                    <div class="countdown">
                                        <ul class="clockdiv2">
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
                                    <ul class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($value->reviews_avg_rating >= $i)
                                                <li><i class="fa-solid fa-star"></i></li>
                                            @elseif ($value->reviews_avg_rating >= $i - 0.5)
                                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            @else
                                                <li><i class="fa-regular fa-star"></i></li>
                                            @endif
                                        @endfor
                                        <li>({{ number_format($value->reviews_avg_rating, 1) }})</li>
                                    </ul>

                                    <a href="{{ route('client.products.show', $value->slug) }}">
                                        <h6>{{ $value->name }}</h6>
                                    </a>

                                    <p>
                                        {{ number_format($value->sale_price, 0, ',', '.') }} đ
                                        <del>{{ number_format($value->base_price, 0, ',', '.') }} đ</del>
                                        <span>-{{ round((($value->base_price - $value->sale_price) / $value->base_price) * 100) }}%</span>
                                    </p>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <div class="modal theme-modal fade" id="size-chart" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Bảng kích thước</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0 text-center">
                    @if (!empty($sizeChart))
                        <a href="{{ asset('storage/' . $sizeChart) }}" target="_blank">
                            <img class="img-fluid" src="{{ asset('storage/' . $sizeChart) }}" alt="Size chart">
                        </a>
                    @else
                        <p class="text-muted">⚠️ Chưa có bảng size cho sản phẩm này</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal theme-modal fade question-answer-modal" id="question-box" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Ask a Question</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="reviews-product">
                                <div> <img src="../assets/images/modal/0.jpg" alt="">
                                    <div>
                                        <h5>Denim Skirts Corset Blazer</h5>
                                        <p>$20.00
                                            <del>$35.00 </del>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="from-group">
                                <label class="form-label">Your Question :</label>
                                <textarea class="form-control" id="comment" cols="30" rows="4"
                                    placeholder="Write your Question here..."></textarea>
                            </div>
                        </div>
                        <div class="modal-button-group">
                            <button class="btn btn-cancel" type="submit" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button class="btn btn-submit" type="submit" data-bs-dismiss="modal"
                                aria-label="Close">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    @php
        $productUrl = route('client.products.show', $product->slug);
    @endphp


    <div class="modal theme-modal fade social-modal" id="social-box" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>Copy link</h6>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input class="form-field form-field--input" type="text" value="{{ $productUrl }}" readonly>
                    <h6>Share:</h6>
                    <ul class="d-flex gap-3 list-unstyled">
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($productUrl) }}"
                                target="_blank">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode($productUrl) }}&media={{ urlencode($product->image_url) }}&description={{ urlencode($product->name) }}"
                                target="_blank">
                                <i class="fa-brands fa-pinterest-p"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($productUrl) }}&text={{ urlencode($product->name) }}"
                                target="_blank">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/?url={{ urlencode($productUrl) }}" target="_blank">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="terms-conditions-modal modal theme-modal fade" id="terms-conditions-modal" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Chính sách giao hàng & đổi trả</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    @if (!empty($returnPolicy))
                        {!! $returnPolicy !!}
                    @else
                        <p class="text-muted">Chưa có chính sách đổi trả.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="customer-reviews-modal modal theme-modal fade" id="Reviews-modal" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Viết đánh giá của bạn</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    @auth
                        <form id="rating-form" action="{{ route('client.review') }}" method="POST" class="row g-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="rating" id="rating-value" value="0">

                            <div class="col-12">
                                <div class="reviews-product d-flex gap-3">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="" width="80">
                                    <div>
                                        <h5>{{ $product->name }}</h5>
                                        <p>{{ $product->sale_price }}đ <del>{{ $product->base_price }}đ</del></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="customer-rating">
                                    <label class="form-label">Đánh giá</label>
                                    <ul class="rating p-0 mb-0 d-flex" style="list-style: none; cursor: pointer;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li class="star" data-value="{{ $i }}">
                                                <i class="fa-regular fa-star fs-4 me-1"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Nội dung :</label>
                                    <textarea name="comment" class="form-control" id="comment" cols="30" rows="4"
                                        placeholder="Write your comments here..." required></textarea>
                                </div>
                            </div>

                            <div class="modal-button-group d-flex gap-2">
                                <button class="btn btn-cancel" type="button" data-bs-dismiss="modal">Hủy</button>
                                <button class="btn btn-submit submit-rating" type="button">Gửi</button>
                            </div>
                        </form>

                        <script>
                            const stars = document.querySelectorAll('.star');
                            const ratingInput = document.getElementById('rating-value');
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('rating-form');
                                const stars = document.querySelectorAll('.star');
                                const ratingInput = document.getElementById('rating-value');
                                const commentInput = document.getElementById('comment');

                                stars.forEach((star, index) => {
                                    star.addEventListener('click', () => {
                                        const rating = star.getAttribute('data-value');
                                        ratingInput.value = rating;

                                        stars.forEach(s => s.querySelector('i').classList.replace('fa-solid',
                                            'fa-regular'));
                                        stars.forEach(s => s.querySelector('i').classList.replace('fa-solid',
                                            'fa-regular'));

                                        for (let i = 0; i < rating; i++) {
                                            stars[i].querySelector('i').classList.replace('fa-regular', 'fa-solid');
                                        }
                                    });
                                });

                                document.querySelectorAll('.submit-rating').forEach(button => {
                                    button.addEventListener('click', function() {
                                        const rate = ratingInput.value;
                                        const comment = commentInput.value;
                                        if (isNaN(rate) || (rate <= 0 || rate > 5)) {
                                            Swal.fire('Thông báo', 'Vui lòng lựa chọn đánh giá của bạn', 'warning');
                                            return;
                                        }
                                        if (comment == '') {
                                            Swal.fire('Thông báo', 'Vui lòng nhập nội dung đánh giá', 'warning');
                                            return;
                                        }
                                        form.submit();
                                    })
                                });
                            })
                        </script>
                    @endauth
                    @guest
                        <div class="alert alert-warning mt-3 d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <strong>Bạn cần đăng nhập</strong> để đánh giá và bình luận sản phẩm.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </div>

                    @endguest


                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/client/js/grid-option.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const allVariants = @json($variants);
        const variantGroups = document.querySelectorAll('.variant-group');
        const productStock = {{ $product->stock_quantity }};

        // Normalize key để so sánh key như "Màu sắc" và "mau_sac"
        function normalize(str) {
            return str
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .toLowerCase()
                .replace(/\s+/g, '_');
        }

        // Lấy các lựa chọn hiện tại
        function getSelectedAttributes() {
            const selected = {};
            variantGroups.forEach(group => {
                const groupName = group.getAttribute('data-attribute');
                const active = group.querySelector('.variant-item.active');
                if (active) {
                    selected[groupName] = active.getAttribute('data-value');
                }
            });
            return selected;
        }

        // So khớp biến thể đã chọn với biến thể thực tế trong allVariants
        function attributesMatch(a, b) {
            const keysA = Object.keys(a);
            const keysB = Object.keys(b);
            if (keysA.length !== keysB.length) return false;

            return keysA.every(keyA => {
                const keyB = keysB.find(k => normalize(k) === normalize(keyA));
                return keyB && a[keyA] === b[keyB];
            });
        }

        // Cập nhật thông tin biến thể
        function updateVariantInfo() {
            const selected = getSelectedAttributes();

            // ✅ Nếu không có biến thể nào (sản phẩm không có variant group)
            if (variantGroups.length === 0) {
                const qtyEl = document.getElementById('variant-quantity');
                if (productStock <= 0) {
                    qtyEl.textContent = 'Hết hàng';
                    qtyEl.style.color = 'red';
                } else {
                    qtyEl.textContent = productStock;
                    qtyEl.style.color = '';
                }

                document.getElementById('variant-info').style.display = 'block';
                document.getElementById('main-price').textContent = "{{ number_format($finalPrice) }} đ";
                return;
            }

            // ✅ Nếu có biến thể nhưng chưa chọn đủ
            // if (Object.keys(selected).length !== variantGroups.length) {
            //     document.getElementById('variant-info').style.display = 'none';
            //     document.getElementById('main-price').textContent = "{{ number_format($finalPrice) }} đ";
            //     return;
            // }
            // JS truyền min-max từ PHP
            const minPrice = @json($minPrice);
            const maxPrice = @json($maxPrice);

            // ✅ Nếu có biến thể nhưng chưa chọn đủ
            if (Object.keys(selected).length !== variantGroups.length) {
                document.getElementById('variant-info').style.display = 'none';
                if (minPrice === maxPrice) {
                    document.getElementById('main-price').textContent = new Intl.NumberFormat().format(minPrice) + ' đ';
                } else {
                    document.getElementById('main-price').textContent =
                        new Intl.NumberFormat().format(minPrice) + ' đ - ' +
                        new Intl.NumberFormat().format(maxPrice) + ' đ';
                }
                return;
            }

            // ✅ Tìm biến thể phù hợp với lựa chọn
            const matched = allVariants.find(v => attributesMatch(selected, v.attributes));
            if (matched) {
                const quantity = matched.quantity;
                const qtyEl = document.getElementById('variant-quantity');

                if (quantity <= 0) {
                    qtyEl.textContent = 'Hết hàng';
                    qtyEl.style.color = 'red';
                } else {
                    qtyEl.textContent = quantity;
                    qtyEl.style.color = '';
                }

                document.getElementById('variant-info').style.display = 'block';
                const formattedPrice = new Intl.NumberFormat().format(Math.round(matched.price)) + ' đ';
                document.getElementById('main-price').textContent = formattedPrice;
            }
        }


        // Bắt sự kiện click vào mỗi lựa chọn
        document.querySelectorAll('.variant-item').forEach(item => {
            item.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.variant-item').forEach(i => i.classList.remove(
                    'active'));
                this.classList.add('active');
                updateVariantInfo();
            });
        });
    </script>


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

                const matched = allVariants.find(v => attributesMatch(selected, v.attributes));
                if (matched) {
                    const quantity = matched.quantity;

                    const qtyEl = document.getElementById('variant-quantity');
                    if (quantity <= 0) {
                        qtyEl.textContent = 'Hết hàng';
                        qtyEl.style.color = 'red';
                    } else {
                        qtyEl.textContent = quantity;
                        qtyEl.style.color = '';
                    }

                    document.getElementById('variant-info').style.display = 'block';
                    const formattedPrice = new Intl.NumberFormat().format(Math.round(matched.price)) + ' đ';
                    document.getElementById('main-price').textContent = formattedPrice;
                }



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
    </script>



    <script>
        window.variantData = @json($variants);
        console.log(window.variantData);

        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Xử lý chọn thuộc tính
            document.querySelectorAll('.variant-item').forEach(item => {
                item.addEventListener('click', function() {
                    const group = this.closest('.variant-list');
                    if (!group) return;

                    group.querySelectorAll('.variant-item').forEach(i => {
                        i.classList.remove('active');
                        i.style.setProperty('border', '1px solid #ddd', 'important');
                        i.style.fontWeight = 'normal';
                        i.style.color = '';
                    });

                    this.classList.add('active');
                    this.style.setProperty('border', '2px solid #222', 'important');
                    this.style.fontWeight = 'bold';
                    this.style.color = '#222';
                });
            });

            
            // ✅ Lấy ID biến thể từ selected attributes
            function getSelectedVariantId(attributes) {
                const variantData = window.variantData || [];
                return variantData.find(v => {
                    return Object.entries(attributes).every(([key, val]) => {
                        // So sánh key và value đều không phân biệt hoa thường
                        return (
                            Object.keys(v.attributes).some(attrKey =>
                                attrKey.toLowerCase() === key.toLowerCase() &&
                                v.attributes[attrKey].toLowerCase() === val.toLowerCase()
                            )
                        );
                    });
                })?.id || null;
            }


            // ✅ Sự kiện Add to Cart
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {

                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const image = this.dataset.image;
                    const brand = this.dataset.brand || 'Unknown';
                    const quantity = parseInt(document.querySelector('.quantity input')?.value ||
                        1);

                    const currentUser = localStorage.getItem('currentUser') || 'guest';
                    const cartKey = `cartItems_${currentUser}`;
                    const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

                    const selectedAttributes = {};
                    let valid = true;
                    const missingAttrs = [];

                    document.querySelectorAll('.variant-group').forEach(group => {
                        const attrName = group.dataset.attribute;
                        const selected = group.querySelector('.variant-item.active');

                        if (!selected) {
                            valid = false;
                            missingAttrs.push(attrName);
                        } else {
                            selectedAttributes[attrName] = selected.dataset.value ||
                                selected.textContent.trim();
                        }
                    });

                    if (!valid) {
                        missingAttrs.forEach(attr => showToast('Thông báo',`Vui lòng chọn ${attr}`));
                        return;
                    }

                    let matchedVariant = null;

                    // ✅ Trường hợp sản phẩm KHÔNG có biến thể
                    if (!window.variantData || window.variantData.length === 0) {
                        const rawQtyText = document.getElementById('variant-quantity')?.textContent
                            ?.trim().toLowerCase() || '0';
                        const stockQty = rawQtyText.includes('hết hàng') ? 0 : parseInt(rawQtyText
                            .replace(/\D/g, '') || '0');
                        if (stockQty <= 0) {
                            showToast('Thông báo','Sản phẩm đã hết hàng');
                            return;
                        }

                        let price = parseFloat(this.dataset.price || 0);
                        let originalPrice = parseFloat(this.dataset.originalPrice || price);



                        matchedVariant = {
                            id: null,
                            quantity: stockQty,
                            price: parseFloat(this.dataset.price || 0),
                            original_price: parseFloat(this.dataset.originalPrice || this
                                .dataset.price || 0)
                        };
                    } else {
                        // ✅ Có biến thể
                        matchedVariant = window.variantData.find(v =>
                            Object.entries(selectedAttributes).every(([key, val]) => {
                                const matchedKey = Object.keys(v.attributes).find(k =>
                                    normalize(k) === normalize(key));
                                return matchedKey && normalize(v.attributes[matchedKey]) ===
                                    normalize(val);
                            })
                        );

                        if (!matchedVariant) {
                            showToast('Thông báo','Không tìm thấy biến thể phù hợp');
                            return;
                        }

                        if (matchedVariant.quantity <= 0) {
                            showToast('Thông báo','Sản phẩm đã hết hàng');
                            return;
                        }
                    }

                    const variantId = matchedVariant.id;
                    const price = matchedVariant.price;
                    const originalPrice = matchedVariant.original_price || price;

                    // ✅ Kiểm tra tồn kho trước khi cộng dồn vào giỏ
                    const index = cartItems.findIndex(item =>
                        item.id === id &&
                        ((variantId && item.variant_id === variantId) ||
                            (!variantId && JSON.stringify(item.attributes || {}) === JSON
                                .stringify(selectedAttributes)))
                    );

                    const existingQty = index !== -1 ? cartItems[index].quantity : 0;
                    const totalQty = existingQty + quantity;

                    if (totalQty > matchedVariant.quantity) {
                        showToast('Thông báo',`Chỉ còn ${matchedVariant.quantity} sản phẩm trong kho. Vui lòng giảm số lượng.`);
                        return;
                    }

                    const slug = this.dataset.slug;
const productUrl = this.dataset.productUrl;

                    // ✅ Thêm hoặc cập nhật vào giỏ
                    if (index !== -1) {
                        cartItems[index].quantity += quantity;
                    } else {
                        cartItems.push({
                            id,
                            variant_id: variantId,
                            name,
                            price,
                            originalPrice,
                            image,
                            quantity,
                            brand,
                            attributes: selectedAttributes,
                            max_quantity: matchedVariant.quantity,
                            slug,                        // <—
  productUrl 
                            
                        });
                    }

                    localStorage.setItem(cartKey, JSON.stringify(cartItems));
                    document.dispatchEvent(new Event('cartUpdated'));
                    updateCartBadge();

                    const offcanvasEl = document.getElementById('offcanvasRight');
                    if (offcanvasEl) {
                        const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
                        bsOffcanvas.show();
                    }
                });
            });

            //Buy now
            const buyNowButtons = document.querySelectorAll('.buy-now-btn');

            buyNowButtons.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const id = btn.dataset.productId;
                    const name = btn.dataset.productName;
                    const image = btn.dataset.productImage;
                    const quantity = parseInt(document.querySelector('.quantity input')?.value ||
                        1);
                    const brand = btn.dataset.productBrand || 'Unknown';

                    const currentUser = localStorage.getItem('currentUser') || 'guest';
                    const cartKey = `cartItems_${currentUser}`;
                    const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

                    // Lấy selected attributes
                    const selectedAttributes = {};
                    let valid = true;
                    const missingAttrs = [];

                    document.querySelectorAll('.variant-group').forEach(group => {
                        const attrName = group.dataset.attribute;
                        const selected = group.querySelector('.variant-item.active');

                        if (!selected) {
                            valid = false;
                            missingAttrs.push(attrName);
                        } else {
                            selectedAttributes[attrName] = selected.dataset.value ||
                                selected.textContent.trim();
                        }
                    });

                    if (!valid) {
                        missingAttrs.forEach(attr => {
                            showToast('Thông báo',`Vui lòng chọn ${attr}`);
                        });
                        return;
                    }
                    // Lấy variantId nếu có
                    const variantId = getSelectedVariantId(selectedAttributes);

                    let price = parseFloat(btn.dataset.productPrice);

                    if (variantId) {
                        const matchedVariant = window.variantData.find(v => v.id === variantId);
                        if (matchedVariant) {
                            // ✅ Kiểm tra tồn kho
                            if (matchedVariant.quantity <= 0) {
                                showToast('Thông báo','Sản phẩm đã hết hàng');
                                return;
                            }
                            price = matchedVariant.price;
                        }
                    }

                    // Xóa toàn bộ giỏ trước khi thêm mới 1 sản phẩm (Buy Now chỉ mua 1 sản phẩm)
                    const newCart = [{
                        id,
                        variant_id: variantId,
                        name,
                        price,
                        image,
                        quantity,
                        brand,
                        attributes: selectedAttributes
                    }];

                    localStorage.setItem(cartKey, JSON.stringify(newCart));
                    document.dispatchEvent(new Event('cartUpdated'));

                    // Chuyển đến trang giỏ hàng
                    window.location.href = "{{ route('client.cart.index') }}";
                });
            });

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

    <script>
        updateVariantInfo();
    </script>

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Thông báo',
                text: '{{ session('warning') }}',
                confirmButtonText: 'OK',
                timer: 1200,
            });
        </script>
    @endif



     <!-- HTML hiện tại của bạn giữ nguyên -->
<!-- Thêm CSS nhỏ để thấy phần được chọn -->
<style>
  .variant-item.active { outline: 2px solid #c69c6d; }
  .variant-item[hidden] { display: none !important; }
  .variant-item.disabled { opacity:.45; pointer-events:none; }
</style>

<script>
// ====== 1) DỮ LIỆU BIẾN THỂ TỪ BACKEND (đang bật) ======
const VARIANTS = @json($variants); // [{id, attributes:{Màu:'Cam', Size:'M', ...}, price, quantity,...}]

// Chuẩn hoá mảng làm việc
const variantList = VARIANTS.map(v => ({ id: v.id, attrs: v.attributes }));

// ====== 2) BẮT THAM CHIẾU CÁC NÚT LỰA CHỌN ======
const groups = [...document.querySelectorAll('.variant-group')];
const optionEls = {}; // { 'màu sắc' => Map(value => <li>), 'size' => Map(...) }
groups.forEach(g => {
  const attr = g.dataset.attribute;               // vd: 'màu sắc', 'size' (đang là lowercase theo blade)
  optionEls[attr] = new Map();
  g.querySelectorAll('.variant-item').forEach(li => {
    optionEls[attr].set(li.dataset.value, li);    // li có data-value="Cam"/"M" ...
  });
});

// ====== 3) HÀM TIỆN ÍCH ======
const clone = o => JSON.parse(JSON.stringify(o));

function matchVariantIds(filter) {
  // Trả về set id các biến thể khớp toàn bộ {attr:value} trong filter
  return new Set(
    variantList.filter(v =>
      Object.entries(filter).every(([a,val]) => (v.attrs[a] ?? v.attrs[capitalize(a)]) === val)
    ).map(v => v.id)
  );
}

function allowedValuesFor(attr, currentSel) {
  // Giá trị hợp lệ của 'attr' khi cố định các lựa chọn khác
  const other = clone(currentSel); delete other[attr];
  const matched = matchVariantIds(other);
  const values = new Set();
  variantList.forEach(v => {
    if (matched.size === 0 || matched.has(v.id)) {
      const vAttrVal = v.attrs[attr] ?? v.attrs[capitalize(attr)];
      if (vAttrVal != null) values.add(vAttrVal);
    }
  });
  return values;
}

function capitalize(s){ return (s||"").charAt(0).toUpperCase()+s.slice(1); }

// ====== 4) TRẠNG THÁI LỰA CHỌN & RENDER ======
const selected = {};   // vd: { 'màu sắc':'Cam', 'size':'XS' }

function refreshUI() {
  // Với từng thuộc tính, tính giá trị hợp lệ rồi ẩn/hiện
  Object.keys(optionEls).forEach(attr => {
    const allowed = allowedValuesFor(attr, selected);
    optionEls[attr].forEach((li, val) => {
      const ok = allowed.has(val);
      // Ẩn hoàn toàn lựa chọn không hợp lệ
      li.hidden = !ok;
      li.classList.toggle('disabled', !ok);
      if (!ok && li.classList.contains('active')) {
        li.classList.remove('active');
        delete selected[attr];
      }
    });

    // Hiển thị thông báo nếu không còn lựa chọn
    const errBox = document.querySelector(`.variant-group[data-attribute="${attr}"] .variant-error`);
    if (errBox) {
      errBox.style.display = allowed.size ? 'none' : 'block';
      errBox.textContent = allowed.size ? '' : 'Không còn lựa chọn phù hợp.';
    }
  });

  // Nếu đã chọn đủ (tất cả nhóm) và khớp đúng 1 biến thể -> có thể cập nhật giá/ tồn kho
  const chosenCount = Object.keys(optionEls).length;
  if (Object.keys(selected).length === chosenCount) {
    const ids = matchVariantIds(selected);
    if (ids.size === 1) {
      // TODO: cập nhật UI giá/tồn kho nếu bạn muốn
      // const chosen = VARIANTS.find(v => v.id === [...ids][0]);
      // document.querySelector('#price').textContent = formatPrice(chosen.price);
      // document.querySelector('#stock').textContent = chosen.quantity;
    }
  }
}

// ====== 5) GẮN SỰ KIỆN CLICK ======
document.querySelectorAll('.variant-item').forEach(li => {
  li.addEventListener('click', () => {
    if (li.hidden || li.classList.contains('disabled')) return;

    const group = li.closest('.variant-group').dataset.attribute;
    // bỏ active các anh em trong cùng nhóm
    li.parentElement.querySelectorAll('.variant-item').forEach(sib => sib.classList.remove('active'));
    // chọn mới
    li.classList.add('active');
    selected[group] = li.dataset.value;

    refreshUI();
  });
});

// ====== 6) KHỞI TẠO ======
refreshUI();
</script>
@endsection
