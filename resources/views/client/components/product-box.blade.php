{{-- file: resources/views/client/components/product-box.blade.php --}}
<div class="col-xxl-3 col-md-4 col-6">
    <div class="product-box">
        <div class="img-wrapper">
            @if($product->starts_at && $product->ends_at)
                <div class="label-block">
                    <img src="{{ asset('assets/client/images/product/3.png') }}" alt="label">
                    <span>on <br>Sale!</span>
                </div>
            @endif
            <a href="{{ route('client.products.show', $product->slug) }}" style="display: block;"></a>
            <div class="cart-info-icon">
                <a class="wishlist-icon add-to-wishlist" href="javascript:void(0)" data-id="{{ $product->id }}">
                    <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                </a>
            </div>
            <div class="product-image">
                <a class="pro-first" href="{{ route('client.products.show', $product->slug) }}">
                    <img class="bg-img" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                </a>
            </div>
            <div class="countdown" style="bottom: 5px;"
                data-starttime="{{ optional($product->starts_at ? \Carbon\Carbon::parse($product->starts_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}"
                data-endtime="{{ optional($product->ends_at ? \Carbon\Carbon::parse($product->ends_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}">
                <ul>
                    <li><div class="timer"><div class="days"></div></div><span class="title">Days</span></li>
                    <li class="dot"><span>:</span></li>
                    <li><div class="timer"><div class="hours"></div></div><span class="title">Hours</span></li>
                    <li class="dot"><span>:</span></li>
                    <li><div class="timer"><div class="minutes"></div></div><span class="title">Min</span></li>
                    <li class="dot"><span>:</span></li>
                    <li><div class="timer"><div class="seconds"></div></div><span class="title">Sec</span></li>
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
                <span>{{ $product->rating_avg ?? '0' }} <i class="fa-solid fa-star"></i></span>
            </div>
            <a href="{{ route('client.products.show', $product->slug) }}">
                <h6>{{ $product->name }}</h6>
            </a>
            @php
                $now = \Carbon\Carbon::now();
                $start = $product->starts_at ? \Carbon\Carbon::parse($product->starts_at) : null;
                $end = $product->ends_at ? \Carbon\Carbon::parse($product->ends_at) : null;
                $isInDiscountTime = $start && $end ? $now->between($start, $end) : false;
                $finalPrice = $isInDiscountTime
                    ? $product->base_price * (1 - $product->sale_times / 100)
                    : ($product->sale_price ?? $product->base_price);
            @endphp
            <p>{{ number_format($finalPrice) }} đ</p>
            @if ($product->sale_price || $isInDiscountTime)
                <del>{{ number_format($product->base_price) }} đ</del>
            @endif
            @if ($isInDiscountTime)
                <span>-{{ $product->sale_times }}%</span>
            @endif
        </div>
    </div>
</div>
