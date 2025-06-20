@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')
    {{-- <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Danh sách sản phẩm</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="section-b-space pt-0">
        <div class="custom-container container product-contain">
            <div class="title text-start">
                <h3>Danh mục : {{ $category->name }}</h3>
                <svg>
                    <use href="{{ asset('assets/svg/icon-sprite.svg#main-line') }}"></use>

                </svg>
            </div>
            <div class="swiper special-offer-slide-2">
                <div class="swiper-wrapper ratio1_3">
                    {{-- @dd($category) --}}
                    @foreach ($category->products as $product)
                        <a href="">
                            <div class="swiper-slide">
                                <div class="product-box-3">
                                    <div class="img-wrapper">
                                        <div class="label-block"><span class="lable-1">NEW</span>
                                            <a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                                    class="iconsax" data-icon="heart" aria-hidden="true"
                                                    data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i></a>
                                        </div>
                                        <div class="product-image"><a class="pro-first"
                                                href="{{ route('client.products.show', $product->slug) }}"> <img
                                                    class="bg-img"
                                                    src="{{ asset('assets/client/images/product/product-3/11.jpg') }}"
                                                    alt="product"></a><a class="pro-sec" href="{{ route('client.products.show', $product->slug) }}"> <img
                                                    class="bg-img"
                                                    src="{{ asset('assets/client/images/product/product-3/9.jpg') }}"
                                                    alt="product"></a></div>
                                        <div class="countdown"
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
                                        <ul class="rating">
                                            @php
                                                $fullStars = floor($product->rating_avg); // sao đầy
                                                $halfStar = $product->rating_avg - $fullStars >= 0.5; // sao nửa
                                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // sao rỗng
                                            @endphp

                                            {{-- sao đầy --}}
                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <li><i class="fa-solid fa-star"></i></li>
                                            @endfor

                                            {{-- sao nửa --}}
                                            @if ($halfStar)
                                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            @endif

                                            {{-- sao rỗng --}}
                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <li><i class="fa-regular fa-star"></i></li>
                                            @endfor

                                            <li>{{ number_format($product->rating_avg, 1) }}</li>
                                        </ul>

                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                            <h6>{{ $product->name }}</h6>
                                        </a>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $start = \Carbon\Carbon::parse($product->starts_at);
                                            $end = \Carbon\Carbon::parse($product->ends_at);

                                            $isInDiscountTime = $now->between($start, $end);
                                            $finalPrice = $isInDiscountTime
                                                ? $product->base_price * (1 - $product->sale_times / 100)
                                                : $product->sale_price;
                                        @endphp
                                        <p>{{ number_format($finalPrice) }} đ</p>
                                        <del>{{ number_format($product->base_price) }} đ</del>

                                        {{-- Hiển thị nếu đang trong thời gian giảm giá --}}
                                        @if ($isInDiscountTime)
                                            <span>-{{ $product->sale_times }}%</span>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach

                </div>
            </div>
        </div>
    </section>


@endsection
@section('js')
    <script src="{{ asset('assets/client/js/grid-option.js') }}"></script>
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
    </script>




@endsection
