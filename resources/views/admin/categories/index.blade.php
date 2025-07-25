@extends('layouts.admin')

@section('title', 'Danh mục')
@section('content')
<div class="d-flex flex-column flex-column-fluid">

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Danh mục sản phẩm
                </h1>
                <!--end::Title-->


                <!--begin::Breadcrumb-->

            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Filter menu-->
                <div class="m-0">
                    <!--begin::Menu toggle-->

                    <!--end::Menu toggle-->



                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                        id="kt_menu_683db6e91bd8d">
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
                                        data-dropdown-parent="#kt_menu_683db6e91bd8d" data-allow-clear="true">
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
            <!--begin::Categories-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->

                    <!--end::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Add category-->
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            Thêm Danh Mục
                        </a>
                        <!--end::Add category-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">

                    <!--begin::Table-->
                    <div style="overflow-x: auto;">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" style="min-width: 1300px;"
                            id="kt_ecommerce_categories_table">
                            <thead>
                                <tr>
                                    <th class="min-w-50px">STT</th>
                                    <th class="min-w-80px">Ảnh</th>
                                    <th class="min-w-160px">Tên danh mục</th>
                                    <th class="min-w-140px">Danh mục cha</th>
                                    <th class="min-w-180px">Mô tả</th>
                                    <th class="min-w-100px text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $stt = 1;

                                function renderRows($categories, $parentId = null, $level = 0, &$stt = 1, $categoryMap = [], $breadcrumb = [])
                                {
                                foreach ($categories->where('parent_id', $parentId) as $category) {
                                $hasChildren = $categories->where('parent_id', $category->id)->count() > 0;
                                $isParent = $level === 0;
                                $rowClass = $isParent ? 'category-parent' : 'child-row';
                                $breadcrumbText = implode(' > ', [...$breadcrumb, $category->name]);

                                echo '<tr data-id="' . $category->id . '" data-parent="' . $parentId . '" class="parent-' . ($parentId ?? 'root') . ' ' . $rowClass . '"' . ($isParent ? '' : ' style="display:none"') . '>';

                                    // Cột STT – chỉ cấp 1
                                    echo '<td>';
                                        if ($isParent)
                                        echo $stt++;
                                        echo '</td>';

                                    // Ảnh
                                    echo '<td>';
                                        if ($category->image) {
                                        echo '<img src="' . asset(" storage/" . $category->image) . '" width="40" height="40" style="object-fit:cover;border-radius:6px;">';
                                        } else {
                                        echo '<span class="text-muted">Không có</span>';
                                        }
                                        echo '</td>';

                                    // Tên danh mục
                                    echo '<td title="' . e($breadcrumbText) . '">';
                                        if ($hasChildren) {
                                        echo '<span class="toggle-btn" onclick="toggleChildren(' . $category->id . ', this)">▸</span> ';
                                        } else {
                                        echo '<span style="display:inline-block; width: 14px;"></span> ';
                                        }
                                        echo str_repeat(' ', $level) . ' ' . $category->name;
                                        echo '</td>';

                                    // Danh mục cha
                                    echo '<td>';
                                        if ($category->parent_id && isset($categoryMap[$category->parent_id])) {
                                        echo $categoryMap[$category->parent_id];
                                        } else {
                                        echo '<span class="text-muted">Không có</span>';
                                        }
                                        echo '</td>';

                                    // Mô tả
                                    echo '<td>' . ($category->description ?? 'Không có mô tả') . '</td>';

                                    // Hành động
                                    echo '<td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Hành động
                                            <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                        </a>

                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="' . route('admin.categories.edit', $category) . '" class="menu-link px-3">Sửa</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="' . route('admin.categories.show', $category) . '" class="menu-link px-3">Xem</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <form action="' . route('admin.categories.destroy', $category) . '" method="POST" onsubmit="return confirm(\'Bạn chắc chắn muốn xóa?\');">
                                                    ' . csrf_field() . method_field('DELETE') . '
                                                    <button type="submit" class="menu-link px-3">Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>';

                                    echo '</tr>';

                                renderRows($categories, $category->id, $level + 1, $stt, $categoryMap, [...$breadcrumb, $category->name]);
                                }
                                }

                                $categoryMap = $categories->pluck('name', 'id')->toArray();
                                renderRows($categories, null, 0, $stt, $categoryMap);
                                @endphp
                            </tbody>
                        </table>

                        <script>
                            function toggleChildren(parentId, toggleIcon) {
                                const rows = document.querySelectorAll('tr[data-parent="' + parentId + '"]');
                                const isOpen = toggleIcon.textContent.trim() === '▾';

                                toggleIcon.textContent = isOpen ? '▸' : '▾';

                                rows.forEach(row => {
                                    if (isOpen) {
                                        row.style.display = 'none';

                                        // Nếu có con, đóng tiếp
                                        const childToggle = row.querySelector('.toggle-btn');
                                        if (childToggle && childToggle.textContent.trim() === '▾') {
                                            childToggle.textContent = '▸';
                                            toggleChildren(row.dataset.id, childToggle);
                                        }
                                    } else {
                                        row.style.display = 'table-row';
                                    }
                                });
                            }
                        </script>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

</div>
<!--end::Content wrapper-->
@endsection