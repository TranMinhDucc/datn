@extends('layouts.admin')

@section('title', 'Menu')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                    Danh sách Menu
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                    + Thêm Menu
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body pt-0">
                    <div style="overflow-x: auto;">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" style="min-width: 1100px;">
                            <thead>
                                <tr>
                                    <th class="min-w-50px">ID</th>
                                    <th class="min-w-200px">Tiêu đề</th>
                                    <th class="min-w-250px">URL</th>
                                    <th class="min-w-100px">Vị trí</th>
                                    <th class="min-w-100px">Ngôn ngữ</th>
                                    <th class="min-w-100px text-center">Hiển thị</th>
                                    <th class="min-w-150px">Ngày tạo</th>
                                    <th class="min-w-100px text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $stt = 1;
                                    function renderMenu($menus, $parentId = null, $level = 0, &$stt = 1) {
                                        foreach ($menus->where('parent_id', $parentId) as $menu) {
                                            $hasChildren = $menus->where('parent_id', $menu->id)->count() > 0;
                                            $isParent = $level === 0;
                                            echo '<tr data-id="'.$menu->id.'" data-parent="'.$parentId.'" class="parent-'.($parentId ?? 'root').'"'.($isParent?'':' style="display:none"').'>';

                                                // ID
                                                echo '<td>'.$stt++.'</td>';

                                                // Tiêu đề + toggle
                                                echo '<td>';
                                                    if ($hasChildren) {
                                                        echo '<span class="toggle-btn" onclick="toggleChildren('.$menu->id.', this)">▸</span> ';
                                                    } else {
                                                        echo '<span style="display:inline-block;width:14px;"></span> ';
                                                    }
                                                    echo str_repeat('&nbsp;&nbsp;', $level).e($menu->title);
                                                echo '</td>';

                                                echo '<td>'.e($menu->url).'</td>';
                                                echo '<td>'.e($menu->position).'</td>';
                                                echo '<td>'.e($menu->language).'</td>';
                                                echo '<td class="text-center"><input type="checkbox" class="toggle-active" data-id="'.$menu->id.'" '.($menu->active ? 'checked' : '').'></td>';
                                                echo '<td>'.($menu->created_at ? $menu->created_at->format('Y-m-d H:i') : '').'</td>';

                                                // Action
                                               echo '<td class="text-end">
    <div class="dropdown">
        <button class="btn btn-sm btn-light btn-active-light-primary"
            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            Actions <i class="fa fa-chevron-down ms-1"></i>
        </button>
        <div class="menu menu-sub menu-sub-dropdown w-125px" data-kt-menu="true">
            <!-- Edit -->
            <div class="menu-item px-3">
                <a href="'.route('admin.menus.edit', $menu).'" class="menu-link px-3">Sửa</a>
            </div>

            <!-- Delete -->
            <div class="menu-item px-3">
                <form action="'.route('admin.menus.destroy', $menu).'" method="POST"
                    onsubmit="return confirm(\'Bạn có chắc chắn muốn xóa menu này không?\')">
                    '.csrf_field().method_field('DELETE').'
                    <button type="submit" class="menu-link px-3 text-primary w-100" style="background: none; border: none;">
                        Xoá
                    </button>
                </form>
            </div>
        </div>
    </div>
</td>';


                                            renderMenu($menus, $menu->id, $level+1, $stt);
                                        }
                                    }
                                    renderMenu($menus);
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

                            document.querySelectorAll('.toggle-active').forEach(function (el) {
                                el.addEventListener('change', function () {
                                    const id = this.dataset.id;
                                    const active = this.checked ? 1 : 0;
                                    fetch(`/admin/menus/${id}/toggle-active`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ active })
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
