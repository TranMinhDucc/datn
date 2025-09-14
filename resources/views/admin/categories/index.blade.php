@extends('layouts.admin')

@section('title', 'Danh mục')
@section('content')
<div class="d-flex flex-column flex-column-fluid">

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Danh mục sản phẩm
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="../../../index.html" class="text-muted text-hover-primary">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Danh mục sản phẩm</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!--begin::Card-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-13"
                                placeholder="Tìm danh mục..." />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-category-table-toolbar="base">
                            <div class="w-150px me-3">
                                <select id="status-filter" class="form-select form-select-solid" data-control="select2"
                                    data-hide-search="true" data-placeholder="Trạng thái">
                                    <option value="all">Tất cả</option>
                                    <option value="active">Hoạt động</option>
                                    <option value="deleted">Đã xóa</option>
                                </select>
                            </div>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                Thêm Danh Mục
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_category_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px text-center">STT</th>
                                    <th class="min-w-80px text-center">Ảnh</th>
                                    <th class="min-w-160px">Tên danh mục</th>
                                    <th class="min-w-140px text-center">Danh mục cha</th>
                                    <th class="min-w-180px">Mô tả</th>
                                    <th class="min-w-100px text-center">Trạng thái</th>
                                    <th class="text-end min-w-100px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600" id="category-table-body">

                                @php
                                    $stt = 1;

                                    function renderRows($categories, $parentId = null, $level = 0, &$stt = 1, $categoryMap = [], $breadcrumb = [])
                                    {
                                        foreach ($categories->where('parent_id', $parentId) as $category) {
                                            $hasChildren = $categories->where('parent_id', $category->id)->count() > 0;
                                            $isParent = $level === 0;
                                            $breadcrumbText = implode(' > ', [...$breadcrumb, $category->name]);
                                @endphp

                                <tr data-id="{{ $category->id }}" 
                                    data-parent="{{ $parentId }}"
                                    data-level="{{ $level }}"
                                    data-name="{{ strtolower($category->name) }}"
                                    data-status="{{ $category->trashed() ? 'deleted' : 'active' }}"
                                    data-breadcrumb="{{ strtolower($breadcrumbText) }}"
                                    class="category-row parent-{{ $parentId ?? 'root' }} {{ $isParent ? 'category-parent' : 'child-row' }}"
                                    @if(!$isParent) style="display:none" @endif>
                                    
                                    <!-- STT -->
                                    <td class="text-center">
                                        <span class="row-number">{{ $isParent ? $stt++ : '' }}</span>
                                    </td>

                                    <!-- Ảnh -->
                                    <td class="text-center">
                                        @if ($category->image)
                                            <div class="symbol symbol-40px">
                                                <img src="{{ asset('storage/' . $category->image) }}" 
                                                     alt="{{ $category->name }}"
                                                     class="symbol-label object-fit-cover">
                                            </div>
                                        @else
                                            <div class="symbol symbol-40px">
                                                <div class="symbol-label bg-light-secondary">
                                                    <i class="ki-duotone ki-image fs-2 text-secondary"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Tên danh mục -->
                                    <td title="{{ $breadcrumbText }}">
                                        <div class="d-flex align-items-center">
                                            <div class="tree-indent" style="min-width: {{ $level * 25 }}px;"></div>
                                            @if ($hasChildren)
                                                <button type="button" 
                                                        class="btn btn-sm btn-icon btn-light toggle-btn me-2"
                                                        data-category-id="{{ $category->id }}"
                                                        data-expanded="false">
                                                    <i class="fa-solid fa-caret-right fs-4"></i>
                                                </button>
                                            @else
                                                <div class="btn btn-sm btn-icon me-2" style="visibility: hidden;"></div>
                                            @endif
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-gray-800">{{ $category->name }}</span>
                                                @if($level > 0)
                                                    <small class="text-muted">{{ implode(' › ', array_slice(explode(' > ', $breadcrumbText), 0, -1)) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Danh mục cha -->
                                    <td class="text-center">
                                        @if ($category->parent_id && isset($categoryMap[$category->parent_id]))
                                            <span class="badge badge-light-primary">{{ $categoryMap[$category->parent_id] }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <!-- Mô tả -->
                                    <td>
                                        @if($category->description)
                                            <span class="text-gray-600">{{ Str::limit($category->description, 50) }}</span>
                                        @else
                                            <span class="text-muted">Không có mô tả</span>
                                        @endif
                                    </td>

                                    <!-- Trạng thái -->
                                    <td class="text-center">
                                        @if ($category->trashed())
                                            <span class="badge badge-light-danger">
                                                <i class="ki-duotone ki-cross-circle fs-7 me-1"></i>Đã xóa
                                            </span>
                                        @else
                                            <span class="badge badge-light-success">
                                                <i class="ki-duotone ki-check-circle fs-7 me-1"></i>Hoạt động
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Thao tác -->
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" 
                                               class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                               data-kt-menu-trigger="click" 
                                               data-kt-menu-placement="bottom-end">
                                                Hành động
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded
                                                        menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                                data-kt-menu="true">

                                                @if ($category->trashed())
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.categories.restore', $category->id) }}"
                                                            method="POST" 
                                                            onsubmit="return confirm('Khôi phục danh mục này?');"
                                                            class="restore-form">
                                                            @csrf
                                                            <button type="submit" class="menu-link px-3 w-100 text-start border-0 bg-transparent">
                                                                <i class="ki-duotone ki-arrows-circle fs-6 me-2"></i>Khôi phục
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                                           class="menu-link px-3">
                                                            <i class="ki-duotone ki-pencil fs-6 me-2"></i>Sửa
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.categories.show', $category) }}"
                                                           class="menu-link px-3">
                                                            <i class="ki-duotone ki-eye fs-6 me-2"></i>Xem
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form class="delete-category-form"
                                                            action="{{ route('admin.categories.destroy', $category) }}"
                                                            method="POST" 
                                                            data-category-name="{{ $category->name }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="menu-link px-3 w-100 text-start border-0 bg-transparent text-danger">
                                                                <i class="ki-duotone ki-trash fs-6 me-2"></i>Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @php
                                            renderRows($categories, $category->id, $level + 1, $stt, $categoryMap, [...$breadcrumb, $category->name]);
                                        }
                                    }
                                    $categoryMap = $categories->pluck('name', 'id')->toArray();
                                    renderRows($categories, null, 0, $stt, $categoryMap);
                                @endphp

                            </tbody>
                        </table>
                    </div>
                    
                    <!-- No results message -->
                    <div id="no-results" class="text-center py-10" style="display: none;">
                        <div class="text-gray-500 fs-4 mb-3">
                            <i class="ki-duotone ki-search-list fs-1"></i>
                        </div>
                        <div class="text-gray-600 fs-6">
                            Không tìm thấy danh mục nào phù hợp
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>
    <!--end::Content-->
</div>

<style>
/* Table layout fixed để tránh xô lệch */
#kt_category_table {
    table-layout: fixed;
    width: 100%;
}

/* Tree indentation */
.tree-indent {
    display: inline-block;
    flex-shrink: 0;
}

/* Toggle button */
.toggle-btn {
    transition: transform 0.2s ease;
    width: 32px;
    height: 32px;
    flex-shrink: 0;
}

.toggle-btn[data-expanded="true"] i {
    transform: rotate(90deg);
}

/* Category name cell */
.category-name-cell {
    width: 200px;
    max-width: 200px;
}

.category-name-content {
    overflow: hidden;
}

.category-name-content .text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Description cell */
.description-cell {
    width: 150px;
    max-width: 150px;
}

/* Parent category badge */
.badge.text-truncate {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}

/* Row styling */
.category-row {
    transition: all 0.3s ease;
}

.category-row.highlight {
    background-color: rgba(255, 193, 7, 0.1);
}

/* Symbol styling */
.symbol {
    flex-shrink: 0;
}

.symbol-label {
    border-radius: 6px;
}

/* Menu styling */
.menu-link {
    display: flex;
    align-items: center;
}

/* Fixed column widths */
th:nth-child(1), td:nth-child(1) { width: 50px; }
th:nth-child(2), td:nth-child(2) { width: 80px; }
th:nth-child(3), td:nth-child(3) { width: 200px; }
th:nth-child(4), td:nth-child(4) { width: 120px; }
th:nth-child(5), td:nth-child(5) { width: 150px; }
th:nth-child(6), td:nth-child(6) { width: 100px; }
th:nth-child(7), td:nth-child(7) { width: 120px; }

/* Responsive adjustments */
@media (max-width: 992px) {
    .category-name-cell {
        width: 180px;
        max-width: 180px;
    }
    
    .description-cell {
        width: 130px;
        max-width: 130px;
    }
}

@media (max-width: 768px) {
    .category-name-cell {
        width: 160px;
        max-width: 160px;
    }
    
    .description-cell {
        width: 120px;
        max-width: 120px;
    }
    
    .tree-indent {
        width: 15px !important;
    }
}
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const tableBody = document.getElementById('category-table-body');
    const noResults = document.getElementById('no-results');
    
    let allRows = [];
    let expandedCategories = new Set();
    
    // Initialize
    function init() {
        allRows = Array.from(document.querySelectorAll('.category-row'));
        updateRowNumbers();
        bindEvents();
    }
    
    // Bind events
    function bindEvents() {
        // Search functionality
        searchInput.addEventListener('input', debounce(handleSearch, 300));
        
        // Status filter
        statusFilter.addEventListener('change', handleFilter);
        
        // Toggle buttons
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const categoryId = this.dataset.categoryId;
                const isExpanded = this.dataset.expanded === 'true';
                toggleCategory(categoryId, !isExpanded, this);
            });
        });
        
        // Delete forms
        document.querySelectorAll('.delete-category-form').forEach(form => {
            form.addEventListener('submit', handleDelete);
        });
    }
    
    // Toggle category children
    function toggleCategory(parentId, expand, toggleBtn) {
        const childRows = document.querySelectorAll(`tr[data-parent="${parentId}"]`);
        const icon = toggleBtn.querySelector('i');
        
        toggleBtn.dataset.expanded = expand;
        
        if (expand) {
            expandedCategories.add(parentId);
            childRows.forEach(row => {
                if (shouldShowRow(row)) {
                    row.style.display = 'table-row';
                }
            });
        } else {
            expandedCategories.delete(parentId);
            childRows.forEach(row => {
                row.style.display = 'none';
                // Collapse nested children
                const childToggle = row.querySelector('.toggle-btn');
                if (childToggle && childToggle.dataset.expanded === 'true') {
                    toggleCategory(row.dataset.id, false, childToggle);
                }
            });
        }
        
        updateRowNumbers();
    }
    
    // Check if row or any of its descendants should be visible
    function shouldShowRow(row, isChildCheck = false) {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        
        // Check if row itself matches search and status
        let rowMatches = true;
        
        // Status filter
        if (statusValue !== 'all' && row.dataset.status !== statusValue) {
            rowMatches = false;
        }
        
        // Search filter
        if (searchTerm) {
            const rowName = row.dataset.name || '';
            const rowBreadcrumb = row.dataset.breadcrumb || '';
            if (!rowName.includes(searchTerm) && !rowBreadcrumb.includes(searchTerm)) {
                rowMatches = false;
            }
        }
        
        // Check descendants (children, grandchildren, etc.)
        let hasMatchingDescendant = false;
        const childRows = document.querySelectorAll(`tr[data-parent="${row.dataset.id}"]`);
        childRows.forEach(child => {
            if (shouldShowRow(child, true)) {
                hasMatchingDescendant = true;
            }
        });
        
        // A row should be shown if:
        // 1. It matches the search term and status, OR
        // 2. Any of its descendants match, OR
        // 3. It's a child row and its parent is expanded
        if (rowMatches || hasMatchingDescendant) {
            return true;
        }
        
        // If this is a child row, check if its parent is expanded
        if (row.dataset.parent && row.dataset.parent !== 'null' && row.dataset.parent !== 'root') {
            const parentRow = document.querySelector(`tr[data-id="${row.dataset.parent}"]`);
            if (parentRow && parentRow.style.display !== 'none' && expandedCategories.has(row.dataset.parent)) {
                return rowMatches; // Show child only if it matches filters
            }
        }
        
        return false;
    }
    
    // Handle search
    function handleSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        allRows.forEach(row => {
            const shouldShow = shouldShowRow(row);
            
            if (shouldShow) {
                row.style.display = 'table-row';
                visibleCount++;
                
                // If searching, expand parent categories to show matching children
                if (searchTerm) {
                    expandParentsForRow(row);
                }
                
                // Highlight matching text
                if (searchTerm) {
                    const rowName = row.dataset.name || '';
                    const rowBreadcrumb = row.dataset.breadcrumb || '';
                    if (rowName.includes(searchTerm) || rowBreadcrumb.includes(searchTerm)) {
                        row.classList.add('highlight');
                    } else {
                        row.classList.remove('highlight');
                    }
                } else {
                    row.classList.remove('highlight');
                }
            } else {
                row.style.display = 'none';
                row.classList.remove('highlight');
            }
        });
        
        // Show/hide no results message
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        tableBody.style.display = visibleCount === 0 ? 'none' : 'table-row-group';
        
        updateRowNumbers();
    }
    
    // Handle status filter
    function handleFilter() {
        handleSearch(); // Reapply all filters
    }
    
    // Expand parent categories for a row
    function expandParentsForRow(row) {
        let parentId = row.dataset.parent;
        
        while (parentId && parentId !== 'null' && parentId !== 'root') {
            const parentRow = document.querySelector(`tr[data-id="${parentId}"]`);
            if (parentRow) {
                parentRow.style.display = 'table-row'; // Ensure parent is visible
                const toggleBtn = parentRow.querySelector('.toggle-btn');
                if (toggleBtn && toggleBtn.dataset.expanded !== 'true') {
                    toggleCategory(parentId, true, toggleBtn);
                }
                parentId = parentRow.dataset.parent;
            } else {
                break;
            }
        }
    }
    
    // Update row numbers for visible parent rows
    function updateRowNumbers() {
        let counter = 1;
        document.querySelectorAll('.category-row').forEach(row => {
            const numberSpan = row.querySelector('.row-number');
            if (row.style.display !== 'none' && row.dataset.level === '0') {
                numberSpan.textContent = counter++;
            } else {
                numberSpan.textContent = '';
            }
        });
    }
    
    // Handle delete confirmation
    function handleDelete(e) {
        e.preventDefault();
        const form = e.target;
        const categoryName = form.dataset.categoryName || 'danh mục này';
        
        Swal.fire({
            title: 'Bạn chắc chắn?',
            text: `Xóa danh mục "${categoryName}"? Nếu có sản phẩm đang hoạt động thuộc danh mục, tất cả sẽ bị ẩn.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            reverseButtons: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Initialize the page
    init();
});
</script>
@endsection