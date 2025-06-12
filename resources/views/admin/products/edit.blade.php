@extends('layouts.admin')

@section('title', 'Cập nhật danh mục')
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
                    Product Form
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
            <!--begin::Form-->
            <form id="product-form" action="{{ route('admin.products.update', $product->id) }}?page={{ request()->get('page', 1) }}" method="POST"
                enctype="multipart/form-data" id="kt_ecommerce_add_product_form"
                class="form d-flex flex-column flex-lg-row"
                data-kt-redirect="{{ route('admin.products.update', $product->id) }}">
                @csrf
                @method('PUT')
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Thumbnail settings-->
                    <div class="fv-row mb-7">
                        <label class="form-label">Ảnh sản phẩm</label>

                        <!--begin::Image input-->
                        <div class="image-input image-input-outline image-input-placeholder mb-3"
                            data-kt-image-input="true"
                            style="background-image: url('{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/125' }}')">

                            <!--begin::Preview-->
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url('{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/125' }}')">
                            </div>
                            <!--end::Preview-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Thay ảnh">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <!--end::Edit button-->

                            <!--begin::Cancel button-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Hủy">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <!--end::Cancel button-->

                            <!--begin::Remove button-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Xóa ảnh">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <!--end::Remove button-->
                        </div>
                        <!--end::Image input-->

                        @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror

                        <div class="form-text">Định dạng: png, jpg, jpeg. Dung lượng tối đa: 2MB</div>
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
                                <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Hiện
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
                                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
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
                                <option value="{{ $brand->id }}" {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>
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
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Weekly Sales</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <span class="text-muted">No data available. Sales data will begin capturing once product has
                                    been published.</span>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Weekly sales-->
                        <!--begin::Template settings-->
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
                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
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
                                            <input type="text" id="product-name" name="name" class="form-control mb-2"
                                                placeholder="Nhập tên sản phẩm"
                                                value="{{ old('name', $product->name ?? '') }}" />
                                            @error('name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Slug (tự động tạo)</label>
                                            <input type="text" name="slug" id="product-slug" class="form-control mb-2"
                                                placeholder="slug-tu-dong" readonly
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
                                                <input type="number" name="min_purchase_quantity" id="min_purchase_quantity"
                                                    class="form-control @error('min_purchase_quantity') is-invalid @enderror"
                                                    value="{{ old('min_purchase_quantity', $product->min_purchase_quantity ?? 1) }}" min="1">
                                                @error('min_purchase_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="max_purchase_quantity">Mua tối đa:</label>
                                                <input type="number" name="max_purchase_quantity" id="max_purchase_quantity"
                                                    class="form-control @error('max_purchase_quantity') is-invalid @enderror"
                                                    value="{{ old('max_purchase_quantity', $product->max_purchase_quantity ?? 1000000) }}" min="1">
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
                                                    value="{{ old('import_price', $product->import_price ?? 0) }}" min="0" step="0.01">
                                                @error('import_price')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="required form-label">Giá bán</label>
                                                <input type="number" name="base_price" class="form-control"
                                                    value="{{ old('base_price', $product->base_price ?? 0) }}" min="0" step="0.01">
                                                @error('base_price')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="form-label">Giá khuyến mãi</label>
                                                <input type="number" name="sale_price" class="form-control"
                                                    value="{{ old('sale_price', $product->sale_price ?? 0) }}" min="0" step="0.01">
                                                @error('sale_price')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Số lượng tồn kho</label>
                                                <input type="number" name="stock_quantity" class="form-control"
                                                    value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" min="0">
                                                @error('stock_quantity')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Mô tả ngắn -->
                                        <div class="mb-3">
                                            <label for="short_desc" class="form-label">Mô tả ngắn</label>
                                            <textarea name="short_desc" id="short_desc" class="form-control"
                                                rows="3">{{ old('short_desc', $product->short_desc ?? '') }}</textarea>
                                            @error('short_desc')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Mô tả chi tiết -->
                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Mô Tả chi Tiết Sản phẩm</label>
                                            <textarea id="description" name="description" class="form-control"
                                                rows="5">{{ old('description', $product->description ?? '') }}</textarea>
                                            @error('description')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label">Ảnh phụ</label>
                                            <input type="file" id="image-input" name="images[]" multiple accept=".png, .jpg, .jpeg" class="form-control mb-3" />

                                            <!-- Ảnh phụ đã lưu -->
                                            <div class="d-flex flex-wrap gap-4">
                                                @foreach ($product->images as $img)
                                                <div class="position-relative rounded border p-1 shadow-sm" style="width: 120px; height: 120px;" id="image_{{ $img->id }}">
                                                    <img src="{{ asset('storage/' . $img->image_url) }}" class="rounded w-100 h-100 object-fit-cover" alt="Ảnh phụ">

                                                    {{-- Nút xoá ảnh --}}
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                        onclick="removeOldImage({{ $img->id }})" title="Xoá ảnh này">
                                                        &times;
                                                    </button>

                                                    {{-- Thêm input hidden nếu bị xoá --}}
                                                    <input type="hidden" name="existing_image_ids[]" value="{{ $img->id }}">
                                                </div>
                                                @endforeach
                                            </div>

                                            {{-- Hidden container để thêm ảnh cần xoá --}}
                                            <div id="deleted-images-container"></div>

                                            <!-- Preview ảnh mới chọn -->
                                            <div id="image-preview-container" class="d-flex flex-wrap gap-4 mt-4"></div>
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
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Biến thể sản phẩm (từng dòng)</h3>
                                </div>
                                <div class="card-body">
                                    <div id="manual-variant-list">
                                        @foreach ($product->variants as $index => $variant)
                                        <div class="border rounded p-3 mb-3 bg-light" id="variant_card_{{ $index }}">
                                            <div class="row g-3 align-items-end">
                                                @foreach ($variantAttributes as $attribute)
                                                <div class="col-md-3">
                                                    <label class="form-label">{{ $attribute->name }}</label>
                                                    <select name="manual_variants[{{ $index }}][attributes][{{ $attribute->id }}]"
                                                        class="form-select attribute-select" data-index="{{ $index }}">
                                                        <option value="">-- Chọn {{ $attribute->name }} --</option>
                                                        @foreach ($attribute->values as $value)
                                                        @php
                                                        $selected = $variant->options->contains(function ($opt) use ($attribute, $value) {
                                                        return $opt->attribute_id == $attribute->id && $opt->value_id == $value->id;
                                                        });
                                                        @endphp
                                                        <option value="{{ $value->id }}" {{ $selected ? 'selected' : '' }}>
                                                            {{ $value->value }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endforeach

                                                <div class="col-md-2">
                                                    <label class="form-label">SKU</label>
                                                    <input type="text" name="manual_variants[{{ $index }}][sku]"
                                                        class="form-control sku-input"
                                                        value="{{ $variant->sku }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Giá</label>
                                                    <input type="number" name="manual_variants[{{ $index }}][price]"
                                                        class="form-control" min="0" value="{{ $variant->price }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Tồn kho</label>
                                                    <input type="number" name="manual_variants[{{ $index }}][quantity]"
                                                        class="form-control" min="0"
                                                        value="{{ old('manual_variants.' . $index . '.quantity', $variant->quantity ?? 0) }}" required>

                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        title="Xoá biến thể" onclick="removeVariantCard({{ $index }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-sm btn-primary mt-3" onclick="addVariantCard()">+ Thêm biến thể</button>
                                </div>
                            </div>


                            <!-- Nút thêm biến thể -->



                            <!--end::Product Variants-->


                            <!--end::Input group-->

                            {{-- <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold mb-2">
                                                    Discount Type


                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Select a discount type that will be applied to this product">
                                                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i></span> </label>
                                                <!--End::Label-->

                                                <!--begin::Row-->
                                                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9"
                                                    data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6"
                                                            data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span
                                                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                                <input class="form-check-input" type="radio"
                                                                    name="discount_option" value="1" checked="checked" />
                                                            </span>
                                                            <!--end::Radio-->

                                                            <!--begin::Info-->
                                                            <span class="ms-5">
                                                                <span class="fs-4 fw-bold text-gray-800 d-block">No
                                                                    Discount</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                        <!--end::Option-->
                                                    </div>
                                                    <!--end::Col-->

                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary  d-flex text-start p-6"
                                                            data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span
                                                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                                <input class="form-check-input" type="radio"
                                                                    name="discount_option" value="2" />
                                                            </span>
                                                            <!--end::Radio-->

                                                            <!--begin::Info-->
                                                            <span class="ms-5">
                                                                <span class="fs-4 fw-bold text-gray-800 d-block">Percentage
                                                                    %</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                        <!--end::Option-->
                                                    </div>
                                                    <!--end::Col-->

                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6"
                                                            data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span
                                                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                                <input class="form-check-input" type="radio"
                                                                    name="discount_option" value="3" />
                                                            </span>
                                                            <!--end::Radio-->

                                                            <!--begin::Info-->
                                                            <span class="ms-5">
                                                                <span class="fs-4 fw-bold text-gray-800 d-block">Fixed
                                                                    Price</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                        <!--end::Option-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="d-none mb-10 fv-row"
                                                id="kt_ecommerce_add_product_discount_percentage">
                                                <!--begin::Label-->
                                                <label class="form-label">Set Discount Percentage</label>
                                                <!--end::Label-->

                                                <!--begin::Slider-->
                                                <div class="d-flex flex-column text-center mb-5">
                                                    <div class="d-flex align-items-start justify-content-center mb-7">
                                                        <span class="fw-bold fs-3x"
                                                            id="kt_ecommerce_add_product_discount_label">0</span>
                                                        <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                                    </div>
                                                    <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm">
                                                    </div>
                                                </div>
                                                <!--end::Slider-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a percentage discount to be applied on this
                                                    product.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="d-none mb-10 fv-row" id="kt_ecommerce_add_product_discount_fixed">
                                                <!--begin::Label-->
                                                <label class="form-label">Fixed Discounted Price</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="text" name="dicsounted_price" class="form-control mb-2"
                                                    placeholder="Discounted price" />
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set the discounted product price. The product
                                                    will be reduced at the determined fixed price</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Tax-->
                                            <div class="d-flex flex-wrap gap-5">
                                                <!--begin::Input group-->
                                                <div class="fv-row w-100 flex-md-root">
                                                    <!--begin::Label-->
                                                    <label class="required form-label">Tax Class</label>
                                                    <!--end::Label-->

                                                    <!--begin::Select2-->
                                                    <select class="form-select mb-2" name="tax" data-control="select2"
                                                        data-hide-search="true" data-placeholder="Select an option">
                                                        <option></option>
                                                        <option value="0">Tax Free</option>
                                                        <option value="1">Taxable Goods</option>
                                                        <option value="2">Downloadable Product</option>
                                                    </select>
                                                    <!--end::Select2-->

                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set the product tax class.</div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->

                                                <!--begin::Input group-->
                                                <div class="fv-row w-100 flex-md-root">
                                                    <!--begin::Label-->
                                                    <label class="form-label">VAT Amount (%)</label>
                                                    <!--end::Label-->

                                                    <!--begin::Input-->
                                                    <input type="text" class="form-control mb-2" value="" />
                                                    <!--end::Input-->

                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set the product VAT about.</div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end:Tax--> --}}
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Pricing-->
                </div>
        </div>
        <!--end::Tab pane-->

        <!--begin::Tab pane-->
        {{-- <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">

                                    <!--begin::Inventory-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Inventory</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">SKU</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="text" name="sku" class="form-control mb-2"
                                                    placeholder="SKU Number" value="" />
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Enter the product SKU.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Barcode</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="text" name="barcode" class="form-control mb-2"
                                                    placeholder="Barcode Number" value="" />
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Enter the product barcode number.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Quantity</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <div class="d-flex gap-3">
                                                    <input type="number" name="shelf" class="form-control mb-2"
                                                        placeholder="On shelf" value="" />
                                                    <input type="number" name="warehouse" class="form-control mb-2"
                                                        placeholder="In warehouse" />
                                                </div>
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Enter the product quantity.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">Allow Backorders</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input" type="checkbox" value="" />
                                                    <label class="form-check-label">
                                                        Yes
                                                    </label>
                                                </div>
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Allow customers to purchase products that are
                                                    out of stock.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Inventory-->

                                    <!--begin::Variations-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Variations</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                                                <!--begin::Label-->
                                                <label class="form-label">Add Product Variations</label>
                                                <!--end::Label-->

                                                <!--begin::Repeater-->
                                                <div id="kt_ecommerce_add_product_options">
                                                    <!--begin::Form group-->
                                                    <div class="form-group">
                                                        <div data-repeater-list="kt_ecommerce_add_product_options"
                                                            class="d-flex flex-column gap-3">
                                                            <div data-repeater-item
                                                                class="form-group d-flex flex-wrap align-items-center gap-5">
                                                                <!--begin::Select2-->
                                                                <div class="w-100 w-md-200px">
                                                                    <select class="form-select" name="product_option"
                                                                        data-placeholder="Select a variation"
                                                                        data-kt-ecommerce-catalog-add-product="product_option">
                                                                        <option></option>
                                                                        <option value="color">Color</option>
                                                                        <option value="size">Size</option>
                                                                        <option value="material">Material</option>
                                                                        <option value="style">Style</option>
                                                                    </select>
                                                                </div>
                                                                <!--end::Select2-->

                                                                <!--begin::Input-->
                                                                <input type="text" class="form-control mw-100 w-200px"
                                                                    name="product_option_value" placeholder="Variation" />
                                                                <!--end::Input-->

                                                                <button type="button" data-repeater-delete
                                                                    class="btn btn-sm btn-icon btn-light-danger">
                                                                    <i class="ki-duotone ki-cross fs-1"><span
                                                                            class="path1"></span><span
                                                                            class="path2"></span></i> </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form group-->

                                                    <!--begin::Form group-->
                                                    <div class="form-group mt-5">
                                                        <button type="button" data-repeater-create
                                                            class="btn btn-sm btn-light-primary">
                                                            <i class="ki-duotone ki-plus fs-2"></i> Add another variation
                                                        </button>
                                                    </div>
                                                    <!--end::Form group-->
                                                </div>
                                                <!--end::Repeater-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Variations-->

                                    <!--begin::Shipping-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Shipping</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="fv-row">
                                                <!--begin::Input-->
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="kt_ecommerce_add_product_shipping_checkbox" value="1" />
                                                    <label class="form-check-label">
                                                        This is a physical product
                                                    </label>
                                                </div>
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set if the product is a physical or digital
                                                    item. Physical products may require shipping.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Shipping form-->
                                            <div id="kt_ecommerce_add_product_shipping" class="d-none mt-10">
                                                <!--begin::Input group-->
                                                <div class="mb-10 fv-row">
                                                    <!--begin::Label-->
                                                    <label class="form-label">Weight</label>
                                                    <!--end::Label-->

                                                    <!--begin::Editor-->
                                                    <input type="text" name="weight" class="form-control mb-2"
                                                        placeholder="Product weight" value="" />
                                                    <!--end::Editor-->

                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set a product weight in kilograms (kg).
                                                    </div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->

                                                <!--begin::Input group-->
                                                <div class="fv-row">
                                                    <!--begin::Label-->
                                                    <label class="form-label">Dimension</label>
                                                    <!--end::Label-->

                                                    <!--begin::Input-->
                                                    <div class="d-flex flex-wrap flex-sm-nowrap gap-3">
                                                        <input type="number" name="width" class="form-control mb-2"
                                                            placeholder="Width (w)" value="" />
                                                        <input type="number" name="height" class="form-control mb-2"
                                                            placeholder="Height (h)" value="" />
                                                        <input type="number" name="length" class="form-control mb-2"
                                                            placeholder="Lengtn (l)" value="" />
                                                    </div>
                                                    <!--end::Input-->

                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Enter the product dimensions in centimeters
                                                        (cm).</div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end::Shipping form-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Shipping-->
                                    <!--begin::Meta options-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Meta Options</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Title</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="text" class="form-control mb-2" name="meta_title"
                                                    placeholder="Meta tag name" />
                                                <!--end::Input-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a meta tag title. Recommended to be simple
                                                    and precise keywords.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Description</label>
                                                <!--end::Label-->

                                                <!--begin::Editor-->
                                                <div id="kt_ecommerce_add_product_meta_description"
                                                    name="kt_ecommerce_add_product_meta_description"
                                                    class="min-h-100px mb-2"></div>
                                                <!--end::Editor-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a meta tag description to the product for
                                                    increased SEO ranking.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div>
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Keywords</label>
                                                <!--end::Label-->

                                                <!--begin::Editor-->
                                                <input id="kt_ecommerce_add_product_meta_keywords"
                                                    name="kt_ecommerce_add_product_meta_keywords"
                                                    class="form-control mb-2" />
                                                <!--end::Editor-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a list of keywords that the product is
                                                    related to. Separate the keywords by adding a comma <code>,</code>
                                                    between each keyword.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Meta options-->
                                </div>
                            </div> --}}
        <!--end::Tab pane-->

    </div>
    <!--end::Tab content-->

    <div class="d-flex justify-content-end">
        <!--begin::Button-->
        <a href="products.html" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">
            Cancel
        </a>
        <!--end::Button-->

        <!--begin::Button-->
        <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
            <span class="indicator-label">
                Save Changes
            </span>
            <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
        <!--end::Button-->
    </div>
</div>
<!--end::Main column-->
</form>
<!--end::Form-->
</div>
<!--end::Content container-->
</div>
<!--end::Content-->

</div>



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('product-form'); // PHẢI đúng ID của <form>
    const variantList = document.getElementById('manual-variant-list');

    if (form && variantList) {
        form.addEventListener('submit', function (e) {
            if (variantList.children.length === 0) {
                e.preventDefault(); // Ngăn submit
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu biến thể',
                    text: 'Vui lòng thêm ít nhất một biến thể trước khi lưu.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});

    const attributes = @json($variantAttributes);
    let variantIndex = 0;

    function slugify(str) {
        return str.toString().toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // bỏ dấu tiếng Việt
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function generateSKU(productName, selectedValues) {
        const slug = slugify(productName).toUpperCase();
        const valueStr = selectedValues.map(val => val.toUpperCase()).join('-');
        return slug + '-' + valueStr;
    }

    function addVariantCard() {
        const container = document.getElementById('manual-variant-list');
        const uniqueId = 'v_' + Date.now();

        const div = document.createElement('div');
        div.className = 'border rounded p-3 mb-3 bg-light';
        div.id = `variant_card_${uniqueId}`;

        let html = `<div class="row g-3 align-items-end">`;

        attributes.forEach(attr => {
            html += `
                <div class="col-md-3">
                    <label class="form-label">${attr.name}</label>
                    <select name="manual_variants[${uniqueId}][attributes][${attr.id}]" class="form-select attribute-select" data-index="${uniqueId}">
                        <option value="">-- Chọn ${attr.name} --</option>`;
            attr.values.forEach(val => {
                html += `<option value="${val.id}">${val.value}</option>`;
            });
            html += '</select></div>';
        });

        html += `
            <div class="col-md-2">
                <label class="form-label">SKU</label>
                <input type="text" name="manual_variants[${uniqueId}][sku]" class="form-control sku-input" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Giá</label>
                <input type="number" name="manual_variants[${uniqueId}][price]" class="form-control" min="0" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tồn kho</label>
                <input type="number" name="manual_variants[${uniqueId}][quantity]" class="form-control" min="0" required>
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" title="Xoá biến thể" onclick="removeVariantCard('${uniqueId}')">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;

        div.innerHTML = html;
        container.appendChild(div);
        attachSKUListener(uniqueId);
        variantIndex++;
    }

    function removeVariantCard(index) {
        const card = document.getElementById('variant_card_' + index);
        if (card) card.remove();

        // Kiểm tra sau khi xóa
        const variantList = document.getElementById('manual-variant-list');
        if (variantList.children.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tối thiểu một biến thể',
                text: 'Sản phẩm phải có ít nhất một biến thể. Hãy thêm lại.',
                confirmButtonText: 'OK'
            });
        }
    }

    function attachSKUListener(id) {
        const card = document.getElementById('variant_card_' + id);
        const selects = card.querySelectorAll('.attribute-select');

        selects.forEach(select => {
            select.addEventListener('change', () => {
                const values = [];
                selects.forEach(s => {
                    const text = s.options[s.selectedIndex]?.text;
                    if (text && !text.startsWith('--')) values.push(text.trim());
                });

                const productName = document.querySelector('input[name=name]').value || 'SP';
                const sku = generateSKU(productName, values);

                const skuInput = card.querySelector('.sku-input');
                if (skuInput) skuInput.value = sku;
            });
        });
    }

    // Preview ảnh mới khi chọn
    document.getElementById('image-input').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = ''; // Clear ảnh cũ

        const files = Array.from(e.target.files);
        const dt = new DataTransfer();

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'position-relative';
                imgWrapper.style.width = '100px';

                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'rounded border';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.width = '100%';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1';
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = function() {
                    imgWrapper.remove();
                    // Xoá file khỏi input
                    const input = document.getElementById('image-input');
                    const newDt = new DataTransfer();
                    Array.from(input.files).forEach((f, i) => {
                        if (i !== index) newDt.items.add(f);
                    });
                    input.files = newDt.files;
                };

                imgWrapper.appendChild(img);
                imgWrapper.appendChild(removeBtn);
                previewContainer.appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });
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

    // Auto-generate slug nếu có input slug
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('product-name');
        const slugInput = document.getElementById('product-slug');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                slugInput.value = slugify(nameInput.value);
            });
        }
    });
</script>



@endpush
<!--end::Content wrapper-->
@endsection