@extends('layouts.admin')
@section('title', 'Cập nhật sản phẩm')
@section('content')

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Chỉnh sửa sản phẩm
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
                            <i class="ki-duotone ki-filter fs-6 text-muted me-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                            Filter
                        </a>
                        <!--end::Menu toggle-->



                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                            id="kt_menu_683db6e98b446">
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
                                    <label class="form-label fw-semibold">Trạng Thái:</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" multiple data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="Select option"
                                            data-dropdown-parent="#kt_menu_683db6e98b446" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Hiện </option>
                                            <option value="0">Ẩn</option>
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
                                            <input class="form-check-input" type="checkbox" value="2"
                                                checked="checked" />
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
                <!--begin::Form-->
                <form id="product-form"
                    action="{{ route('admin.products.update', $product->id) }}?page={{ request()->get('page', 1) }}"
                    method="POST" enctype="multipart/form-data" id="kt_ecommerce_add_product_form"
                    class="form d-flex flex-column flex-lg-row"
                    data-kt-redirect="{{ route('admin.products.update', $product->id) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Aside column-->
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                        <!--begin::Thumbnail settings-->
                        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                            <!-- Ảnh đại diện sản phẩm -->
                            @php
                                $imageUrl = $product->image
                                    ? asset('storage/' . $product->image)
                                    : 'https://via.placeholder.com/150';
                            @endphp

                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Ảnh</h2>
                                    </div>
                                </div>

                                <div class="card-body text-center pt-0">
                                    <div class="image-input image-input-outline image-input-placeholder mb-3 {{ $product->image ? '' : 'image-input-empty' }}"
                                        data-kt-image-input="true" style="background-image: url('{{ $imageUrl }}')">
                                        <!-- Preview -->
                                        <div class="image-input-wrapper w-150px h-150px"
                                            style="background-image: url('{{ $imageUrl }}')"></div>

                                        <!-- Upload -->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change">
                                            <i class="ki-duotone ki-pencil fs-7"></i>
                                            <input type="file" name="image" accept=".png, .jpg, .jpeg"
                                                class="form-control mb-2" />
                                        </label>

                                        <!-- Error -->
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <!-- Remove -->
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove">
                                            <i class="ki-duotone ki-cross fs-2"></i>
                                        </span>
                                    </div>

                                    <div class="text-muted fs-7">
                                        **Chọn ảnh đại diện sản phẩm (chỉ hỗ trợ *.png, .jpg, .jpeg).
                                    </div>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Trạng Thái</h2>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="rounded-circle bg-success w-15px h-15px"></div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <select name="is_active" class="form-select mb-2">
                                        <option value="1"
                                            {{ old('is_active', $product->is_active ?? '1') == '1' ? 'selected' : '' }}>Hiện
                                        </option>
                                        <option value="0"
                                            {{ old('is_active', $product->is_active ?? '1') == '0' ? 'selected' : '' }}>Ẩn
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-muted fs-7">Set the product status.</div>
                                </div>
                            </div>

                            <!-- Danh mục -->
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Danh Mục Sản Phẩm</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <label class="form-label">Danh Mục:</label>
                                    <select name="category_id" class="form-select mb-2" data-control="select2">
                                        <option></option>
                                        @foreach ($categories->filter(fn($cat) => is_null($cat->deleted_at)) as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-muted fs-7 mb-7">Add product to a category.</div>
                                </div>
                            </div>

                            <!-- Thương hiệu -->
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Thương hiệu Sản Phẩm</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <label class="form-label">Thương hiệu:</label>
                                    <select name="brand_id" class="form-select mb-2" data-control="select2">
                                        <option></option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-muted fs-7 mb-7">Add product to a brand.</div>
                                </div>
                            </div>
                        </div>


                        <!--end::Thumbnail settings-->
                        <!--begin::Status-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Trạng Thái</h2>
                                </div>
                                <!--end::Card title-->

                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <div class="rounded-circle bg-success w-15px h-15px"
                                        id="kt_ecommerce_add_product_status"></div>
                                </div>
                                <!--begin::Card toolbar-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Select2-->
                                <select name="status" class="form-select mb-2" data-control="select2"
                                    data-hide-search="true" data-placeholder="Select an option"
                                    id="kt_ecommerce_add_product_status_select">
                                    <option></option>
                                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>
                                        Hiện
                                    </option>
                                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Ẩn
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <!--end::Select2-->

                                {{-- <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product status.</div>
                                <!--end::Description--> --}}

                                <!--begin::Datepicker-->
                                <div class="d-none mt-10">
                                    <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Select
                                        publishing date and time</label>
                                    <input class="form-control" id="kt_ecommerce_add_product_status_datepicker"
                                        placeholder="Pick date & time" />
                                </div>
                                <!--end::Datepicker-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Status-->

                        <!--begin::Category & tags-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Danh Mục sản phẩm</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <!--begin::Label-->
                                <label class="form-label">Danh Mục</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <select name="category_id" class="form-select mb-2" data-control="select2"
                                    data-placeholder="Select an option" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <!--end::Select2-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7 mb-7">Add product to a category</div>
                                <!--end::Description-->
                                <!--end::Input group-->

                                <!--begin::Button-->
                                {{-- <a href="add-category.html" class="btn btn-light-primary btn-sm mb-10">
                                    <i class="ki-duotone ki-plus fs-2"></i> Create new category
                                </a> --}}
                                <!--end::Button-->

                                <!--begin::Input group-->
                                <!--begin::Label-->
                                {{-- <label class="form-label d-block">Tags</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input id="kt_ecommerce_add_product_tags" name="kt_ecommerce_add_product_tags"
                                    class="form-control mb-2" value="" />
                                <!--end::Input-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7">Add tags to a product.</div> --}}
                                <!--end::Description-->
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Thương Hiệu</h2>
                                </div>
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <label class="form-label">Thương hiệu</label>

                                <select name="brand_id" class="form-select mb-2" data-control="select2"
                                    data-placeholder="Chọn thương hiệu" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <!--begin::Description-->
                                <div class="text-muted fs-7 mb-7">Gán thương hiệu cho sản phẩm.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card body-->
                        </div>

                        <!--end::Category & tags-->
                        <!--begin::Weekly sales-->
                        {{-- <div class="card card-flush py-4">
                           
                       
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Product Template</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Select store template-->
                                <label for="kt_ecommerce_add_product_store_template" class="form-label">Select a product
                                    template</label>
                                <!--end::Select store template-->

                                <!--begin::Select2-->
                                <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                    data-placeholder="Select an option" id="kt_ecommerce_add_product_store_template">
                                    <option></option>
                                    <option value="default" selected>Default template</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="office">Office stationary</option>
                                    <option value="fashion">Fashion</option>
                                </select>
                                <!--end::Select2-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7">Assign a template from your current theme to define how a
                                    single product is displayed.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card body-->
                        </div> --}}
                        <!--end::Template settings-->
                    </div>
                    <!--end::Aside column-->

                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin:::Tabs-->
                        {{-- <ul
                            class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                    href="#kt_ecommerce_add_product_general">General</a>
                            </li>
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                    href="#kt_ecommerce_add_product_advanced">Advanced</a>
                            </li>
                            <!--end:::Tab item-->

                        </ul> --}}
                        <!--end:::Tabs-->
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general"
                                role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">

                                    <!--begin::General options-->
                                    <div class="card card-flush py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Tổng Quan</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <!-- Tên sản phẩm -->
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Tên Sản Phẩm</label>
                                                <input type="text" id="product-name" name="name"
                                                    class="form-control mb-2" placeholder="Nhập tên sản phẩm"
                                                    value="{{ old('name', $product->name ?? '') }}" />
                                                @error('name')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Slug (tự động tạo)</label>
                                                <input type="text" name="slug" id="product-slug"
                                                    class="form-control mb-2" placeholder="slug-tu-dong" readonly
                                                    value="{{ old('slug', $product->slug ?? '') }}" />
                                                @error('slug')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <!-- Mã sản phẩm -->
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Mã Sản Phẩm</label>
                                                <input type="text" name="code" class="form-control mb-2"
                                                    placeholder="Mã Sản Phẩm"
                                                    value="{{ old('code', $product->code ?? '') }}" />
                                                @error('code')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Số lượng mua tối thiểu / tối đa -->
                                            <div class="row mb-10">
                                                <div class="col-md-6">
                                                    <label for="min_purchase_quantity">Mua tối thiểu:</label>
                                                    <input type="number" name="min_purchase_quantity"
                                                        id="min_purchase_quantity"
                                                        class="form-control @error('min_purchase_quantity') is-invalid @enderror"
                                                        value="{{ old('min_purchase_quantity', $product->min_purchase_quantity ?? 1) }}"
                                                        min="1">
                                                    @error('min_purchase_quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="max_purchase_quantity">Mua tối đa:</label>
                                                    <input type="number" name="max_purchase_quantity"
                                                        id="max_purchase_quantity"
                                                        class="form-control @error('max_purchase_quantity') is-invalid @enderror"
                                                        value="{{ old('max_purchase_quantity', $product->max_purchase_quantity ?? 1000000) }}"
                                                        min="1">
                                                    @error('max_purchase_quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Giá và tồn kho -->
                                            <div class="row mb-10">
                                                <div class="col-md-6">
                                                    <label class="required form-label">Giá nhập</label>
                                                    <input type="number" name="import_price" class="form-control"
                                                        value="{{ old('import_price', $product->import_price ?? 0) }}"
                                                        min="0" step="0.01">
                                                    @error('import_price')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="required form-label">Giá bán</label>
                                                    <input type="number" name="base_price" class="form-control"
                                                        value="{{ old('base_price', $product->base_price ?? 0) }}"
                                                        min="0" step="0.01">
                                                    @error('base_price')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-10">
                                                <div class="col-md-6">
                                                    <label class="form-label">Giá khuyến mãi</label>
                                                    <input type="number" name="sale_price" class="form-control"
                                                        value="{{ old('sale_price', $product->sale_price ?? 0) }}"
                                                        min="0" step="0.01">
                                                    @error('sale_price')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Tồn kho</label>

                                                    {{-- ⛔ KHÔNG dùng disabled, chỉ dùng readonly --}}
                                                    <input type="number" id="stock_quantity" name="stock_quantity"
                                                        class="form-control"
                                                        value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}"
                                                        readonly>

                                                    {{-- ✅ Luôn gửi giá trị về server --}}
                                                    <input type="hidden" id="hidden_stock_quantity"
                                                        name="stock_quantity"
                                                        value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}">

                                                    <small class="text-muted">Tự động tính từ các biến thể (nếu
                                                        có).</small>

                                                    @error('stock_quantity')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="row mb-10">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Thời gian bắt đầu</label>
                                                        <input type="datetime-local" name="starts_at"
                                                            class="form-control" value="{{ $product->starts_at }}">
                                                        @error('starts_at')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Thời gian kết thúc</label>
                                                        <input type="datetime-local" name="ends_at" class="form-control"
                                                            value="{{ $product->ends_at }}">
                                                        @error('ends_at')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">Phần trăm / giá tiền</label>
                                                        <input type="number" name="sale_times" class="form-control"
                                                            value="{{ $product->sale_times }}">
                                                        @error('sale_times')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>




                                            </div>

                                            <!-- Mô tả ngắn -->

                                            <!-- Mô tả ngắn -->
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Mô Tả chi Tiết Sản phẩm</label>
                                                <textarea id="description" name="description" class="form-control" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror



                                            </div>
                                            {{-- Mô tả chi tiêt --}}
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Mô tả chi tiết sản phẩm</label>
                                                <textarea id="detailed_description" name="detailed_description" class="form-control" rows="8">{{ old('detailed_description', $product->detailed_description ?? '') }}</textarea>
                                                @error('detailed_description')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div id="product-details-container">
                                                <h5 class="mb-2">Chi tiết sản phẩm</h5>

                                                @php
                                                    $grouped = $details->groupBy('group_name');
                                                    $groupIndex = 0;
                                                @endphp

                                                @if ($grouped->isEmpty())
                                                    {{-- Trường hợp không có dữ liệu, hiển thị một nhóm trống --}}
                                                    <div class="group-wrapper mb-4" data-group-index="0">
                                                        <div class="mb-2">
                                                            <input type="text" name="details[0][group_name]"
                                                                class="form-control" placeholder="Nhóm">
                                                        </div>
                                                        <div class="row mb-2 sub-item">
                                                            <div class="col-md-5">
                                                                <input type="text" name="details[0][items][0][label]"
                                                                    class="form-control" placeholder="Nhãn">
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" name="details[0][items][0][value]"
                                                                    class="form-control" placeholder="Giá trị">
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-sub">X</button>
                                                            </div>
                                                        </div>
                                                        <button type="button"
                                                            class="btn btn-success btn-sm add-sub-item">
                                                            <i class="fas fa-plus-circle me-1"></i> Thêm nhãn
                                                        </button>

                                                    </div>
                                                @else
                                                    {{-- Lặp qua từng nhóm --}}
                                                    @foreach ($grouped as $groupName => $items)
                                                        <div class="group-wrapper mb-4"
                                                            data-group-index="{{ $groupIndex }}">
                                                            <div class="mb-2">
                                                                <input type="text"
                                                                    name="details[{{ $groupIndex }}][group_name]"
                                                                    class="form-control" value="{{ $groupName }}">
                                                            </div>

                                                            @foreach ($items as $itemIndex => $item)
                                                                <div class="row mb-2 sub-item">
                                                                    <div class="col-md-5">
                                                                        <input type="text"
                                                                            name="details[{{ $groupIndex }}][items][{{ $itemIndex }}][label]"
                                                                            class="form-control"
                                                                            value="{{ $item->label }}"
                                                                            placeholder="Nhãn">
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text"
                                                                            name="details[{{ $groupIndex }}][items][{{ $itemIndex }}][value]"
                                                                            class="form-control"
                                                                            value="{{ $item->value }}"
                                                                            placeholder="Giá trị">
                                                                    </div>
                                                                    <div class="col-md-2 d-flex align-items-center">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm remove-sub">X</button>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                            <button type="button"
                                                                class="btn btn-light btn-sm add-sub-item">+ Thêm
                                                                nhãn/giá trị</button>
                                                        </div>
                                                        @php $groupIndex++; @endphp
                                                    @endforeach
                                                @endif
                                            </div>

                                            {{-- Nút thêm nhóm mới --}}
                                            <button type="button" id="add-group" class="btn btn-light-primary">+ Thêm
                                                nhóm
                                                mới</button>

                                            <div class="mt-3">
                                                <label class="form-label">Ảnh phụ</label>
                                                <input type="file" id="image-input" name="images[]" multiple
                                                    accept=".png, .jpg, .jpeg" class="form-control mb-3" />

                                                <!-- Ảnh phụ đã lưu -->
                                                <div class="d-flex flex-wrap gap-4">
                                                    @foreach ($product->images as $img)
                                                        <div class="position-relative rounded border p-1 shadow-sm"
                                                            style="width: 120px; height: 120px;"
                                                            id="image_{{ $img->id }}">
                                                            <img src="{{ asset('storage/' . $img->image_url) }}"
                                                                class="rounded w-100 h-100 object-fit-cover"
                                                                alt="Ảnh phụ">

                                                            {{-- Nút xoá ảnh --}}
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                                onclick="removeOldImage({{ $img->id }})"
                                                                title="Xoá ảnh này">
                                                                &times;
                                                            </button>

                                                            {{-- Thêm input hidden nếu bị xoá --}}
                                                            <input type="hidden" name="existing_image_ids[]"
                                                                value="{{ $img->id }}">
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- Hidden container để thêm ảnh cần xoá --}}
                                                <div id="deleted-images-container"></div>

                                                <!-- Preview ảnh mới chọn -->
                                                <div id="image-preview-container" class="d-flex flex-wrap gap-4 mt-4">
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <!-- Nhúng CKEditor bản full giống phần thêm -->
                                    <script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
                                    <script>
                                        CKEDITOR.replace('description', {
                                            height: 100,
                                            toolbarCanCollapse: true
                                        });
                                        CKEDITOR.replace('detailed_description', {
                                            height: 300,
                                            toolbarCanCollapse: true
                                        });
                                    </script>



                                    <!--end::Editor-->

                                    {{-- <!--begin::Description-->
                                    <div class="text-muted fs-7">Set a description to the product for better
                                        visibility.</div>
                                    <!--end::Description-->
                                </div> --}}
                                    <!--end::Input group-->
                                </div>
                                <!--end::Card header-->
                            </div>
                            <!--end::General options-->
                            <!--begin::Media-->
                            {{-- <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Media</h2>
                                </div>
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="fv-row mb-2">
                                    <!--begin::Dropzone-->
                                    <div class="dropzone" id="kt_ecommerce_add_product_media">
                                        <!--begin::Message-->
                                        <div class="dz-message needsclick">
                                            <!--begin::Icon-->
                                            <i class="ki-duotone ki-file-up text-primary fs-3x"><span
                                                    class="path1"></span><span class="path2"></span></i>
                                            <!--end::Icon-->
                                            <!--begin::Info-->
                                            <div class="ms-4">
                                                <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or
                                                    click to upload.</h3>
                                                <span class="fs-7 fw-semibold text-gray-500">Upload up to 10
                                                    files</span>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                    </div>
                                    <!--end::Dropzone-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product media gallery.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card header-->
                        </div> --}}
                            <!--end::Media-->

                            <!--begin::Pricing-->
                            <!-- Phân Loại & Biến Thể -->
                            <div class="card card-flush py-4 mb-5">
                                <div class="card-header">
                                    <h2 class="card-title">Phân Loại & Biến Thể</h2>
                                </div>
                                <div class="card-body">
                                    <div id="pf_attribute_groups_wrapper"></div>
                                    <button type="button" class="btn btn-light-primary" id="pf_add_attribute_group">
                                        <i class="bi bi-plus-circle"></i> Thêm phân loại
                                    </button>
                                </div>
                            </div>

                            <!-- Biến Thể -->

                            <div id="pf_variant_section" class="card card-flush py-4" style="display: none">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title fw-bold fs-4 text-dark">🧩 Biến thể sản phẩm</h3>
                                    <span class="badge bg-primary-soft text-primary">Tự động sinh theo phân loại</span>
                                </div>

                                <div id="pf_apply_all_wrapper" class="card-body pb-0 mb-4" style="display: none;">
                                    <div class="row g-4 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Áp dụng giá</label>
                                            <input type="number" class="form-control form-control-solid"
                                                id="pf_apply_price" placeholder="Nhập giá">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Áp dụng tồn kho</label>
                                            <input type="number" class="form-control form-control-solid"
                                                id="pf_apply_qty" placeholder="Số lượng tồn">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Áp dụng SKU</label>
                                            <input type="text" class="form-control form-control-solid"
                                                id="pf_apply_sku" placeholder="SKU chung">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Cân nặng (g)</label>
                                            <input type="number" class="form-control form-control-solid"
                                                id="pf_apply_weight" placeholder="Cân nặng">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Dài (cm)</label>
                                            <input type="number" class="form-control form-control-solid"
                                                id="pf_apply_length" placeholder="Dài">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Rộng (cm)</label>
                                            <input type="number" class="form-control form-control-solid"
                                                id="pf_apply_width" placeholder="Rộng">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Cao (cm)</label>
                                            <input type="number" class="form-control form-control-solid"
                                                id="pf_apply_height" placeholder="Cao">
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <button type="button" class="btn btn-danger fw-bold mt-2 w-100"
                                                onclick="pfApplyToAll()">
                                                <i class="bi bi-check2-circle fs-5 me-1"></i> Áp dụng
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-bordered table-rounded border-gray-300">
                                            <thead class="fw-bold text-gray-700 bg-light">
                                                <tr>
                                                    <th>Thuộc tính</th>
                                                    <th>Giá bán</th>
                                                    <th>Số lượng</th>
                                                    <th>SKU</th>
                                                    <th>Cân nặng (g)</th>
                                                    <th>Dài (cm)</th>
                                                    <th>Rộng (cm)</th>
                                                    <th>Cao (cm)</th>
                                                    <th class="text-center">Xóa</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pf_variant_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>




                            <!-- Nút thêm biến thể -->



                            <!--end::Product Variants-->


                            <!--end::Input group-->

                            {{-- <!--begin::Input group-->
                       
                     

                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Pricing-->
            </div>
        </div>
      
        {{-- <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
      
    </div> --}}

                        </div>
                        <div class="d-flex justify-content-end gap-3 mt-5" style="padding-right: 2rem;">
                            <!--begin::Button Cancel-->
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                                Cancel
                            </a>
                            <!--end::Button Cancel-->

                            <!--begin::Button Save-->
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                                <span class="indicator-progress">
                                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <!--end::Button Save-->
                        </div>
                        <!--end::Action buttons-->


                    </div>
                    <!--end::Main column-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>


    <script>
        window.oldAttributeGroups = @json($attributeGroups);
        window.oldVariants = @json($productVariants);
        window.allAttributeValues = @json($attributeValues);
    </script>

    <script>
        ClassicEditor
            .create(document.querySelector('#detailed_description'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <!-- Nhúng CKEditor bản full giống phần thêm -->
    <script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            height: 100,
            toolbarCanCollapse: true
        });
    </script>


    <script>
        function slugify(str) {
            return str.toString().toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Bỏ dấu tiếng Việt
                .replace(/[^a-z0-9 -]/g, '') // Loại bỏ ký tự đặc biệt
                .replace(/\s+/g, '-') // Thay khoảng trắng bằng -
                .replace(/-+/g, '-') // Loại bỏ dấu - thừa
                .replace(/^-+|-+$/g, ''); // Loại bỏ dấu - đầu/cuối
        }

        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('product-name');
            const slugInput = document.getElementById('product-slug');

            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    slugInput.value = slugify(nameInput.value);
                });
            }
        });

        document.getElementById('image-input').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const previewContainer = document.getElementById('image-preview-container');

            previewContainer.innerHTML = ''; // Clear ảnh cũ khi reselect
            const dt = new DataTransfer(); // Để update input file

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative';
                    wrapper.style.width = '100px';

                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'rounded border';
                    img.style.height = '100px';
                    img.style.width = '100%';
                    img.style.objectFit = 'cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function() {
                        wrapper.remove();

                        // Remove file khỏi input
                        const newDt = new DataTransfer();
                        Array.from(document.getElementById('image-input').files).forEach((f, i) => {
                            if (i !== index) newDt.items.add(f);
                        });
                        document.getElementById('image-input').files = newDt.files;
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
                dt.items.add(file);
            });

            // Cập nhật lại input
            document.getElementById('image-input').files = dt.files;
        });

        function removeOldImage(imageId) {
            const imageCard = document.getElementById('image_' + imageId);
            if (imageCard) imageCard.remove();

            const container = document.getElementById('deleted-images-container');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_image_ids[]';
            input.value = imageId;
            container.appendChild(input);
        }



        const PF_ATTRIBUTE_SUGGESTIONS = {
            "Màu sắc": ["Đỏ", "Cam", "Vàng", "Xanh lá", "Xanh dương", "Tím", "Hồng"],
            "Size": ["XS", "S", "M", "L", "XL", "XXL"],
            "Giới tính": ["Nam", "Nữ", "Unisex"]
        };

        let pfAttributeIndex = 0;
        let pfAttributeGroups = {};

        function cartesian(arrays) {
            return arrays.reduce((a, b) => a.flatMap(d => b.map(e => d.concat(e))), [
                []
            ]);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const oldGroups = window.oldAttributeGroups || [];
            const oldVariants = window.oldVariants || [];

            oldGroups.forEach(group => {
                const groupId = `pf_group_${pfAttributeIndex}`;
                pfAttributeGroups[groupId] = {
                    name: group.name,
                    values: group.values || []
                };
                pfRenderAttributeGroup(groupId, group.name, group.values);
                pfAttributeIndex++;
            });

            if (oldVariants.length > 0) {
                const tbody = document.getElementById("pf_variant_list");
                tbody.innerHTML = "";

                oldVariants.forEach((variant, i) => {
                    const row = document.createElement("tr");

                    const tdAttr = document.createElement("td");
                    Object.entries(variant.attribute_map).forEach(([name, value]) => {
                        const div = document.createElement("div");
                        div.textContent = `${name}: ${value}`;
                        const hidden = document.createElement("input");
                        hidden.type = "hidden";
                        hidden.name = `variants[${i}][attributes][${name}]`;
                        hidden.value = value;
                        tdAttr.appendChild(div);
                        tdAttr.appendChild(hidden);
                    });

                    const tdPrice = document.createElement("td");
                    tdPrice.innerHTML =
                        `<input type="number" name="variants[${i}][price]" class="form-control" value="${variant.price}">`;

                    const tdQty = document.createElement("td");
                    tdQty.innerHTML =
                        `<input type="number" name="variants[${i}][quantity]" class="form-control" value="${variant.quantity}">`;

                    const tdSku = document.createElement("td");
                    tdSku.innerHTML =
                        `<input type="text" name="variants[${i}][sku]" class="form-control" value="${variant.sku}">`;
                    const tdWeight = document.createElement("td");
                    tdWeight.innerHTML =
                        `<input type="number" name="variants[${i}][weight]" class="form-control" value="${variant.weight ?? ''}">`;

                    const tdLength = document.createElement("td");
                    tdLength.innerHTML =
                        `<input type="number" name="variants[${i}][length]" class="form-control" value="${variant.length ?? ''}">`;

                    const tdWidth = document.createElement("td");
                    tdWidth.innerHTML =
                        `<input type="number" name="variants[${i}][width]" class="form-control" value="${variant.width ?? ''}">`;

                    const tdHeight = document.createElement("td");
                    tdHeight.innerHTML =
                        `<input type="number" name="variants[${i}][height]" class="form-control" value="${variant.height ?? ''}">`;

                    const tdDelete = document.createElement("td");
                    tdDelete.innerHTML =
                        `
                                                                                                                                                                                                                                    <button type="button" class="btn btn-icon btn-bg-light btn-sm btn-hover-danger" onclick="removeVariantRow(this)">
                                                                                                                                                                                                                                        <i class="bi bi-trash text-danger fs-5"></i>
                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                    `;

                    row.appendChild(tdAttr);
                    row.appendChild(tdPrice);
                    row.appendChild(tdQty);
                    row.appendChild(tdSku);
                    row.appendChild(tdWeight);
                    row.appendChild(tdLength);
                    row.appendChild(tdWidth);
                    row.appendChild(tdHeight);
                    row.appendChild(tdDelete);

                    tbody.appendChild(row);
                });

                document.getElementById("pf_variant_section").style.display = "block";
                calculateTotalStock();

            } else {
                pfRenderVariants();
            }

            document.getElementById("pf_add_attribute_group")?.addEventListener("click", pfAddAttributeGroup);
            document.addEventListener('input', function(e) {
                if (e.target && e.target.name && e.target.name.includes('[quantity]')) {
                    calculateTotalStock();
                }
            });
        });

        function pfAddAttributeGroup() {
            const groupId = `pf_group_${pfAttributeIndex}`;
            pfAttributeGroups[groupId] = {
                name: '',
                values: []
            };
            pfRenderAttributeGroup(groupId);
            pfAttributeIndex++;
        }

        function pfRenderAttributeGroup(groupId, selectedName = '', selectedValues = []) {
            const wrapper = document.getElementById("pf_attribute_groups_wrapper");
            const div = document.createElement("div");
            div.className = "bg-light rounded p-4 border position-relative mb-4";
            div.id = groupId;

            div.innerHTML =
                `
                                                                                                                                                                                                                                            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" onclick="pfRemoveAttributeGroup('${groupId}')"></button>
                                                                                                                                                                                                                                            <div class="mb-3 d-flex align-items-center gap-3">
                                                                                                                                                                                                                                                <label class="form-label fw-bold mb-0" style="min-width: 90px;">Phân loại</label>
                                                                                                                                                                                                                                                <input type="text" class="form-control w-50 pf-attribute-name-input" />
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                            <div class="mb-1">
                                                                                                                                                                                                                                                <label class="form-label fw-bold">Tuỳ chọn</label>
                                                                                                                                                                                                                                                <div id="${groupId}_tags" class="pf-attribute-option-container d-flex flex-wrap gap-2 align-items-center"></div>
                                                                                                                                                                                                                                                <div class="form-text text-muted">Nhập và nhấn Enter hoặc chọn từ gợi ý</div>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                            <input type="hidden" name="attributeGroups[]" value="${selectedName}">
                                                                                                                                                                                                                                        `;

            wrapper.appendChild(div);

            const input = div.querySelector(".pf-attribute-name-input");

            const usedNames = Object.values(pfAttributeGroups)
                .map(g => g.name)
                .filter(name => name && name !== selectedName); // Tránh tự disable chính mình khi edit

            const options = Object.keys(PF_ATTRIBUTE_SUGGESTIONS).map(name => ({
                value: name,
                text: name,
                disabled: usedNames.includes(name)
            }));

            const ts = new TomSelect(input, {
                create: true,
                maxItems: 1,
                mode: "input",
                options: options,
                placeholder: "Chọn hoặc nhập phân loại",
                onInitialize() {
                    if (selectedName) {
                        this.addOption({
                            value: selectedName,
                            text: selectedName
                        });
                        this.setValue(selectedName);
                    }
                },
                onChange: val => {
                    pfAttributeGroups[groupId].name = val;
                    div.querySelector('input[type=hidden]').value = val;
                    pfRenderTags(groupId);
                    pfRenderVariants();
                }
            });

            pfAttributeGroups[groupId].values = selectedValues;
            pfRenderTags(groupId);
        }

        function pfRenderTags(groupId) {
            const container = document.getElementById(`${groupId}_tags`);
            container.innerHTML = "";

            const selected = pfAttributeGroups[groupId].values || [];
            const list = document.createElement("div");
            list.className = "d-flex flex-wrap gap-2 align-items-center";

            selected.forEach(val => {
                const tag = document.createElement("div");
                tag.className = "d-inline-flex align-items-center bg-white border rounded p-2";

                const input = document.createElement("input");
                input.type = "text";
                input.className = "form-control form-control-sm border-0 p-0";
                input.style.background = "transparent";
                input.value = val;
                input.readOnly = true;

                const trash = document.createElement("i");
                trash.className = "bi bi-trash text-danger ms-2 cursor-pointer";
                trash.onclick = () => pfRemoveTag(groupId, val);

                tag.appendChild(input);
                tag.appendChild(trash);
                list.appendChild(tag);
            });

            container.appendChild(list);

            const input = document.createElement("input");
            container.appendChild(input);

            const ts = new TomSelect(input, {
                create: true,
                maxItems: 1,
                persist: false,
                options: selected.map(val => ({
                    value: val,
                    text: val
                })),
                onItemAdd(value) {
                    if (!pfAttributeGroups[groupId].values.includes(value)) {
                        pfAttributeGroups[groupId].values.push(value);
                        pfRenderTags(groupId);
                        pfRenderVariants();
                    }
                    ts.clear();
                },
                onBlur() {
                    const val = ts.getValue().trim();
                    if (val && !pfAttributeGroups[groupId].values.includes(val)) {
                        ts.addOption({
                            value: val,
                            text: val
                        });
                        ts.addItem(val);
                    } else {
                        ts.clear();
                    }
                }
            });

            const suggest = PF_ATTRIBUTE_SUGGESTIONS[pfAttributeGroups[groupId].name] || window.allAttributeValues?.[
                pfAttributeGroups[groupId].name
            ] || [];
            if (suggest.length > 0) {
                ts.addOptions(suggest.map(val => ({
                    value: val,
                    text: val
                })));
            }

            // Remove old hidden input nếu tồn tại
            const oldHiddenInput = container.querySelector(`input[name="attributeValues[${groupId}]"]`);
            if (oldHiddenInput) oldHiddenInput.remove();

            // Tạo hidden input lưu các giá trị tuỳ chọn của phân loại
            const hiddenValues = document.createElement("input");
            hiddenValues.type = "hidden";
            hiddenValues.name = `attributeValues[${groupId}]`; // Key này dùng trong Laravel
            hiddenValues.value = JSON.stringify(pfAttributeGroups[groupId].values || []);
            container.appendChild(hiddenValues);

        }


        function pfRenderVariants() {
            const tbody = document.getElementById("pf_variant_list");
            if (!tbody) return;

            // 1. Lưu lại dữ liệu cũ
            const oldData = {};
            document.querySelectorAll("#pf_variant_list tr").forEach((row) => {
                const attrs = Array.from(row.querySelectorAll("input[type=hidden]"))
                    .map(input => `${input.name}:${input.value}`)
                    .sort()
                    .join("|");
                const price = row.querySelector('input[name$="[price]"]')?.value || '0';
                const quantity = row.querySelector('input[name$="[quantity]"]')?.value || '0';
                const sku = row.querySelector('input[name$="[sku]"]')?.value || '';
                oldData[attrs] = {
                    price: row.querySelector('input[name$="[price]"]')?.value || '0',
                    quantity: row.querySelector('input[name$="[quantity]"]')?.value || '0',
                    sku: row.querySelector('input[name$="[sku]"]')?.value || '',
                    weight: row.querySelector('input[name$="[weight]"]')?.value || '',
                    length: row.querySelector('input[name$="[length]"]')?.value || '',
                    width: row.querySelector('input[name$="[width]"]')?.value || '',
                    height: row.querySelector('input[name$="[height]"]')?.value || '',
                };
            });

            tbody.innerHTML = "";

            const keys = Object.keys(pfAttributeGroups).filter(id => pfAttributeGroups[id].name && pfAttributeGroups[id]
                .values.length);

            const stockInput = document.getElementById("stock_quantity");
            const hiddenStockInput = document.getElementById("hidden_stock_quantity");

            if (keys.length === 0) {
                // ✅ Nếu không có biến thể → mở lại tồn kho, không reset giá trị
                if (stockInput) {
                    stockInput.readOnly = false;
                    stockInput.disabled = false;
                }

                if (hiddenStockInput) {
                    hiddenStockInput.value = stockInput?.value || '';
                }

                // Ẩn bảng biến thể
                document.getElementById("pf_variant_section").style.display = "none";
                document.getElementById("pf_apply_all_wrapper").style.display = "none";

                return;
            }

            const combinations = cartesian(keys.map(id => pfAttributeGroups[id].values.map(val => ({
                groupName: pfAttributeGroups[id].name,
                value: val
            }))));

            combinations.forEach((combo, i) => {
                const row = document.createElement("tr");

                const tdAttr = document.createElement("td");
                const attrSignature = [];

                combo.forEach(opt => {
                    const text = document.createElement("div");
                    text.textContent = `${opt.groupName}: ${opt.value}`;

                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `variants[${i}][attributes][${opt.groupName}]`;
                    hidden.value = opt.value;

                    attrSignature.push(`${hidden.name}:${hidden.value}`);

                    tdAttr.appendChild(text);
                    tdAttr.appendChild(hidden);
                });

                const signatureKey = attrSignature.sort().join("|");
                const existing = oldData[signatureKey] || {
                    price: '0',
                    quantity: '0',
                    sku: ''
                };

                const tdPrice = document.createElement("td");
                tdPrice.innerHTML =
                    `
                                                                                                                                                                                                                                                <input type="number"
                                                                                                                                                                                                                                                       name="variants[${i}][price]"
                                                                                                                                                                                                                                                       class="form-control"
                                                                                                                                                                                                                                                       value="${existing.price !== '0' ? existing.price : ''}"
                                                                                                                                                                                                                                                       min="1"
                                                                                                                                                                                                                                                       required>
                                                                                                                                                                                                                                            `;

                const tdQty = document.createElement("td");
                tdQty.innerHTML =
                    `
                                                                                                                                                                                                                                                <input type="number"
                                                                                                                                                                                                                                                       name="variants[${i}][quantity]"
                                                                                                                                                                                                                                                       class="form-control"
                                                                                                                                                                                                                                                       value="${existing.quantity !== '0' ? existing.quantity : ''}"
                                                                                                                                                                                                                                                       min="1"
                                                                                                                                                                                                                                                       required>
                                                                                                                                                                                                                                            `;

                const tdSku = document.createElement("td");
                tdSku.innerHTML =
                    `<input type="text" name="variants[${i}][sku]" class="form-control" value="${existing.sku}">`;

                const tdWeight = document.createElement("td");
                tdWeight.innerHTML =
                    `<input type="number" name="variants[${i}][weight]" class="form-control" value="${existing.weight ?? ''}" placeholder="gram">`;

                const tdLength = document.createElement("td");
                tdLength.innerHTML =
                    `<input type="number" name="variants[${i}][length]" class="form-control" value="${existing.length ?? ''}" placeholder="cm">`;

                const tdWidth = document.createElement("td");
                tdWidth.innerHTML =
                    `<input type="number" name="variants[${i}][width]" class="form-control" value="${existing.width ?? ''}" placeholder="cm">`;

                const tdHeight = document.createElement("td");
                tdHeight.innerHTML =
                    `<input type="number" name="variants[${i}][height]" class="form-control" value="${existing.height ?? ''}" placeholder="cm">`;

                const tdDelete = document.createElement("td");
                tdDelete.innerHTML =
                    `
                    <button type="button" class="btn btn-icon btn-bg-light btn-sm btn-hover-danger" onclick="removeVariantRow(this)">
                   <i class="bi bi-trash text-danger fs-5"></i>
                   </button>
                                                                                                                                                                                                                                            `;
                row.appendChild(tdAttr);
                row.appendChild(tdPrice);
                row.appendChild(tdQty);
                row.appendChild(tdSku);
                row.appendChild(tdWeight);
                row.appendChild(tdLength);
                row.appendChild(tdWidth);
                row.appendChild(tdHeight);
                row.appendChild(tdDelete);


                tbody.appendChild(row);
            });

            // ✅ Có biến thể → khóa tồn kho và cập nhật tổng số lượng
            if (stockInput) {
                stockInput.readOnly = true;
                stockInput.disabled = true;
            }

            document.getElementById("pf_variant_section").style.display = "block";
            document.getElementById("pf_apply_all_wrapper").style.display = "block";

            calculateTotalStock();
        }



        function pfRemoveTag(groupId, val) {
            pfAttributeGroups[groupId].values = pfAttributeGroups[groupId].values.filter(v => v !== val);
            pfRenderTags(groupId);
            pfRenderVariants();
        }

        function pfRemoveAttributeGroup(id) {
            delete pfAttributeGroups[id];
            document.getElementById(id)?.remove();
            pfRenderVariants();
            delete pfAttributeGroups[id];
            document.getElementById(id)?.remove();
            pfRenderVariants();

            const keys = Object.keys(pfAttributeGroups).filter(id => pfAttributeGroups[id].name && pfAttributeGroups[id]
                .values.length);
            if (keys.length === 0) {
                document.getElementById("pf_apply_all_wrapper").style.display = "none";
                document.getElementById("pf_variant_section").style.display = "none";
            }
        }

        function pfApplyToAll() {
            const price = document.getElementById('pf_apply_price').value;
            const qty = document.getElementById('pf_apply_qty').value;
            const sku = document.getElementById('pf_apply_sku').value;
            const weight = document.getElementById('pf_apply_weight').value;
            const length = document.getElementById('pf_apply_length').value;
            const width = document.getElementById('pf_apply_width').value;
            const height = document.getElementById('pf_apply_height').value;

            document.querySelectorAll("#pf_variant_list tr").forEach((row, i) => {
                if (price !== '') row.querySelector(`[name="variants[${i}][price]"]`).value = price;
                if (qty !== '') row.querySelector(`[name="variants[${i}][quantity]"]`).value = qty;
                if (sku !== '') row.querySelector(`[name="variants[${i}][sku]"]`).value = sku;
                if (weight !== '') row.querySelector(`[name="variants[${i}][weight]"]`).value = weight;
                if (length !== '') row.querySelector(`[name="variants[${i}][length]"]`).value = length;
                if (width !== '') row.querySelector(`[name="variants[${i}][width]"]`).value = width;
                if (height !== '') row.querySelector(`[name="variants[${i}][height]"]`).value = height;
            });

            calculateTotalStock(); // Cập nhật lại tổng tồn kho
        }


        function calculateTotalStock() {
            const stockInput = document.getElementById("stock_quantity");
            const hiddenStockInput = document.getElementById("hidden_stock_quantity");

            const qtyInputs = document.querySelectorAll('input[name^="variants"][name$="[quantity]"]');

            if (!stockInput) return;

            if (qtyInputs.length === 0) {
                // Không có biến thể → cho phép nhập tay
                stockInput.readOnly = false;
                stockInput.disabled = false;
                if (hiddenStockInput) hiddenStockInput.value = stockInput.value || '';
                return;
            }

            let total = 0;
            qtyInputs.forEach(input => {
                const val = parseInt(input.value);
                if (!isNaN(val)) {
                    total += val;
                }
            });

            // Gán tồn kho tổng vào cả input và hidden
            stockInput.value = total;
            stockInput.readOnly = true;
            stockInput.disabled = true;

            if (hiddenStockInput) {
                hiddenStockInput.value = total;
            }
        }


        function removeVariantRow(button) {
            const row = button.closest("tr");
            if (row) {
                row.remove();
                calculateTotalStock(); // cập nhật tồn kho lại sau xoá
            }
        }



        // Edit chi tiết sản phẩm 
        document.addEventListener('DOMContentLoaded', function() {
            let groupIndex = {{ isset($grouped) ? $grouped->count() : 1 }};

            // Thêm nhóm mới
            document.getElementById('add-group').addEventListener('click', function() {
                const container = document.getElementById('product-details-container');
                const groupHTML = `
                                                                <div class="group-wrapper mb-4" data-group-index="${groupIndex}">
                                                                    <div class="mb-2">
                                                                        <input type="text" name="details[${groupIndex}][group_name]" class="form-control" placeholder="Nhóm">
                                                                    </div>
                                                                    <div class="row mb-2 sub-item">
                                                                        <div class="col-md-5">
                                                                            <input type="text" name="details[${groupIndex}][items][0][label]" class="form-control" placeholder="Nhãn">
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <input type="text" name="details[${groupIndex}][items][0][value]" class="form-control" placeholder="Giá trị">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-sub">X</button>
                                                                        </div>
                                                                    </div>
                                                                   <button type="button" class="btn btn-light btn-sm add-sub-item">Thêm nhãn/giá trị</button>
                                                                </div>
                                                            `;
                container.insertAdjacentHTML('beforeend', groupHTML);
                groupIndex++;
            });

            // Thêm nhãn/giá trị trong nhóm
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('add-sub-item')) {
                    const groupWrapper = e.target.closest('.group-wrapper');
                    const groupIdx = groupWrapper.dataset.groupIndex;
                    const subItems = groupWrapper.querySelectorAll('.sub-item');
                    const newIndex = subItems.length;

                    const newItemHTML = `
                                                                    <div class="row mb-2 sub-item">
                                                                        <div class="col-md-5">
                                                                            <input type="text" name="details[${groupIdx}][items][${newIndex}][label]" class="form-control" placeholder="Nhãn">
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <input type="text" name="details[${groupIdx}][items][${newIndex}][value]" class="form-control" placeholder="Giá trị">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-sub">X</button>
                                                                        </div>
                                                                    </div>
                                                                `;

                    e.target.insertAdjacentHTML('beforebegin', newItemHTML);
                }

                // Xoá dòng nhãn/giá trị
                if (e.target && e.target.classList.contains('remove-sub')) {
                    e.target.closest('.sub-item').remove();
                }
            });
        });
    </script>
@endsection
