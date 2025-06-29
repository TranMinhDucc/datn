@extends('layouts.admin')
@section('title', 'Thuộc tính biến thể')
@section('content')

    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 my-0">Attributes</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted"><a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">eCommerce</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Attributes</li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary">
                        <i class="fa-solid fa-filter fs-6 text-muted me-1"></i> Filter
                    </a>
                    <a href="{{ route('admin.variant_attributes.create') }}" class="btn btn-sm fw-bold btn-primary">
                        Create
                    </a>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!--begin::Header tools-->
                <div class="card mb-5">
                    <div class="card-body d-flex flex-wrap gap-4 justify-content-between align-items-center">
                        <form method="GET" action="{{ route('admin.search') }}">
                            <input type="hidden" name="module" value="variant_attributes">
                            <div class="d-flex align-items-center position-relative flex-grow-1">
                                <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Search Attribute..." />
                            </div>
                        </form>


                        <div class="d-flex gap-3">
                            <select class="form-select form-select-solid w-150px">
                                <option>Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>

                            <a href="{{ route('admin.variant_attributes.create') }}" class="btn btn-primary">
                                Thêm Thuộc Tính
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Header tools-->

                <!--begin::Table-->
                <div class="card card-flush">
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead class="text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <tr>
                                    <th class="text-center min-w-60px">ID</th>
                                    <th class="min-w-200px">Tên thuộc tính</th>
                                    <th class="min-w-300px">Giá trị</th>
                                    <th class="text-center min-w-100px">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($attributes as $attr)
                                    <tr>
                                        <td class="text-center">{{ $attr->id }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $attr->name }}</span>
                                        </td>
                                        <td>
                                            @forelse($attr->values as $value)
                                                <span class="badge badge-light-primary fw-semibold me-1 mb-1">
                                                    {{ $value->value }}
                                                </span>
                                            @empty
                                                <span class="text-muted fst-italic">Chưa có giá trị</span>
                                            @endforelse
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.variant_attributes.edit', $attr->id) }}"
                                                            class="dropdown-item">
                                                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.variant_attributes.destroy', $attr->id) }}"
                                                            method="POST" onsubmit="return confirm('Xóa thuộc tính này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa-solid fa-trash me-2 text-danger"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Không có dữ liệu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end mt-4">
                            {!! $attributes->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                </div>
                <!--end::Table-->

            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection