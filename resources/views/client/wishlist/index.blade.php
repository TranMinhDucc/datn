@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Wishlist</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="{{route('client.home')}}">Home </a></li>
                            <li class="breadcrumb-item active"> <a href="#">Wishlist</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0">
        <div class="custom-container container wishlist-box">
            <div class="product-tab-content ratio1_3">
                <div class="row-cols-xl-4 row-cols-md-3 row-cols-2 grid-section view-option row gy-4 g-xl-4">
                    @if(isset($wishlists))
                        @foreach ($wishlists as $item)
                            @php
                                $product = $item->product;
                            @endphp
                            <div class="col">
                                <div class="product-box-3 product-wishlist">
                                    <div class="img-wrapper">
                                        <div class="label-block">
                                            <a class="label-2 wishlist-icon delete-button delete-wishlist"
                                                href="javascript:void(0)" data-id="{{ $product->id }}" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                                                    data-icon="trash" aria-hidden="true"></i></a>
                                            <form id="remove-wishlist-{{ $product->id }}"
                                                action="{{ route('client.account.wishlist.remove', $product->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                        <div class="product-image">
                                            <a class="pro-first"
                                                href="{{ route('client.products.show', $product->slug) }}">
                                                <img class="bg-img"
                                                    src="{{ asset('storage/' . $product->image ?? 'assets/client/images/no-image.png') }}"
                                                    alt="{{ $product->name }}">
                                            </a>
                                            <a class="pro-sec"
                                                href="{{ route('client.products.show', $product->slug) }}">
                                                <img class="bg-img"
                                                    src="{{ asset('storage/' . $product->image ?? 'assets/client/images/no-image.png') }}"
                                                    alt="{{ $product->name }}">
                                            </a>
                                        </div>
                                        <div class="cart-info-icon">
                                            <a href="#" title="Add to cart"><i class="iconsax"
                                                    data-icon="basket-2"></i></a>
                                            <a href="#" title="Compare"><i class="iconsax"
                                                    data-icon="arrow-up-down"></i></a>
                                            <a href="#" title="Quick View"><i class="iconsax"
                                                    data-icon="eye"></i></a>
                                        </div>
                                    </div>
                                    @php
                                        // Lấy đánh giá
                                        $reviews = App\Models\Review::where('product_id', $product->id)
                                            ->where('approved', true)
                                            ->with('user')
                                            ->latest()
                                            ->get();

                                        $rating_summary = [
                                            'avg_rating' => null,
                                            'total_rating' => count($reviews),
                                            '5_star_percent' => 0,
                                            '4_star_percent' => 0,
                                            '3_star_percent' => 0,
                                            '2_star_percent' => 0,
                                            '1_star_percent' => 0,
                                        ];

                                        if ($rating_summary['total_rating'] > 0) {
                                            $star_5 = $star_4 = $star_3 = $star_2 = $star_1 = 0;

                                            foreach ($reviews as $review) {
                                                switch ($review->rating) {
                                                    case '1':
                                                        $star_1++;
                                                        break;
                                                    case '2':
                                                        $star_2++;
                                                        break;
                                                    case '3':
                                                        $star_3++;
                                                        break;
                                                    case '4':
                                                        $star_4++;
                                                        break;
                                                    case '5':
                                                        $star_5++;
                                                        break;
                                                }
                                            }

                                            $total = $rating_summary['total_rating'];
                                            $rating_summary['1_star_percent'] = round($star_1 / $total * 100);
                                            $rating_summary['2_star_percent'] = round($star_2 / $total * 100);
                                            $rating_summary['3_star_percent'] = round($star_3 / $total * 100);
                                            $rating_summary['4_star_percent'] = round($star_4 / $total * 100);
                                            $rating_summary['5_star_percent'] = round($star_5 / $total * 100);
                                            $rating_summary['avg_rating'] = ($star_5 * 5 + $star_4 * 4 + $star_3 * 3 + $star_2 * 2 + $star_1) / $total;
                                        }
                                    @endphp
                                    <div class="product-detail">
                                        <ul class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($rating_summary['avg_rating'] >= $i)
                                                    <li><i class="fa-solid fa-star"></i></li>
                                                @elseif ($rating_summary['avg_rating'] >= $i - 0.5)
                                                    <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                                @else
                                                    <li><i class="fa-regular fa-star"></i></li>
                                                @endif
                                            @endfor
                                            <li>{{ $rating_summary['avg_rating'] }}</li>
                                        </ul>

                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                            <h6>{{ $product->name }}</h6>
                                        </a>
                                        <p>{{ number_format($product->sale_price, 0, ',', '.') }} đ</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ✅ Xác nhận xoá khỏi wishlist
            document.querySelectorAll('.delete-wishlist').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc muốn xoá?',
                        text: 'Sản phẩm sẽ bị xoá khỏi danh sách yêu thích!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`remove-wishlist-${productId}`)
                                .submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
