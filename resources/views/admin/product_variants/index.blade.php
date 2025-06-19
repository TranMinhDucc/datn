@extends('layouts.admin')
@section('title', 'Danh sách biến thể sản phẩm')
@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Product Variants
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">eCommerce</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Variants</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.product_variants.create') }}" class="btn btn-sm fw-bold btn-primary">
                        Thêm Biến Thể
                    </a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                                <input type="text" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Tìm kiếm biến thể" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Tên sản phẩm</th>
                                    <th class="text-center">Thuộc tính</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Tồn kho</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($variants as $variant)
                                    <tr>
                                        <td class="text-center">{{ $variant->id }}</td>
                                        <td class="text-center">{{ $variant->product->name ?? '-' }}</td>
                                        <td class="text-center">
                                            @foreach($variant->attributes as $attr)
                                                <span class="badge bg-light-info text-dark">{{ $attr->name }}:
                                                    {{ $attr->pivot->value }}</span><br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                                        <td class="text-center">{{ $variant->stock }}</td>
                                        <td class="text-center">
                                            @if ($variant->status)
                                                <span class="badge badge-light-success">Hiện</span>
                                            @else
                                                <span class="badge badge-light-danger">Ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                    data-bs-toggle="dropdown">
                                                    Hành động <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('admin.product_variants.edit', $variant->id) }}"
                                                            class="dropdown-item">Sửa</a></li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.product_variants.destroy', $variant->id) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end mt-4">
                            {{ $variants->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection