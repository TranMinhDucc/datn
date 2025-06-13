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
            <form id="product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row" data-kt-redirect="{{ route('admin.products.index') }}">
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
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change">
                                    <i class="ki-duotone ki-pencil fs-7"></i>
                                    <input type="file" name="image" accept=".png, .jpg, .jpeg" class="form-control mb-2" />
                                </label>
                                @error('images')<div class="text-danger">{{ $message }}</div>@enderror
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove">
                                    <i class="ki-duotone ki-cross fs-2"></i>
                                </span>
                            </div>
                            <div class="text-muted fs-7">**Chọn ảnh đại diện sản phẩm (chỉ hỗ trợ *.png, .jpg, .jpeg).</div>
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
                                <option value="1" {{ old('is_active', $product->is_active ?? '1') == '1' ? 'selected' : '' }}>Hiện</option>
                                <option value="0" {{ old('is_active', $product->is_active ?? '1') == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            @error('is_active')<div class="text-danger">{{ $message }}</div>@enderror
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
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="text-danger">{{ $message }}</div>@enderror
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
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')<div class="text-danger">{{ $message }}</div>@enderror
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
                            <input type="text" id="product-name" name="name" class="form-control mb-2" placeholder="Tên Sản Phẩm" value="{{ old('name', $product->name ?? '') }}">
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror

                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="product-slug" class="form-control mb-2" placeholder="slug-tu-dong" value="{{ old('slug', $product->slug ?? '') }}">
                            @error('slug')<div class="text-danger">{{ $message }}</div>@enderror

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Giá nhập</label>
                                    <input type="number" name="import_price" class="form-control" value="{{ old('import_price') }}">
                                    @error('import_price')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Giá gốc</label>
                                    <input type="number" name="base_price" class="form-control" value="{{ old('base_price') }}">
                                    @error('base_price')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Giá sale</label>
                                    <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                                    @error('sale_price')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tồn kho</label>
                                    <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}">
                                    @error('stock_quantity')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <label class="form-label">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                            @error('description')<div class="text-danger">{{ $message }}</div>@enderror

                            <label>Ảnh phụ</label>
                            <input type="file" name="images[]" id="image-input" class="form-control" multiple accept=".png,.jpg,.jpeg">
                            <div id="image-preview-container" class="mt-2 d-flex flex-wrap gap-3"></div>
                        </div>
                    </div>

                    <!-- Biến thể -->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <h3 class="card-title">Phân loại sản phẩm</h3>
                        </div>
                        <div class="card-body">
                            <div id="pf_attribute_groups_wrapper" class="mb-4"></div>
                            <button type="button" id="pf_add_attribute_group" class="btn btn-light-primary mb-3">+ Thêm nhóm phân loại</button>
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="pf_variant_list"></tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Submit -->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light me-5">Huỷ</a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Lưu thay đổi</span>
                            <span class="indicator-progress">Đang xử lý... <span class="spinner-border spinner-border-sm ms-2"></span></span>
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
<!-- <script>
    let attributeGroupIndex = 0;
    let attributeGroups = {};

    const ATTRIBUTE_SUGGESTIONS = {
        "Màu sắc": ["Đỏ", "Cam", "Vàng", "Xanh lá", "Xanh dương", "Tím", "Hồng", "Xanh quân đội", "Xanh dương nhạt"],
        "Size": ["XS", "S", "M", "L", "XL", "XXL"],
        "Giới tính": ["Nam", "Nữ", "Unisex"]
    };

    function addAttributeGroup() {
        const container = document.getElementById('attribute-group-list');
        const groupId = `attribute_group_${attributeGroupIndex}`;
        attributeGroups[groupId] = { name: '', values: [] };

        const div = document.createElement('div');
        div.className = 'bg-light rounded p-4 border position-relative mb-4';
        div.id = groupId;

        div.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" onclick="removeAttributeGroup('${groupId}')"></button>
            <div class="mb-3 d-flex align-items-center gap-3">
                <label class="form-label fw-bold mb-0" style="min-width: 90px;">Phân loại</label>
                <input type="text" class="form-control w-50 attribute-name-input" placeholder="Chọn hoặc nhập phân loại" />
            </div>
            <div class="mb-1">
                <label class="form-label fw-bold">Tuỳ chọn</label>
                <div id="${groupId}_tags" class="attribute-option-container d-flex flex-wrap gap-2 align-items-center"></div>
                <div class="form-text text-muted">Nhập và nhấn Enter hoặc chọn từ gợi ý</div>
            </div>
        `;

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'attributeGroups[]';
        hiddenInput.className = 'attribute-group-hidden';
        hiddenInput.value = '';
        div.appendChild(hiddenInput);

        container.appendChild(div);

        const selectInput = div.querySelector('.attribute-name-input');
        const usedAttributes = Object.values(attributeGroups).map(g => g.name).filter(Boolean);

        const options = Object.keys(ATTRIBUTE_SUGGESTIONS).map(name => ({
            value: name,
            text: name,
            group: "suggested",
            disabled: usedAttributes.includes(name)
        }));

        const tomSelect = new TomSelect(selectInput, {
            create: true,
            maxItems: 1,
            mode: 'input',
            options,
            placeholder: "Chọn hoặc nhập phân loại",
            optgroups: [{ value: "suggested", label: "Giá trị đề xuất" }],
            optgroupField: 'group',
            render: {
                option: (data, escape) => {
                    const style = data.disabled ? 'opacity: 0.5; pointer-events: none;' : '';
                    return `<div style="${style}">${escape(data.text)}</div>`;
                },
                optgroup_header: (data, escape) => `<div class="text-muted small px-2 py-1">${escape(data.label)}</div>`
            },
            onChange(value) {
                attributeGroups[groupId].name = value;
                div.querySelector('.attribute-group-hidden').value = value;
                attributeGroups[groupId].values = [];
                renderAttributeTags(groupId);
                generateCombinations();
                refreshAllAttributeSelects();
            }
        });

        renderAttributeTags(groupId);
        attributeGroupIndex++;
        updateAddGroupButtonText();
        document.getElementById('variant-section').style.display = 'block';
    }

    function renderAttributeTags(groupId) {
        const container = document.getElementById(`${groupId}_tags`);
        container.innerHTML = '';

        const selectedValues = attributeGroups[groupId].values;

        const list = document.createElement('div');
        list.className = 'd-flex flex-wrap gap-2 align-items-center';
        list.id = `${groupId}_sortable`;

        selectedValues.forEach(val => {
            const tag = document.createElement('div');
            tag.className = 'd-inline-flex align-items-center bg-white border rounded p-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm border-0 p-0';
            input.style.width = 'auto';
            input.style.minWidth = '60px';
            input.style.background = 'transparent';
            input.value = val;
            input.readOnly = true;

            const moveIcon = document.createElement('i');
            moveIcon.className = 'bi bi-arrows-move ms-2 text-muted cursor-move';

            const removeIcon = document.createElement('i');
            removeIcon.className = 'bi bi-trash text-danger ms-2 cursor-pointer';
            removeIcon.setAttribute('onclick', `removeTag('${groupId}', '${val}')`);

            tag.appendChild(input);
            tag.appendChild(moveIcon);
            tag.appendChild(removeIcon);

            list.appendChild(tag);
        });

        container.appendChild(list);

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Nhập tuỳ chọn...';
        container.appendChild(input);

        if (input.tomselect) input.tomselect.destroy();

        const suggestions = getSuggestedValues(groupId);

        const ts = new TomSelect(input, {
            create: true,
            maxItems: 1,
            persist: false,
            options: [],
            render: {
                option: (data, escape) => {
                    const style = data.disabled ? 'opacity: 0.4; pointer-events: none;' : '';
                    return `<div style="${style}">${escape(data.text)}</div>`;
                }
            },
            onItemAdd(value) {
                if (!attributeGroups[groupId].values.includes(value)) {
                    attributeGroups[groupId].values.push(value);
                    renderAttributeTags(groupId);
                    generateCombinations();
                }
                ts.clear();
            },
            onBlur() {
                const value = ts.getValue().trim();
                if (value && !attributeGroups[groupId].values.includes(value)) {
                    ts.addOption({ value, text: value });
                    ts.addItem(value);
                } else {
                    ts.clear();
                }
            }
        });

        ts.clearOptions();
        ts.addOptions(suggestions);
        ts.refreshOptions(false);

        Sortable.create(list, {
            animation: 150,
            handle: '.bi-arrows-move',
            onEnd: function () {
                const items = list.querySelectorAll('input[readonly]');
                attributeGroups[groupId].values = [...items].map(i => i.value.trim());
                generateCombinations();
            }
        });
    }

    function getSuggestedValues(groupId) {
        const currentName = attributeGroups[groupId]?.name;
        const usedValues = new Set();
        Object.keys(attributeGroups).forEach(id => {
            if (id !== groupId) {
                attributeGroups[id].values.forEach(v => usedValues.add(v));
            }
        });
        return (ATTRIBUTE_SUGGESTIONS[currentName] || []).map(val => ({
            value: val,
            text: val,
            disabled: usedValues.has(val)
        }));
    }

    function removeAttributeGroup(id) {
        delete attributeGroups[id];
        document.getElementById(id)?.remove();
        updateAddGroupButtonText();
        generateCombinations();
        refreshAllAttributeSelects();
    }

    function removeTag(groupId, value) {
        attributeGroups[groupId].values = attributeGroups[groupId].values.filter(v => v !== value);
        renderAttributeTags(groupId);
        generateCombinations();
    }

    function updateAddGroupButtonText() {
        const count = Object.keys(attributeGroups).length;
        const btn = document.getElementById('add-group-btn');
        if (btn) btn.innerText = `+ Thêm nhóm phân loại mới ${count + 1}`;
    }

    function refreshAllAttributeSelects() {
        const used = Object.values(attributeGroups).map(g => g.name).filter(Boolean);
        document.querySelectorAll('.attribute-name-input').forEach(input => {
            const ts = input.tomselect;
            const currentVal = ts.getValue();
            ts.clearOptions();
            const options = Object.keys(ATTRIBUTE_SUGGESTIONS).map(name => ({
                value: name,
                text: name,
                group: "suggested",
                disabled: used.includes(name) && name !== currentVal
            }));
            ts.addOptions(options);
            ts.refreshOptions(false);
        });
    }

    function generateCombinations() {
        const groupIds = Object.keys(attributeGroups);
        const tbody = document.getElementById('variant-list');
        const section = document.getElementById('variant-section');

        if (groupIds.length === 0 || groupIds.some(id => attributeGroups[id].values.length === 0)) {
            tbody.innerHTML = '';
            section.style.display = 'none';
            return;
        }

        section.style.display = 'block';
        tbody.innerHTML = '';

        const valueLists = groupIds.map(id => attributeGroups[id].values);
        const combinations = cartesian(valueLists);

        const headerRow = document.createElement('tr');
        headerRow.id = 'variant-header-inputs';
        headerRow.innerHTML = `
            <td class="fw-semibold text-muted">Áp dụng cho tất cả</td>
            <td><input type="number" class="form-control form-control-sm" id="apply-price" placeholder="₫ Nhập vào"></td>
            <td><input type="number" class="form-control form-control-sm" id="apply-quantity" placeholder="0"></td>
            <td><input type="text" class="form-control form-control-sm" id="apply-sku" placeholder="Nhập vào"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="applyToAllVariants()">Áp dụng</button></td>
        `;
        tbody.appendChild(headerRow);

        combinations.forEach((combo, i) => {
            const label = combo.join(' / ');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${label}<input type="hidden" name="variants[${i}][attributes]" value="${label}"></td>
                <td><input type="number" name="variants[${i}][price]" class="form-control" required></td>
                <td><input type="number" name="variants[${i}][quantity]" class="form-control" required></td>
                <td><input type="text" name="variants[${i}][sku]" class="form-control"></td>
                <td></td>
            `;
            tbody.appendChild(row);
        });
    }

    function applyToAllVariants() {
        const price = document.getElementById('apply-price').value;
        const quantity = document.getElementById('apply-quantity').value;
        const sku = document.getElementById('apply-sku').value;
        const rows = document.querySelectorAll('#variant-list tr');
        rows.forEach((row, i) => {
            if (row.id === 'variant-header-inputs') return;
            if (price) row.querySelector(`input[name^="variants["][name$="[price]"]`).value = price;
            if (quantity) row.querySelector(`input[name^="variants["][name$="[quantity]"]`).value = quantity;
            if (sku) row.querySelector(`input[name^="variants["][name$="[sku]"]`).value = sku;
        });
    }

    function cartesian(arrays) {
        return arrays.reduce((acc, curr) => acc.flatMap(a => curr.map(b => a.concat(b))), [[]]);
    }
</script> -->


<!-- <script>
    // == BEGIN SCRIPT: product_form_script.js ==

    // Dùng prefix để tránh xung đột với JS khác
    // Script này gắn với form có ID là "product-form"
    document.addEventListener("DOMContentLoaded", function() {
        let pfAttributeIndex = 0;
        let pfVariantIndex = 0;

        const attributeGroupsInput = document.querySelector("#pf_attributeGroups");
        const variantsContainer = document.querySelector("#pf_variants_container");
        const attributeGroupWrapper = document.querySelector("#pf_attribute_groups_wrapper");

        // Thêm nhóm phân loại
        document.querySelector("#pf_add_attribute_group").addEventListener("click", function() {
            const groupNameInput = document.querySelector("#pf_attribute_group_input");
            const groupName = groupNameInput.value.trim();
            if (!groupName) return;

            const groupId = `pf_group_${pfAttributeIndex++}`;

            const groupDiv = document.createElement("div");
            groupDiv.className = "mb-2";
            groupDiv.innerHTML = `
            <label class="form-label">${groupName}</label>
            <input type="text" class="form-control pf-attribute-option" data-group-name="${groupName}" placeholder="Nhập các giá trị, ngăn cách bởi dấu phẩy">
        `;
            attributeGroupWrapper.appendChild(groupDiv);

            groupNameInput.value = "";
        });

        // Sinh tổ hợp biến thể
        document.querySelector("#pf_generate_variants").addEventListener("click", function() {
            variantsContainer.innerHTML = "";
            pfVariantIndex = 0;

            const groupValuesMap = {};
            const groupNames = [];

            document.querySelectorAll(".pf-attribute-option").forEach(input => {
                const name = input.dataset.groupName;
                const values = input.value.split(',').map(v => v.trim()).filter(v => v);
                if (values.length > 0) {
                    groupValuesMap[name] = values;
                    groupNames.push(name);
                }
            });

            // Lưu group name vào hidden input (sử dụng input name="attributeGroups[]")
            let attrWrapper = document.querySelector("#pf_attributeGroups_wrapper");
            attrWrapper.innerHTML = "";
            groupNames.forEach(name => {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = "attributeGroups[]";
                hidden.value = name;
                attrWrapper.appendChild(hidden);
            });

            // Hàm tổ hợp Cartesian
            function cartesian(arrays) {
                return arrays.reduce((a, b) => a.flatMap(d => b.map(e => d.concat(e))), [
                    []
                ]);
            }

            const combinations = cartesian(Object.values(groupValuesMap));
            combinations.forEach(combo => {
                const attrStr = combo.join(" / ");
                const variantHtml = `
                <div class="border rounded p-3 mb-3">
                    <input type="hidden" name="variants[${pfVariantIndex}][attributes]" value="${attrStr}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Thuộc tính</label>
                            <input type="text" class="form-control" value="${attrStr}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá bán</label>
                            <input type="number" class="form-control" name="variants[${pfVariantIndex}][price]" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Số lượng</label>
                            <input type="number" class="form-control" name="variants[${pfVariantIndex}][quantity]" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SKU (tuỳ chọn)</label>
                            <input type="text" class="form-control" name="variants[${pfVariantIndex}][sku]">
                        </div>
                    </div>
                </div>
            `;
                variantsContainer.insertAdjacentHTML("beforeend", variantHtml);
                pfVariantIndex++;
            });
        });
    });

    // == END SCRIPT ==

    // == END SCRIPT ==
</script> -->

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
        "Màu sắc": ["Đỏ", "Cam", "Vàng", "Xanh lá", "Xanh dương", "Tím", "Hồng", "Xanh quân đội", "Xanh dương nhạt"],
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
            <td></td>
        `;
            tbody.appendChild(row);
        });
    }

    function pfApplyToAllVariants() {
        const price = document.getElementById('pf_apply_price').value;
        const quantity = document.getElementById('pf_apply_quantity').value;
        const sku = document.getElementById('pf_apply_sku').value;

        const rows = document.querySelectorAll('#pf_variant_list tr');
        rows.forEach((row) => {
            if (row.querySelector('td')?.textContent.includes('Áp dụng cho tất cả')) return;

            if (price) row.querySelector(`input[name$="[price]"]`).value = price;
            if (quantity) row.querySelector(`input[name$="[quantity]"]`).value = quantity;
            if (sku) row.querySelector(`input[name$="[sku]"]`).value = sku;
        });
    }


    function pfCartesian(arrays) {
        return arrays.reduce((a, b) => a.flatMap(d => b.map(e => d.concat(e))), [
            []
        ]);
    }
</script>




<!--end::Content wrapper-->

@endsection