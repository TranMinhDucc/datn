@extends('layouts.client')

@section('title', 'Lọc sản phẩm')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Bộ sưu tập – Lọc dễ mê</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                            <li class="breadcrumb-item active"> <a href="{{ route('client.products.filterSidebar') }}">Bộ
                                    sưu tập – Lọc dễ mê </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0">
        <div class="custom-container container">
            <div class="row">
                <div class="col-3">
                    <div class="custom-accordion theme-scrollbar left-box">
                        <div class="left-accordion">
                            <h5>Back </h5><i class="back-button fa-solid fa-xmark"></i>
                        </div>
                        <form method="GET" action="{{ route('client.products.filterSidebar') }}">
                            <div class="accordion" id="accordionPanelsStayOpenExample">

                                <!-- 🔍 Search by name -->
                                <div class="search-box">
                                    <input type="search" name="keyword" placeholder="Tìm sản phẩm..."
                                        value="{{ request('keyword') }}">
                                    <i class="iconsax" data-icon="search-normal-2"></i>
                                </div>

                                <!-- 🏷 Danh mục -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCategory"><span>Danh mục</span></button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="collapseCategory">
                                        <div class="accordion-body">
                                            <ul class="color-variant theme-scrollbar">
                                             @foreach ($categories->where('parent_id', null) as $parent)
    <li>
        <input class="custom-checkbox" id="category{{ $parent->id }}"
            type="checkbox" name="category[]" value="{{ $parent->id }}"
            {{ is_array(request('category')) && in_array($parent->id, request('category')) ? 'checked' : '' }}>
        <label for="category{{ $parent->id }}"><strong>{{ $parent->name }}</strong></label>
    </li>

    @foreach ($categories->where('parent_id', $parent->id) as $child)
        <li style="margin-left: 20px;">
            <input class="custom-checkbox" id="category{{ $child->id }}"
                type="checkbox" name="category[]" value="{{ $child->id }}"
                {{ is_array(request('category')) && in_array($child->id, request('category')) ? 'checked' : '' }}>
            <label for="category{{ $child->id }}">{{ $child->name }}</label>
        </li>
    @endforeach
