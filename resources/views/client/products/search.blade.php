@extends('layouts.client')

@section('title', 'Tìm kiếm')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Search</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Search</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space pt-0">
        <div class="custom-container container">
            <div class="row gy-4">
                <div class="col-12 m-auto">
                    <div class="title-1">
                        <p class="justify-content-center">Use Search<span></span></p>
                        <h3 class="text-center">Search For Products</h3>
                    </div>
                </div>

                <form action="{{ route('client.products.search') }}" method="GET">
                    <div class="col-lg-5 col-sm-8 m-auto">
                        <div class="main-search-box position-relative">
                            <div class="d-flex align-items-center">
                                <input type="search" name="keyword" placeholder="Search Here..." class="form-control"
                                    value="{{ request('keyword') }}">
                                <i class="iconsax" data-icon="search-normal-2"></i>
                                <button type="submit" class="btn btn_black sm ms-2">Search</button>

                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-12">
                    <div class="row ratio1_3 gy-4 search-item">
                        @forelse ($products as $product)
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="product-box-3">
                                    <div class="img-wrapper">
                                        <div class="label-block">
                                            <span class="lable-1">NEW</span>
                                            <a class="label-2 wishlist-icon" href="javascript:void(0)">
                                                <i class="iconsax" data-icon="heart"></i>
                                            </a>
                                        </div>
                                        <div class="product-image">
                                            <a href="{{ route('client.products.show', $product->slug) }}"
                                                style="display: block;">
                                                <div class="product-image bg-size"
                                                    style="background-image: url('{{ asset('storage/' . $product->image) }}');
                                                                                                                                                                                                    background-size: cover;
                                                                                                                                                                                                    background-position: center;">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-detail">
                                        <ul class="rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            <li><i class="fa-regular fa-star"></i></li>
                                            <li>4.3</li>
                                        </ul>
                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                            <h6>{{ $product->name }}</h6>
                                        </a>
                                        <p>
                                            {{ number_format($product->sale_price ?? $product->base_price) }}đ
                                            @if ($product->sale_price)
                                                <del>{{ number_format($product->base_price) }}đ</del>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center">
                                    Không tìm thấy sản phẩm nào phù hợp với từ khóa <strong>{{ request('keyword') }}</strong>.
                                </p>
                            </div>
                        @endforelse
                    </div>

                </div>
                <div class="col-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection