@extends('layouts.admin')

@section('title', 'Thêm mới danh mục')
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
                                    <label class="form-label fw-semibold">Status:</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" multiple data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="Select option"
                                            data-dropdown-parent="#kt_menu_683db6e98b446" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Show</option>
                                            <option value="0">Hide</option>
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
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                    id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row"
                    data-kt-redirect="{{ route('admin.products.index') }}">
                    @csrf
                    <!--begin::Aside column-->
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                        <!--begin::Thumbnail settings-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Ảnh</h2>
                                </div>
                                <!--end::Card     title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body text-center pt-0">
                                <!--begin::Image input-->
                                <!--begin::Image input placeholder-->
                                <style>
                                    .image-input-placeholder {
                                        background-image: url('../../../assets/media/svg/files/blank-image.svg');
                                    }

                                    [data-bs-theme="dark"] .image-input-placeholder {
                                        background-image: url('../../../assets/media/svg/files/blank-image-dark.svg');
                                    }
                                </style>
                                <!--end::Image input placeholder-->

                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                    data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                    <!--end::Preview existing avatar-->

                                    <!--begin::Label-->
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span
                                                class="path2"></span></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="images" accept=".png, .jpg, .jpeg"
                                            class="form-control mb-2" />

                                        <!--end::Inputs-->
                                    </label>
                                    @error('images')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <!--end::Label-->

                                    <!--begin::Cancel-->
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </span>
                                    <!--end::Cancel-->

                                    <!--begin::Remove-->
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7">
                                    **Chọn ảnh đại diện sản phẩm (chỉ hỗ trợ *.png, .jpg, .jpeg).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card body-->
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
                                    data-hide-search="true" data-placeholder="Chọn trạng thái"
                                    id="kt_ecommerce_add_product_status_select">
                                    <option></option>
                                    <option value="1" {{ old('status', $product->status ?? '1') == '1' ? 'selected' : '' }}>
                                        Hiện</option>
                                    <option value="0" {{ old('status', $product->status ?? '1') == '0' ? 'selected' : '' }}>Ẩn
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <!--end::Select2-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product status.</div>
                                <!--end::Description-->

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
                                    <h2>Danh Mục Sản Phẩm</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <!--begin::Label-->
                                <label class="form-label">Danh Mục:</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <select name="category_id" class="form-select mb-2" data-control="select2"
                                    data-placeholder="Chọn danh mục" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <!--end::Select2-->

                                <!--begin::Description-->
                                <div class="text-muted fs-7 mb-7">Add product to a category.</div>
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
                        <!--end::Category & tags-->

                    </div>
                    <!--end::Aside column-->

                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">

                                    <!--begin::General options-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Tổng quan</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Tên Sản phẩm</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="text" name="name" class="form-control mb-2"
                                                    placeholder="Tên Sản Phẩm"
                                                    value="{{ old('name', $product->name ?? '') }}" />
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Mã Sản Phẩm</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="text" name="code" class="form-control mb-2"
                                                    placeholder="Mã Sản Phẩm"
                                                    value="{{ old('code', $product->code ?? '') }}" />
                                                @error('code')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <!--end::Input-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="min_purchase_quantity">Mua tối thiểu:</label>
                                                        <input type="number" name="min_purchase_quantity"
                                                            id="min_purchase_quantity" class="form-control"
                                                            value="{{ old('min_purchase_quantity', $product->min_purchase_quantity ?? 1) }}"
                                                            min="1">
                                                    </div>
                                                </div>
                                                @error('min_purchase_quantity')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="max_purchase_quantity">Mua tối đa:</label>
                                                        <input type="number" name="max_purchase_quantity"
                                                            id="max_purchase_quantity" class="form-control"
                                                            value="{{ old('max_purchase_quantity', $product->max_purchase_quantity ?? 100) }}"
                                                            min="1">
                                                    </div>
                                                </div>
                                                @error('max_purchase_quantity')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>


                                            <div class="mb-3">
                                                <label for="short_desc" class="form-label">Mô tả ngắn</label>
                                                <textarea name="short_desc" id="short_desc" class="form-control"
                                                    rows="3">{{ old('short_desc', $product->short_desc ?? '') }}</textarea>
                                                @error('short_desc')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!--begin::Input group-->
                                            <!-- Form input -->
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Mô Tả chi Tiết Sản phẩm</label>
                                                <textarea id="description" name="description" class="form-control"
                                                    rows="5">{{ old('description') }}</textarea>
                                                @error('description') <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Nhúng CKEditor -->
                                            <script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
                                            <script>
                                                // Kích hoạt CKEditor cho textarea có id là 'description'
                                                CKEDITOR.replace('description', {
                                                    height: 100,
                                                    toolbarCanCollapse: true
                                                });
                                            </script>


                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::General options-->


                                    <!--begin::Pricing-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Biến Thể Sản Phẩm</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Product Variants-->
                                             
                                                        <div id="variants-container">
                                                            <!-- Template biến thể -->
                                                            <div class="variant-item border p-4 rounded mb-4">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label>Màu sắc</label>
                                                                        <input type="text" name="variants[0][color]"
                                                                            class="form-control" placeholder="VD: Đỏ">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Kích thước</label>
                                                                        <input type="text" name="variants[0][size]"
                                                                            class="form-control" placeholder="VD: M, L">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label>Giá nhập</label>
                                                                        <input type="number" step="0.01"
                                                                            name="variants[0][import_price]"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label>Giá bán</label>
                                                                        <input type="number" step="0.01"
                                                                            name="variants[0][base_price]"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label>Giá khuyến mãi</label>
                                                                        <input type="number" step="0.01"
                                                                            name="variants[0][sale_price]"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3 mt-3">
                                                                        <label>Tồn kho</label>
                                                                        <input type="number"
                                                                            name="variants[0][stock_quantity]"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3 mt-3">
                                                                        <label>Ảnh biến thể</label>
                                                                        <input type="file" name="variants[0][image]"
                                                                            accept=".jpg,.jpeg,.png" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-2 mt-5">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mt-2 remove-variant">Xóa</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" id="add-variant"
                                                            class="btn btn-light-primary btn-sm">+ Thêm biến thể</button>
                                               
                                                <!--end::Product Variants-->
                                            </div>
                                            <!--end::Tab content-->

                                            <div class="d-flex justify-content-end">
                                                <!--begin::Button-->
                                                <a href="products.html" id="kt_ecommerce_add_product_cancel"
                                                    class="btn btn-light me-5">
                                                    Cancel
                                                </a>
                                                <!--end::Button-->

                                                <!--begin::Button-->
                                                <button type="submit" id="kt_ecommerce_add_product_submit"
                                                    class="btn btn-primary">
                                                    <span class="indicator-label">
                                                        Save Changes
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
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

    <!--end::Content wrapper-->
@endsection