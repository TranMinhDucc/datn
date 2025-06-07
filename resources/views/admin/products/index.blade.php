@extends('layouts.admin')
@section('title', 'Danh sách sản phẩm')
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Products
                    </h1>
                    <!--end::Title-->


                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="../../../index.html" class="text-muted text-hover-primary">
                                Home </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            eCommerce </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            Catalog </li>
                        <!--end::Item-->

                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Filter menu-->
                    <div class="m-0">
                        <!--begin::Menu toggle-->
                        <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="fa-solid fa-filter fs-6 text-muted me-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                            Filter
                        </a>
                        <!--end::Menu toggle-->



                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                            id="kt_menu_683db6e8d632c">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Menu separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Menu separator-->


                            <!--begin::Form-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Status:</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" multiple data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="Select option"
                                            data-dropdown-parent="#kt_menu_683db6e8d632c" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="2">In Process</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Member Type:</label>
                                    <!--end::Label-->

                                    <!--begin::Options-->
                                    <div class="d-flex">
                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                            <span class="form-check-label">
                                                Author
                                            </span>
                                        </label>
                                        <!--end::Options-->

                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                            <span class="form-check-label">
                                                Customer
                                            </span>
                                        </label>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Notifications:</label>
                                    <!--end::Label-->

                                    <!--begin::Switch-->
                                    <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="" name="notifications"
                                            checked />
                                        <label class="form-check-label">
                                            Enabled
                                        </label>
                                    </div>
                                    <!--end::Switch-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">Reset</button>

                                    <button type="submit" class="btn btn-sm btn-primary"
                                        data-kt-menu-dismiss="true">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Menu 1-->
                    </div>
                    <!--end::Filter menu-->


                    <!--begin::Secondary button-->
                    <!--end::Secondary button-->

                    <!--begin::Primary button-->
                    <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_create_app">
                        Create </a>
                    <!--end::Primary button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"><span
                                        class="path1"></span><span class="path2"></span></i> <input type="text"
                                    data-kt-ecommerce-product-filter="search"
                                    class="form-control form-control-solid w-250px ps-12" placeholder="Search Product" />
                            </div>
                            <!--end::Search-->
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

                            <!--begin::Add product-->
                            <a href="{{route('admin.products.create')}}" class="btn btn-primary">
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                           
   <thead>
    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
        <th class="w-10px pe-2 text-center">
            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                <input class="form-check-input" type="checkbox" data-kt-check="true"
                    data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
            </div>
        </th>
        <th class="text-center min-w-60px">ID</th>
        <th class="text-center min-w-90px">Ảnh</th>
 <th class="min-w-120px">Tên sản phẩm</th>
<th class="text-center min-w-90px">Mã sản phẩm</th>

        <th class="text-center min-w-130px">Danh mục</th>
        <th class="text-center min-w-120px">Giá</th>
        <th class="text-center min-w-70px">Đã bán</th>
        <th class="text-center min-w-120px">Đánh giá</th>
        <th class="text-center min-w-100px">Trạng thái</th>
        <th class="text-center min-w-120px">Hành động</th>
    </tr>
</thead>
<tbody class="fw-semibold text-gray-600 align-middle">
    @foreach ($products as $product)
        <tr>
            {{-- Checkbox --}}
            <td class="text-center">
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="{{ $product->id }}" />
                </div>
            </td>

            {{-- ID --}}
            <td class="text-center">
                {{ $product->id }}
            </td>

            {{-- Ảnh --}}
        <td class="text-center" style="min-width: 150px;">
    <a href="{{ route('admin.products.edit', $product->id) }}">
        <img src="{{ $product->images ? (Str::startsWith($product->images, ['http://', 'https://']) ? $product->images : asset('storage/' . $product->images)) : 'https://via.placeholder.com/50' }}"
            width="80" height="80"
            class="rounded shadow-sm"
            style="object-fit: cover;"
            alt="{{ $product->name }}">
    </a>
</td>


            {{-- Tên sản phẩm --}}
         {{-- TÊN SẢN PHẨM --}}
<td class="align-middle" style="min-width: 20px;">
    <a href="{{ route('admin.products.edit', $product->id) }}"
       class="text-gray-800 text-hover-primary fs-6 fw-bold">
        {{ $product->name }}
    </a>
</td>


            {{-- Mã sản phẩm --}}
            <td class="text-center">
                <span class="fw-bold text-dark">{{ $product->code }}</span>
            </td>

            {{-- Danh mục --}}
            <td class="text-center">
                <span class="badge bg-light-info text-dark">
                    {{ $product->category->name ?? 'Chưa phân loại' }}
                </span>
            </td>

            {{-- Giá --}}
            <td class="text-center">
                <span class="fw-bold text-dark">{{ number_format($product->price, 0, ',', '.') }} đ</span>
            </td>

            {{-- Đã bán --}}
            <td class="text-center">
                <span class="text-muted">{{ $product->sold }}</span>
            </td>

            {{-- Đánh giá --}}
            <td class="text-center">
                <div class="rating justify-content-center">
                    @php $stars = rand(3, 5); @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="rating-label {{ $i <= $stars ? 'checked' : '' }}">
                            <i class="fa-solid fa-star fs-6 text-warning"></i>
                        </div>
                    @endfor
                </div>
            </td>

            {{-- Trạng thái --}}
            <td class="text-center">
                @if($product->status == 1)
                    <span class="badge badge-light-success">Hiện</span>
                @else
                    <span class="badge badge-light-danger">Ẩn</span>
                @endif
            </td>

            {{-- Hành động --}}
            <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                        data-bs-toggle="dropdown">
                        Hành động <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="{{ route('admin.products.edit', ['product' => $product->id, 'page' => request('page', 1)]) }}" class="dropdown-item">Sửa</a>
</li>
                        <li>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Xóa sản phẩm này?')"
                                    class="dropdown-item text-danger">Xóa</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>

</table>
     {!! $products->appends(request()->query())->links('pagination::bootstrap-5') !!}
                        <!--end::Table-->

                        {{-- <div class="d-flex justify-content-end">
                            {{ $products->links() }}
                        </div> --}}

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
@endsection