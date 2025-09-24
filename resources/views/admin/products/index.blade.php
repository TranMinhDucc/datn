@extends('layouts.admin')
@section('title', 'Danh sách sản phẩm')
@section('content')


    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Sản phẩm
                </h1>
                <!--end::Title-->


                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="../../../index.html" class="text-muted text-hover-primary">
                            Trang chủ </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        Sản phẩm </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                   
                    <!--end::Item-->

                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
          
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content  flex-column-fluid ">


        <!--begin::Content container-->
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                            <input type="hidden" name="module" value="products"> <!-- module phải được gửi -->
                            <input id="order-search" type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-250px ps-12" placeholder="Search Order"
                                autocomplete="off" value="{{ request('search') }}" />

                        </div>
                    </div>


                    <!--end::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="w-100 mw-150px">
                            <!--begin::Select2-->
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                                <option></option>
                                <option value="all">All</option>
                                <option value="published">Published</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <!--end::Select2-->
                        </div>
                        <a href="{{ route('admin.products.trash') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-trash"></i> Thùng rác
                        </a>
                        <!--begin::Add product-->
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            Thêm Sản Phẩm
                        </a>
                        <!--end::Add product-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">

                    <!--begin::Table-->
                    <div style="overflow-x: auto;">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 " style="min-width: 1300px;"
                            id="kt_ecommerce_products_table">

                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <!-- Checkbox đầu bảng -->
                                    <th class="w-10px pe-2 text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_ecommerce_products_table .form-check-input"
                                                value="1" />
                                        </div>
                                    </th>

                                    <th class="text-center min-w-60px">ID</th>
                                    <th class="text-center min-w-90px">Ảnh</th>
                                    <th class="min-w-150px">Tên sản phẩm</th>
                                    <th class="text-center min-w-90px">Giá nhập</th>
                                    <th class="text-center min-w-90px">Giá gốc</th>
                                    <th class="text-center min-w-100px">Giá KM</th>
                                    <th class="text-center min-w-90px">Kho hàng</th>
                                    <th class="text-center min-w-130px">Danh mục</th>
                                    <th class="text-center min-w-120px">Đánh giá</th>
                                    <th class="text-center min-w-100px">SP ưu đãi</th>

                                    <th class="text-center min-w-100px">Trạng thái</th>
                                    <th class="text-center min-w-120px">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 align-middle" id="orders_tbody">
                                @foreach ($products as $product)
                                    <tr
                                        data-search="{{ $product->id }} {{ $product->name }} {{ $product->sku }} {{ $product->category->name ?? '' }} {{ $product->import_price }} {{ $product->base_price }} {{ $product->sale_price }}">
                                        {{-- Checkbox --}}
                                        <td class="text-center">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="{{ $product->id }}" />
                                            </div>
                                        </td>

                                        {{-- ID --}}
                                        <td class="text-center">{{ $product->id }}</td>

                                        {{-- Ảnh --}}
                                        <td class="text-center" style="min-width: 150px;">
                                            <a href="{{ route('admin.products.edit', $product->id) }}">
                                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80' }}"
                                                    width="80" height="80" class="rounded shadow-sm" style="object-fit: cover;"
                                                    alt="{{ $product->name }}">
                                            </a>
                                        </td>

                                        {{-- Tên sản phẩm --}}
                                        <td class="align-middle" style="min-width: 120px;">
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">
                                                {{ $product->name }}
                                            </a>
                                        </td>

                                        {{-- Giá nhập --}}
                                        <td class="text-center">
                                            <span
                                                class="fw-bold text-gray-800">{{ number_format($product->import_price, 0, ',', '.') }}
                                                đ</span>
                                        </td>

                                        {{-- Giá gốc --}}
                                        <td class="text-center">
                                            <span
                                                class="fw-bold text-dark">{{ number_format($product->base_price, 0, ',', '.') }}
                                                đ</span>
                                        </td>

                                        {{-- Giá khuyến mãi --}}
                                        <td class="text-center">
                                            <span
                                                class="fw-bold text-success">{{ number_format($product->sale_price ?? 0, 0, ',', '.') }}
                                                đ</span>
                                        </td>

                                        {{-- Kho hàng --}}
                                        <td class="text-center">{{ $product->stock_quantity }}</td>

                                        {{-- Danh mục --}}
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-sm">
                                                @if ($product->category)
                                                    @if ($product->category->deleted_at)
                                                        {{ $product->category->name }} (Đã xoá)
                                                    @else
                                                        {{ $product->category->name }}
                                                    @endif
                                                @else
                                                    Chưa phân loại
                                                @endif

                                                {{-- <span class="badge bg-light-info text-dark">
                                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                                </span> --}}
                                            </a>
                                        </td>

                                       

                                        {{-- Sản phẩm ưu đãi đặc biệt --}}
                                        <td class="text-center">
                                            @if ($product->is_special_offer)
                                                <span class="badge badge-light-primary">Có</span>
                                            @else
                                                <span class="badge badge-light-secondary">Không</span>
                                            @endif
                                        </td>

                                        {{-- Trạng thái --}}
                                        <td class="text-center">
                                            @if ($product->is_active)
                                                <span class="badge badge-light-success">Hiện</span>
                                            @else
                                                <span class="badge badge-light-danger">Ẩn</span>
                                            @endif
                                        </td>

                                        {{-- Thao tác --}}
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                                            class="dropdown-item">
                                                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                            method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa-solid fa-trash me-2 text-danger"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>



                        </table>
                    </div>
                    {!! $products->appends(['search' => request('search')])->links('pagination::bootstrap-5') !!}
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

    </div>
    <!--end::Content wrapper-->

    <script>

        const input = document.querySelector('[data-kt-ecommerce-order-filter="search"]');
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                // Tạo lại URL với từ khóa tìm kiếm và load lại trang
                let searchTerm = input.value;
                let url = new URL(window.location.href);
                url.searchParams.set('search', searchTerm);
                window.location.href = url.toString(); // Điều hướng lại trang với từ khóa tìm kiếm
            }
        });

    </script>

@endsection