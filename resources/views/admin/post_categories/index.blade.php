@extends('layouts.admin')

@section('content')
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-1 ">
    <!--begin::Title-->
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
        Quản lý danh mục
    </h1>
    <!--end::Title-->

    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
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
            Danh mục </li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
</div>
<!--end::Page title-->

<!--begin::Actions-->
<div class="d-flex align-items-center gap-2 gap-lg-3 ">
    <!--begin::Filter menu-->
    <div class="m-0">
        <!--begin::Menu toggle-->
        <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-filter fs-6 text-muted me-1"><span class="path1"></span><span class="path2"></span></i>
            Lọc
        </a>
        <!--end::Menu toggle-->

        <!--begin::Menu 1-->
        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_filter">
            <!--begin::Header-->
            <div class="px-7 py-5">
                <div class="fs-5 text-gray-900 fw-bold">Tùy chọn lọc</div>
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
                    <label class="form-label fw-semibold">Trạng thái:</label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <div>
                        <select class="form-select form-select-solid" id="statusFilter">
                            <option value="">Tất cả</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Actions-->
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" onclick="resetFilter()">Đặt lại</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="applyFilter()" data-kt-menu-dismiss="true">Áp dụng</button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Form-->
        </div>
        <!--end::Menu 1-->
    </div>
    <!--end::Filter menu-->

    <!--begin::Search-->
    <div class="position-relative me-3">
        <input type="text" class="form-control form-control-sm form-control-solid w-250px ps-9" placeholder="Tìm kiếm danh mục..." id="searchInput">
        <i class="ki-duotone ki-magnifier fs-6 position-absolute ms-4 top-50 translate-middle-y">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--end::Search-->

    <!--begin::Primary button-->
    <a href="{{ route('admin.post-categories.create') }}" class="btn btn-sm fw-bold btn-primary">
        <i class="ki-duotone ki-plus fs-2 me-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        Tạo mới
    </a>
    <!--end::Primary button-->
</div>
<!--end::Actions-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!--begin::Stats-->
        <div class="row gx-6 gx-xl-9 mb-8">
            <div class="col-lg-3 col-sm-6">
                <div class="card h-100">
                    <div class="card-body p-6">
                        <div class="fs-2hx fw-bold">{{ $stats['total'] }}</div>
                        <div class="fs-4 fw-semibold text-gray-500">Tổng danh mục</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card h-100">
                    <div class="card-body p-6">
                        <div class="fs-2hx fw-bold text-success">{{ $stats['active'] }}</div>
                        <div class="fs-4 fw-semibold text-gray-500">Đang hoạt động</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card h-100">
                    <div class="card-body p-6">
                        <div class="fs-2hx fw-bold text-warning">{{ $stats['inactive'] }}</div>
                        <div class="fs-4 fw-semibold text-gray-500">Không hoạt động</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card h-100">
                    <div class="card-body p-6">
                        <div class="fs-2hx fw-bold text-primary">{{ $stats['total_posts'] }}</div>
                        <div class="fs-4 fw-semibold text-gray-500">Tổng bài viết</div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Stats-->

        <!--begin::Categories Grid-->
        <div class="row g-6 g-xl-9" id="categoriesGrid">
            @foreach($categories as $category)
            <!--begin::Col-->
            <div class="col-md-6 col-xl-4 d-flex category-item" data-status="{{ $category->status }}" data-name="{{ strtolower($category->name) }}" data-title="{{ strtolower($category->title) }}">
                <!--begin::Card-->
                <div class="card border-hover-primary h-100 w-100 d-flex flex-column position-relative card-hover-actions">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-9 flex-shrink-0">
                        <!--begin::Card Title-->
                        <div class="card-title m-0">
                            <div class="symbol symbol-50px w-50px bg-light">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fs-2x text-primary"></i>
                                @else
                                    <i class="ki-duotone ki-category fs-2x text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                @endif
                            </div>
                        </div>
                        <!--end::Card Title-->

                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            @if($category->status == 1)
                                <span class="badge badge-light-success fw-bold px-4 py-3">Hoạt động</span>
                            @else
                                <span class="badge badge-light-warning fw-bold px-4 py-3">Không hoạt động</span>
                            @endif
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end:: Card header-->

                    <!--begin:: Card body-->
                    <div class="card-body p-9 d-flex flex-column flex-grow-1">
                        <!--begin::Name-->
                        <div class="fs-3 fw-bold text-gray-900 mb-3">
                            {{ $category->title }}
                        </div>
                        <!--end::Name-->

                        <!--begin::Slug-->
                        <p class="text-gray-500 fw-semibold fs-6 mb-4">
                            <span class="badge badge-light">{{ $category->slug }}</span>
                        </p>
                        <!--end::Slug-->

                        <!--begin::Name-->
                        <p class="text-gray-600 fw-semibold fs-5 mb-7 flex-grow-1">
                            Tên: {{ $category->name }}
                        </p>
                        <!--end::Name-->

                        <!--begin::Info-->
                        <div class="d-flex flex-wrap mt-auto">
                            <!--begin::Created At-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-3 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">{{ $category->created_at->format('M d, Y') }}</div>
                                <div class="fw-semibold text-gray-500">Ngày tạo</div>
                            </div>
                            <!--end::Created At-->

                            <!--begin::Posts Count-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">{{ $category->posts_count }}</div>
                                <div class="fw-semibold text-gray-500">Bài viết</div>
                            </div>
                            <!--end::Posts Count-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end:: Card body-->

                    <!--begin::Hover Actions Overlay-->
                    <div class="card-hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.7); opacity: 0; transition: all 0.3s ease; z-index: 10;">
                        <div class="d-flex gap-3 align-items-center">
                            <!--begin::Edit Button-->
                            <a href="{{ route('admin.post-categories.edit', $category) }}" class="btn btn-warning btn-sm px-4 py-2 fw-bold d-flex align-items-center" style="height: 38px;">
                                <i class="ki-duotone ki-pencil fs-4 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Sửa
                            </a>
                            <!--end::Edit Button-->

                            <!--begin::Delete Button-->
                            <form method="POST" action="{{ route('admin.post-categories.destroy', $category) }}" style="display: inline; margin: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục \'{{ addslashes($category->title) }}\' không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm px-4 py-2 fw-bold d-flex align-items-center" style="height: 38px;">
                                    <i class="ki-duotone ki-trash fs-4 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                    Xóa
                                </button>
                            </form>
                            <!--end::Delete Button-->
                        </div>
                    </div>
                    <!--end::Hover Actions Overlay-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
            @endforeach
        </div>
        <!--end::Categories Grid-->

        <!-- Phân trang -->
        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const categoryItems = document.querySelectorAll('.category-item');

    categoryItems.forEach(item => {
        const title = item.getAttribute('data-title');
        const name = item.getAttribute('data-name');

        if (title.includes(searchTerm) || name.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Filter functionality
function applyFilter() {
    const statusFilter = document.getElementById('statusFilter').value;
    const categoryItems = document.querySelectorAll('.category-item');

    categoryItems.forEach(item => {
        const status = item.getAttribute('data-status');
        
        if (statusFilter === '' || status === statusFilter) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function resetFilter() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchInput').value = '';
    
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.style.display = 'block';
    });
}
</script>

@endsection

<style>
.card-hover-actions:hover .card-hover-overlay {
    opacity: 1 !important;
}

.card-hover-actions {
    cursor: pointer;
    overflow: hidden;
}

.card-hover-actions:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}
</style>