@endforeach


                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🏢 Thương hiệu -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseBrand">
                                            <span>Thương hiệu</span>
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="collapseBrand">
                                        <div class="accordion-body">
                                            <ul class="color-variant">
                                                @foreach ($brands as $brand)
                                                    <li>
                                                        <input class="custom-checkbox" id="brand{{ $brand->id }}"
                                                            type="checkbox" name="brand[]" value="{{ $brand->id }}" , {{ is_array(request('brand')) && in_array($brand->id, request('brand')) ? 'checked' : '' }}>
                                                        <label for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 💰 Lọc giá -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapsePrice" aria-expanded="true"
                                            aria-controls="collapsePrice">
                                            <span>Khoảng giá</span>
                                        </button>
                                    </h2>
                                    <div id="collapsePrice" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <label for="min_price" class="form-label m-0"
                                                        style="min-width: 60px;">Từ:</label>
                                                    <input type="number" class="form-control" id="min_price"
                                                        name="min_price" min="0" step="1000" placeholder="0"
                                                        value="{{ request('min_price', '') }}">
                                                    <span>đ</span>
                                                </div>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <label for="max_price" class="form-label m-0"
                                                        style="min-width: 60px;">Đến:</label>
                                                    <input type="number" class="form-control" id="max_price"
                                                        name="max_price" min="0" step="1000" placeholder="120000"
                                                        value="{{ request('max_price', '') }}">
                                                    <span>đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- 🎨 Màu sắc -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseColor">
                                            <span>Màu sắc</span>
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="collapseColor">
                                        <div class="accordion-body">
                                            <ul class="color-variant">
                                                @foreach ($colors as $color)
                                                    <li>
                                                        <input type="checkbox" class="custom-checkbox" name="color[]"
                                                            id="color{{ $color->id }}" value="{{ $color->id }}" , {{ is_array(request('color')) && in_array($color->id, request('color')) ? 'checked' : '' }}>
                                                        <label for="color{{ $color->id }}">{{ $color->value }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 👕 Kích cỡ -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSize">
                                            <span>Kích cỡ</span>
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="collapseSize">
                                        <div class="accordion-body">
                                            <ul class="color-variant">
                                                @foreach ($sizes as $size)
                                                    <li>
                                                        <input class="custom-checkbox" id="size{{ $size->id }}" type="checkbox"
                                                            name="size[]" value="{{ $size->id }}" {{ is_array(request('size')) && in_array($size->id, request('size')) ? 'checked' : '' }}> <label
                                                            for="size{{ $size->id }}">{{ $size->value }}</label>
                                                </li> @endforeach

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- 👤 Giới tính -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseGender">
                                            <span>Giới tính</span>
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="collapseGender">
                                        <div class="accordion-body">
                                            <ul class="color-variant">
                                                @foreach ($genders as $gender)
                                                    <li>
                                                        <input class="custom-radio" id="gender_{{ $gender->id }}" type="radio"
                                                            name="gender" value="{{ $gender->id }}" {{ request('gender') == $gender->id ? 'checked' : '' }}>
                                                        <label for="gender_{{ $gender->id }}">{{ $gender->value }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- ✅ Còn hàng / Hết hàng -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseStock">
                                            <span>Tình trạng</span>
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="collapseStock">
                                        <div class="accordion-body">
                                            <ul class="color-variant">
                                                <li>
                                                    <input class="custom-radio" id="inStock" type="radio"
                                                        name="availability" value="in_stock" , {{ request('availability') === 'in_stock' ? 'checked' : '' }}>
                                                    <label for="inStock">Còn hàng</label>
                                                </li>
                                                <li>
                                                    <input class="custom-radio" id="outStock" type="radio"
                                                        name="availability" value="out_of_stock" , {{ request('availability') === 'out_of_stock' ? 'checked' : '' }}>
                                                    <label for="outStock">Hết hàng</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔘 Submit -->
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    <a href="{{ route('client.products.filterSidebar') }}" class="btn btn-clear-filters">
                                        Xóa tất cả bộ lọc
                                    </a>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="sticky">
                        <div class="top-filter-menu">
                            <form method="GET" id="filterForm">
                                <div>
                                    <a class="filter-button btn">
                                        <h6><i class="iconsax" data-icon="filter"></i> Bộ lọc</h6>
                                    </a>
                                    <div class="category-dropdown">
                                        <label for="sort_by">Sắp xếp theo:</label>
                                        <select class="form-select" id="sort_by" name="sort_by"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option value="best_selling" {{ request('sort_by') == 'best_selling' ? 'selected' : '' }}>Bán chạy nhất</option>
                                            <option value="popularity" {{ request('sort_by') == 'popularity' ? 'selected' : '' }}>Phổ biến</option>
                                            <option value="featured" {{ request('sort_by') == 'featured' ? 'selected' : '' }}>
                                                Nổi bật</option>
                                            <option value="alpha_desc" {{ request('sort_by') == 'alpha_desc' ? 'selected' : '' }}>Tên sản phẩm: Z - A</option>
                                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                                            <option value="discount_desc" {{ request('sort_by') == 'discount_desc' ? 'selected' : '' }}>Giảm giá: Nhiều đến ít</option>
                                        </select>
                                    </div>
                                </div>
                            </form>


                            <ul class="filter-option-grid">

                            </ul>
                        </div>
                        <div class="product-tab-content ratio1_3">
                            @if ($products->count())
                                <div class="row-cols-lg-4 row-cols-md-3 row-cols-2 grid-section view-option row g-3 g-xl-4">
                                    @foreach ($products as $product)

                                        <div>
                                            <div class="product-box-3">
                                                <div class="img-wrapper">
                                                    <div class="label-block">
                                                        <a class="label-2 wishlist-icon add-to-wishlist" href="javascript:void(0)"
                                                            data-id="{{ $product->id }}">
                                                            <i class="iconsax {{ in_array($product->id, $wishlistProductIds ?? []) ? 'active' : '' }}"
                                                                data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                                                data-bs-title="Add to Wishlist"></i>
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
                                                    <div class="cart-info-icon">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0">
                                                            <i class="iconsax" data-icon="basket-2" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-title="Add to cart"> </i>
                                                        </a>
                                                        <a href="compare.html" tabindex="0">
                                                            <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                                        </a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                                            tabindex="0">
                                                            <i class="iconsax" data-icon="eye" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-title="Quick View"></i>
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
                                                    <p class="list-per">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 200) }}
                                                    </p>
                                                    <p>
                                                        {{ number_format($product->sale_price ?? $product->base_price, 0, ',', '.') }}₫
                                                        @if($product->sale_price)
                                                            <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                        @endif
                                                    </p>
                                                    <div class="listing-button">
                                                        <a class="btn"
                                                            href="{{ route('client.products.show', $product->slug) }}">Quick
                                                            Shop </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="d-flex justify-content-center align-items-center" style="min-height: 300px;">
                                    <div class="alert alert-warning text-center">
                                        Không có sản phẩm nào phù hợp với bộ lọc bạn đã chọn.
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="pagination-wrap">
                            {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('style')
    <style>
        .price-range-wrapper {
            position: relative;
            padding: 10px 0;
        }

        .form-range {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
            pointer-events: auto;
            position: relative;
            z-index: 2;
            margin-top: 10px;
        }

        .form-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 18px;
            width: 18px;
            background-color: #c28d5e;
            border-radius: 50%;
            border: 2px solid #fff;
            cursor: pointer;
        }

        .form-range::-webkit-slider-runnable-track {
            height: 4px;
            background: #ccc;
            border-radius: 3px;
        }

        .color-variant {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 14px;
            padding-left: 0;
            margin: 0;
            list-style: none;
        }

        .color-variant li {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .color-variant li:hover {
            border-color: #c28d5e;
            background-color: #fdf8f4;
        }

        .color-variant input[type="checkbox"],
        .color-variant input[type="radio"] {
            accent-color: #c28d5e;
            width: 16px;
            height: 16px;
            cursor: pointer;
            margin: 0;
        }

        .color-variant label {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            cursor: pointer;
            color: #333;
        }

        .color-variant input:checked+label {
            color: #c28d5e;
            font-weight: 600;
        }

        .btn-clear-filters {
            background-color: #f8d7a9;
            color: #6a3d00;
            border: none;
            padding: 7px 16px;
            border-radius: 7px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-clear-filters:hover {
            background-color: #f4c57e;
            color: #000;
        }
    </style>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{--
    <script>
        document.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(function (input) {
            input.addEventListener('change', function () {
                this.closest('form').submit(); // Submit lại form khi có thay đổi checkbox
            });
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.add-to-wishlist').forEach(btn => {
                btn.addEventListener('click', function () {
                    const productId = this.dataset.id;
                    const icon = this.querySelector('i');

                    fetch(`/account/wishlist/add/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            const status = data.status || (data.success ? 'ok' : 'error');

                            if (status === 'ok') {
                                icon.classList.add('active');
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    timer: 1200,
                                    showConfirmButton: false
                                });
                            } else if (status === 'exists') {
                                Swal.fire({
                                    icon: 'info',
                                    title: data.message,
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message || 'Có lỗi xảy ra!'
                                });
                            }
                        })
                });
            });
        });
    </script>



@endsection