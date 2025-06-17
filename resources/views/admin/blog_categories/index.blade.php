@extends('layouts.admin')

@section('title', 'Chuyên mục Blog')
@section('content')


<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
					<div class="d-flex align-items-center position-relative my-1">
						<i class="fas fa-search fs-3 position-absolute ms-5"></i>
						<input type="text" name="search" id="searchInput"
							class="form-control form-control-solid w-250px ps-13"
							placeholder="Tìm kiếm chuyên mục..." value="{{ request('search') }}" />
					</div>
					<!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-blog-category-table-toolbar="base">
                        <!--begin::Add category-->
                        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus fs-2"></i></i>Thêm chuyên mục
                        </a>
                        <!--end::Add category-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!-- @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                        <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-success">Thành công</h4>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif -->

                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_blog_categories_table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">Tên chuyên mục</th>
                            <th class="min-w-125px">Slug</th>
                            <th class="min-w-125px">Mô tả</th>
                            <th class="min-w-100px">Số bài viết</th>
                            <th class="min-w-100px">Ngày tạo</th>
                            <th class="text-end min-w-100px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @forelse($categories as $category)
                        <tr>
                            <td class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <div class="symbol-label fs-3 bg-light-primary text-primary">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold text-hover-primary fs-6">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light-info">{{ $category->slug }}</span>
                            </td>
                            <td>
                                <span class="text-gray-600">{{ Str::limit($category->description, 50) ?: 'Không có mô tả' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-primary">{{ $category->blogs_count }} bài</span>
                            </td>
                            <td>{{ $category->created_at->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Thao tác
                                    <i class="fa-solid fa-chevron-down fs-5 ms-1"></i>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('admin.blog-categories.edit', $category) }}" class="menu-link px-3">Sửa</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" onclick="deleteCategory({{ $category->id }})">Xóa</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fa-solid fa-file-circle-xmark fs-5x text-muted mb-5"></i>
                                    <span class="text-muted fs-4">Chưa có chuyên mục nào</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--end::Table-->

                <!--begin::Pagination-->
                <div class="d-flex flex-stack flex-wrap pt-10">
                    <div class="fs-6 fw-semibold text-gray-700">
                        Hiển thị {{ $categories->firstItem() ?? 0 }} đến {{ $categories->lastItem() ?? 0 }}
                        trong tổng số {{ $categories->total() }} kết quả
                    </div>
                    {{ $categories->links() }}
                </div>
                <!--end::Pagination-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
<!-- Delete form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection
@push('scripts')
<script>
    function deleteCategory(id) {
        Swal.fire({
            text: "Bạn có chắc chắn muốn xóa chuyên mục này?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Có, xóa!",
            cancelButtonText: "Hủy",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function(result) {
            if (result.value) {
                const form = document.getElementById('delete-form');
                form.action = '/admin/blog-categories/' + id;
                form.submit();
            }
        });
    }
    let searchInput = document.getElementById('searchInput');
    let timer;

    document.addEventListener('DOMContentLoaded', function() {
        console.log("Script đã chạy");

        const searchInput = document.getElementById('searchInput');
        let timer;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    let search = searchInput.value.trim();
                    let params = new URLSearchParams(window.location.search);

                    if (search.length) {
                        params.set('search', search);
                    } else {
                        params.delete('search');
                    }

                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                }, 500);
            });
        } else {
            console.warn("Không tìm thấy phần tử #searchInput");
        }
    });
</script>
@endpush