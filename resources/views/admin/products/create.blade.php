@extends('layouts.admin')

@section('title', 'Thêm mới sản phẩm')
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
                        Thêm sản phẩm
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
                <form id="product-form" action="{{ route('admin.products.store') }}" method="POST"
                    enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row"
                    data-kt-redirect="{{ route('admin.products.index') }}">
                    @csrf
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                        <!-- Ảnh đại diện sản phẩm -->
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Ảnh</h2>
                                </div>
                            </div>
                            <div class="card-body text-center pt-0">
                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change">
                                        <i class="ki-duotone ki-pencil fs-7"></i>
                                        <input type="file" name="image" accept=".png, .jpg, .jpeg"
                                            class="form-control mb-2" />
                                    </label>
                                    @error('images')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove">
                                        <i class="ki-duotone ki-cross fs-2"></i>
                                    </span>
                                </div>
                                <div class="text-muted fs-7">**Chọn ảnh đại diện sản phẩm (chỉ hỗ trợ *.png, .jpg, .jpeg).
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
                                            {{ $category->name }}</option>
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
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-muted fs-7 mb-7">Add product to a brand.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!-- Tổng quan -->
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Tổng quan</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <label class="form-label required">Tên Sản phẩm</label>
                                <input type="text" id="product-name" name="name" class="form-control mb-2"
                                    placeholder="Tên Sản Phẩm" value="{{ old('name', $product->name ?? '') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Slug</label>
                                        <input type="text" name="slug" id="product-slug" class="form-control mb-2"
                                            placeholder="slug-tu-dong" value="{{ old('slug', $product->slug ?? '') }}">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">SKU</label>
                                        <input type="text" name="sku" class="form-control mb-2"
                                            placeholder="Mã sản phẩm" value="{{ old('sku', $product->sku ?? '') }}">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Giá nhập</label>
                                        <input type="number" name="import_price" class="form-control"
                                            value="{{ old('import_price') }}">
                                        @error('import_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Giá gốc</label>
                                        <input type="number" name="base_price" class="form-control"
                                            value="{{ old('base_price') }}">
                                        @error('base_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Giá sale</label>
                                        <input type="number" name="sale_price" class="form-control"
                                            value="{{ old('sale_price') }}">
                                        @error('sale_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tồn kho</label>
                                        <input type="number" id="stock_quantity" name="stock_quantity"
                                            class="form-control" value="{{ old('stock_quantity') }}">

                                        <small class="text-muted">Tự động tính từ các biến thể (nếu có)</small>

                                        @error('stock_quantity')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6">
                                        <label class="form-label">Thời gian bắt đầu</label>
                                        <input type="datetime-local" name="starts_at" class="form-control"
                                            value="{{ old('starts_at') }}">
                                        @error('starts_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Thời gian kết thúc</label>
                                        <input type="datetime-local" name="ends_at" class="form-control"
                                            value="{{ old('ends_at') }}">
                                        @error('ends_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Phần trăm / giá tiền</label>
                                        <input type="number" name="sale_times" class="form-control"
                                            value="{{ old('sale_times') }}">
                                        @error('sale_times')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <label class="form-label">Mô tả ngắn</label>
                                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                {{-- Mô tả chi tiết --}}
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="detailed_description" id="editor" class="form-control" rows="10">{{ old('detailed_description', $product->detailed_description ?? '') }}</textarea>

                                @error('detailed_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                <label>Ảnh phụ</label>
                                <input type="file" name="images[]" id="image-input" class="form-control" multiple
                                    accept=".png,.jpg,.jpeg">
                                <div id="image-preview-container" class="mt-2 d-flex flex-wrap gap-3"></div>
                                <!-- Thêm header và nút xóa tất cả chi tiết sản phẩm -->
                                <div id="product-details-header" class="d-flex align-items-center mb-3">
                                    <h5 class="mb-0 me-3">Chi tiết sản phẩm</h5>

                                </div>
                                <div id="product-details-container">
                                    {{-- Nhóm đầu tiên mặc định --}}
                                    <div class="group-wrapper mb-4 position-relative" data-group-index="0">
                                        <button type="button"
                                            class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-group"
                                            aria-label="Close"></button>
                                        <div class="mb-2">
                                            <input type="text" name="details[0][group_name]" class="form-control"
                                                placeholder="Tên nhóm (ví dụ: Cách giặt)">
                                        </div>
                                        <div class="sub-items-wrapper">
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
                                        </div>
                                        <button type="button" class="btn btn-light-primary btn-sm add-sub-item mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Thêm nhãn
                                        </button>
                                    </div>
                                </div>

                                {{-- Nút thêm nhóm chi tiết mới --}}
                                <button type="button" class="btn btn-light-primary btn-sm mt-3" id="add-group">
                                    <i class="fas fa-layer-group me-1"></i> Thêm chi tiết
                                </button>


                            </div>

                        </div>

                        <!-- Biến thể -->
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <h3 class="card-title">Phân loại sản phẩm</h3>
                            </div>
                            <div class="card-body">
                                <div id="pf_attribute_groups_wrapper" class="mb-4"></div>
                                <button type="button" id="pf_add_attribute_group" class="btn btn-light-primary mb-3">+
                                    Thêm nhóm phân loại</button>
                            </div>
                        </div>

                        <div class="card card-flush py-4" id="pf_variant_section" style="display: none">
                            <div class="card-header">
                                <h3 class="card-title">Danh sách phân loại hàng</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Thuộc tính</th>
                                            <th>Giá bán</th>
                                            <th>Số lượng</th>
                                            <th>SKU</th>
                                            <th>Cân nặng (g)</th>
                                            <th>Dài (cm)</th>
                                            <th>Rộng (cm)</th>
                                            <th>Cao (cm)</th>
                                            <th>Xóa</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="pf_variant_list"></tbody>
                                </table>
                            </div>
                        </div>

                        <div id="product-dimensions-wrapper" class="row">
                            <div class="col-md-3">
                                <label class="form-label">Cân nặng (g) <span class="text-danger">*</span></label>
                                <input type="number" name="weight" class="form-control"
                                    value="{{ old('weight', $product->weight ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Dài (cm) <span class="text-danger">*</span></label>
                                <input type="number" name="length" class="form-control"
                                    value="{{ old('length', $product->length ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rộng (cm) <span class="text-danger">*</span></label>
                                <input type="number" name="width" class="form-control"
                                    value="{{ old('width', $product->width ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cao (cm) <span class="text-danger">*</span></label>
                                <input type="number" name="height" class="form-control"
                                    value="{{ old('height', $product->height ?? '') }}">
                            </div>
                        </div>


                        <!-- Submit -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light me-5">Huỷ</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Lưu thay đổi</span>
                                <span class="indicator-progress">Đang xử lý... <span
                                        class="spinner-border spinner-border-sm ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!-- CDN SortableJS -->




    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor', {
            filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        function slugify(str) {
            return str
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // bỏ dấu tiếng Việt
                .replace(/đ/g, 'd').replace(/Đ/g, 'D')
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-') // thay khoảng trắng bằng dấu gạch
                .replace(/[^\w\-]+/g, '') // bỏ ký tự đặc biệt
                .replace(/\-\-+/g, '-') // bỏ gạch đôi
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('product-name');
            const slugInput = document.getElementById('product-slug');

            nameInput.addEventListener('input', function() {
                const slug = slugify(this.value);
                slugInput.value = slug;
            });
            calculateTotalStock();
            toggleProductDimensions();
        });


        let previewId = 0;

        document.getElementById('image-input').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const previewContainer = document.getElementById('image-preview-container');

            previewContainer.innerHTML = ''; // Reset ảnh cũ nếu chọn lại

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
                        // ❌ Không xóa khỏi mảng vì không dùng FormData custom
                        imgWrapper.remove();
                        // 👇 Trick: xóa ảnh khỏi input bằng cách clone lại input
                        const input = document.getElementById('image-input');
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        input.files = dt.files;
                    };

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(removeBtn);
                    previewContainer.appendChild(imgWrapper);
                };
                reader.readAsDataURL(file);
            });
        });

        let pfAttributeIndex = 0;
        let pfAttributeGroups = {};

        const PF_ATTRIBUTE_SUGGESTIONS = {
            "Màu sắc": ["Đỏ", "Cam", "Vàng", "Xanh lá", "Xanh dương", "Tím", "Hồng", "Xanh quân đội",
                "Xanh dương nhạt"
            ],
            "Size": ["XS", "S", "M", "L", "XL", "XXL"],
            "Giới tính": ["Nam", "Nữ", "Unisex"]
        };

        document.getElementById("pf_add_attribute_group").addEventListener("click", pfAddAttributeGroup);

        function pfAddAttributeGroup() {
            const wrapper = document.getElementById("pf_attribute_groups_wrapper");
            const groupId = `pf_group_${pfAttributeIndex}`;
            pfAttributeGroups[groupId] = {
                name: "",
                values: []
            };

            const div = document.createElement("div");
            div.className = "bg-light rounded p-4 border position-relative mb-4";
            div.id = groupId;

            div.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" onclick="pfRemoveAttributeGroup('${groupId}')"></button>
        <div class="mb-3 d-flex align-items-center gap-3">
            <label class="form-label fw-bold mb-0" style="min-width: 90px;">Phân loại</label>
            <input type="text" class="form-control w-50 pf-attribute-name-input" placeholder="Chọn hoặc nhập phân loại" />
        </div>
        <div class="mb-1">
            <label class="form-label fw-bold">Tuỳ chọn</label>
            <div id="${groupId}_tags" class="pf-attribute-option-container d-flex flex-wrap gap-2 align-items-center"></div>
            <div class="form-text text-muted">Nhập và nhấn Enter hoặc chọn từ gợi ý</div>
        </div>
        <input type="hidden" name="attributeGroups[]" value="">
    `;

            wrapper.appendChild(div);

            const selectInput = div.querySelector(".pf-attribute-name-input");
            const used = Object.values(pfAttributeGroups).map(g => g.name).filter(Boolean);
            const options = Object.keys(PF_ATTRIBUTE_SUGGESTIONS).map(name => ({
                value: name,
                text: name,
                group: "suggested",
                disabled: used.includes(name)
            }));

            new TomSelect(selectInput, {
                create: true,
                maxItems: 1,
                mode: "input",
                options,
                optgroups: [{
                    value: "suggested",
                    label: "Giá trị đề xuất"
                }],
                optgroupField: "group",
                placeholder: "Chọn hoặc nhập phân loại",
                onChange: (value) => {
                    pfAttributeGroups[groupId].name = value;
                    div.querySelector('input[type="hidden"]').value = value;
                    pfAttributeGroups[groupId].values = [];
                    pfRenderTags(groupId);
                }
            });

            pfRenderTags(groupId);
            pfAttributeIndex++;
            document.getElementById("pf_variant_section").style.display = "block";
        }

        function pfRenderTags(groupId) {
            const container = document.getElementById(`${groupId}_tags`);
            container.innerHTML = "";

            const selected = pfAttributeGroups[groupId].values;

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

            const suggest = pfGetSuggestions(groupId);
            const ts = new TomSelect(input, {
                create: true,
                maxItems: 1,
                persist: false,
                options: [],
                onItemAdd(value) {
                    if (!pfAttributeGroups[groupId].values.includes(value)) {
                        pfAttributeGroups[groupId].values.push(value);
                        pfRenderTags(groupId);
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
                    } else ts.clear();
                }
            });

            ts.addOptions(suggest);
            pfGenerateVariants(); // ✅ tự động tạo biến thể mỗi lần render lại tag
        }

        function pfGetSuggestions(groupId) {
            const name = pfAttributeGroups[groupId].name;
            const used = new Set();

            Object.keys(pfAttributeGroups).forEach(id => {
                if (id !== groupId) {
                    pfAttributeGroups[id].values.forEach(v => used.add(v));
                }
            });

            return (PF_ATTRIBUTE_SUGGESTIONS[name] || []).map(val => ({
                value: val,
                text: val,
                disabled: used.has(val)
            }));
        }

        function pfRemoveAttributeGroup(id) {
            delete pfAttributeGroups[id];
            document.getElementById(id)?.remove();
            pfGenerateVariants();
        }

        function pfRemoveTag(groupId, val) {
            pfAttributeGroups[groupId].values = pfAttributeGroups[groupId].values.filter(v => v !== val);
            pfRenderTags(groupId);
            pfGenerateVariants();
        }

        function pfGenerateVariants() {

            const section = document.getElementById("pf_variant_section");
            const tbody = document.getElementById("pf_variant_list");

            const groupIds = Object.keys(pfAttributeGroups);
            if (groupIds.length === 0 || groupIds.some(id => pfAttributeGroups[id].values.length === 0)) {
                section.style.display = "none";
                return;
            }

            section.style.display = "block";
            tbody.innerHTML = "";
            // Tạo dòng áp dụng cho tất cả
            const headerRow = document.createElement("tr");
            headerRow.innerHTML = `
    <td class="fw-semibold text-muted">Áp dụng cho tất cả</td>
    <td><input type="number" class="form-control form-control-sm" id="pf_apply_price" placeholder="₫ Nhập vào"></td>
    <td><input type="number" class="form-control form-control-sm" id="pf_apply_quantity" placeholder="0"></td>
    <td><input type="text" class="form-control form-control-sm" id="pf_apply_sku" placeholder="Nhập vào"></td>
    <td><input type="number" class="form-control form-control-sm" id="pf_apply_weight" placeholder="gram"></td>
    <td><input type="number" class="form-control form-control-sm" id="pf_apply_length" placeholder="cm"></td>
    <td><input type="number" class="form-control form-control-sm" id="pf_apply_width" placeholder="cm"></td>
    <td><input type="number" class="form-control form-control-sm" id="pf_apply_height" placeholder="cm"></td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="pfApplyToAllVariants()">Áp dụng</button></td>
`;
            tbody.appendChild(headerRow);

            const valueLists = groupIds.map(id => pfAttributeGroups[id].values);
            const combinations = pfCartesian(valueLists);

            combinations.forEach((combo, i) => {
                const label = combo.join(" / ");
                const row = document.createElement("tr");
                row.innerHTML = `
    <td>${label}<input type="hidden" name="variants[${i}][attributes]" value="${label}"></td>
    <td><input type="number" name="variants[${i}][price]" class="form-control"></td>
    <td><input type="number" name="variants[${i}][quantity]" class="form-control"></td>
    <td><input type="text" name="variants[${i}][sku]" class="form-control"></td>
    <td><input type="number" name="variants[${i}][weight]" class="form-control" placeholder="gram"></td>
    <td><input type="number" name="variants[${i}][length]" class="form-control" placeholder="cm"></td>
    <td><input type="number" name="variants[${i}][width]" class="form-control" placeholder="cm"></td>
    <td><input type="number" name="variants[${i}][height]" class="form-control" placeholder="cm"></td>
    <td class="text-center">
        <button type="button" class="btn btn-sm btn-light-danger" onclick="removeVariantRow(this)">
            <i class="bi bi-trash"></i>
        </button>
    </td>
`;

                tbody.appendChild(row);
                row.querySelectorAll('input[name$="[quantity]"]').forEach(input => {
                    input.addEventListener('input', calculateTotalStock);
                });

            });
            calculateTotalStock();
            toggleProductDimensions(); // GỌI LẠI HÀM NÀY
        }

        function pfApplyToAllVariants() {
            const price = document.getElementById('pf_apply_price').value;
            const quantity = document.getElementById('pf_apply_quantity').value;
            const sku = document.getElementById('pf_apply_sku').value;
            const weight = document.getElementById('pf_apply_weight').value;
            const length = document.getElementById('pf_apply_length').value;
            const width = document.getElementById('pf_apply_width').value;
            const height = document.getElementById('pf_apply_height').value;

            const rows = document.querySelectorAll('#pf_variant_list tr');
            rows.forEach((row) => {
                if (row.querySelector('td')?.textContent.includes('Áp dụng cho tất cả')) return;
                if (price) row.querySelector(`input[name$="[price]"]`).value = price;
                if (quantity) row.querySelector(`input[name$="[quantity]"]`).value = quantity;
                if (sku) row.querySelector(`input[name$="[sku]"]`).value = sku;
                if (weight) row.querySelector(`input[name$="[weight]"]`).value = weight;
                if (length) row.querySelector(`input[name$="[length]"]`).value = length;
                if (width) row.querySelector(`input[name$="[width]"]`).value = width;
                if (height) row.querySelector(`input[name$="[height]"]`).value = height;
            });
            calculateTotalStock();
        }


        function pfCartesian(arrays) {
            return arrays.reduce((a, b) => a.flatMap(d => b.map(e => d.concat(e))), [
                []
            ]);
        }

        function calculateTotalStock() {
            const stockInput = document.getElementById("stock_quantity");
            if (!stockInput) return;

            const qtyInputs = document.querySelectorAll('input[name^="variants"][name$="[quantity]"]');
            if (qtyInputs.length === 0) {
                stockInput.readOnly = false;
                // ❌ BỎ DÒNG NÀY stockInput.disabled = false;
                stockInput.value = '';
                updateStockInputState();
                return;
            }

            let total = 0;
            qtyInputs.forEach(input => {
                const val = parseInt(input.value);
                if (!isNaN(val)) total += val;
            });

            stockInput.value = total;
            stockInput.readOnly = true;
            // ❌ BỎ DÒNG NÀY stockInput.disabled = true;

            updateStockInputState();
        }



        function removeVariantRow(button) {
            const row = button.closest("tr");
            if (row) {
                row.remove();
                calculateTotalStock(); // cập nhật lại tổng
                toggleProductDimensions(); // GỌI LẠI HÀM NÀY
            }
        }

        function updateStockInputState() {
            const stockInput = document.getElementById("stock_quantity");
            const stockNote = document.getElementById("stock_note");

            const qtyInputs = document.querySelectorAll('input[name^="variants"][name$="[quantity]"]');
            const hasVariants = qtyInputs.length > 0;

            if (hasVariants) {
                stockInput.readOnly = true;
                stockInput.classList.add('bg-light'); // ✅ nền xám giống disabled
                if (stockNote) stockNote.style.display = 'block';
            } else {
                stockInput.readOnly = false;
                stockInput.classList.remove('bg-light'); // ✅ trở về nền trắng
                if (stockNote) stockNote.style.display = 'none';
            }
        }

        function toggleProductDimensions() {
            const dimWrapper = document.getElementById('product-dimensions-wrapper');
            const variantList = document.getElementById('pf_variant_list');
            let variantCount = 0;
            if (variantList) {
                variantCount = Array.from(variantList.children).filter(tr => {
                    return !tr.textContent.includes('Áp dụng cho tất cả');
                }).length;
            }
            if (dimWrapper) {
                if (variantCount > 0) {
                    dimWrapper.style.display = 'none';
                } else {
                    dimWrapper.style.display = 'flex'; // hoặc 'block' nếu bạn muốn
                }
            }
        }

        // Gọi khi load trang và mỗi lần render lại biến thể
        document.addEventListener('DOMContentLoaded', function() {
            toggleProductDimensions();
            // Gọi lại mỗi khi render biến thể (sau mỗi lần thêm/xóa biến thể)
            window.pfGenerateVariants = (function(oldFn) {
                return function() {
                    oldFn.apply(this, arguments);
                    toggleProductDimensions();
                }
            })(window.pfGenerateVariants);
        });


        // Chi tiết sản phẩm 
        document.addEventListener('DOMContentLoaded', function() {
            let groupIndex = 1;

            document.getElementById('add-group').addEventListener('click', function() {
                const container = document.getElementById('product-details-container');

                // const groupHTML = `
            //     <div class="group-wrapper mb-4" data-group-index="${groupIndex}">
            //         <div class="mb-2">
            //             <input type="text" name="details[${groupIndex}][group_name]" class="form-control"
            //                 placeholder="Tên nhóm (ví dụ: Kích thước)">
            //         </div>

            //         <div class="sub-items-wrapper">
            //             <div class="row mb-2 sub-item">
            //                 <div class="col-md-5">
            //                     <input type="text" name="details[${groupIndex}][items][0][label]" class="form-control" placeholder="Nhãn">
            //                 </div>
            //                 <div class="col-md-5">
            //                     <input type="text" name="details[${groupIndex}][items][0][value]" class="form-control" placeholder="Giá trị">
            //                 </div>
            //                 <div class="col-md-2 d-flex align-items-center">
            //                     <button type="button" class="btn btn-danger btn-sm remove-sub">X</button>
            //                 </div>
            //             </div>
            //         </div>

            //         <button type="button" class="btn btn-light-primary btn-sm add-sub-item mt-2">
            //             <i class="fas fa-plus-circle me-1"></i> Thêm nhãn
            //         </button>
            //     </div>
            // `;

                container.insertAdjacentHTML('beforeend', groupHTML);
                groupIndex++;
            });

            document.addEventListener('click', function(e) {
                // Thêm nhãn mới trong nhóm
                if (e.target.closest('.add-sub-item')) {
                    const groupWrapper = e.target.closest('.group-wrapper');
                    const groupIdx = groupWrapper.dataset.groupIndex;
                    const subItems = groupWrapper.querySelectorAll('.sub-item');
                    const itemIdx = subItems.length;

                    const subHTML = `
                <div class="row mb-2 sub-item">
                    <div class="col-md-5">
                        <input type="text" name="details[${groupIdx}][items][${itemIdx}][label]" class="form-control" placeholder="Nhãn">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="details[${groupIdx}][items][${itemIdx}][value]" class="form-control" placeholder="Giá trị">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-sub">X</button>
                    </div>
                </div>
            `;

                    groupWrapper.querySelector('.sub-items-wrapper').insertAdjacentHTML('beforeend',
                        subHTML);
                }

                // Xoá nhãn
                if (e.target.classList.contains('remove-sub')) {
                    e.target.closest('.sub-item').remove();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Xoá nhóm chi tiết sản phẩm
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-group')) {
                    const group = e.target.closest('.group-wrapper');
                    if (group) {
                        group.remove();
                    }
                }
            });
        });
        $('#add-group').click(function() {
            let index = $('.group-wrapper').length;
            let html = `
<div class="group-wrapper position-relative mb-4" data-group-index="${index}">
    <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-group" style="z-index:10;" aria-label="Close"></button>
    <div class="mb-2">
        <input type="text" name="details[${index}][group_name]" class="form-control" placeholder="Tên nhóm (ví dụ: Cách giặt)">
    </div>
    <div class="sub-items-wrapper">
        <div class="row mb-2 sub-item">
            <div class="col-md-5">
                <input type="text" name="details[${index}][items][0][label]" class="form-control" placeholder="Nhãn">
            </div>
            <div class="col-md-5">
                <input type="text" name="details[${index}][items][0][value]" class="form-control" placeholder="Giá trị">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-sub">X</button>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-light-primary btn-sm add-sub-item mt-2">
        <i class="fas fa-plus-circle me-1"></i> Thêm nhãn
    </button>
</div>
`;

            $('#product-details-container').append(html);
        });
        $(document).on('click', '.remove-group', function() {
            $(this).closest('.group-wrapper').remove();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('product-details-container');

            // Xóa nhóm chi tiết
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-group')) {
                    const group = e.target.closest('.group-wrapper');
                    if (group) group.remove();
                }
            });
        });
    </script>





    <!--end::Content wrapper-->

@endsection
