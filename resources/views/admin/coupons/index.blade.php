@extends('layouts.admin')

@section('title', 'Danh sách mã giảm giá')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Danh sách mã giảm giá
                </h1>
            </div>
        </div>
    </div>

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Coupons-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                            <input type="text" data-kt-ecommerce-coupon-filter="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Tìm kiếm mã giảm giá" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Add coupon-->
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                            Thêm mã giảm giá mới
                        </a>
                        <!--end::Add coupon-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_coupon_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_ecommerce_coupon_table .form-check-input"
                                                value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-40px">#</th>
                                    <th class="min-w-150px">Mã</th>
                                    <th class="min-w-100px">Loại</th>
                                    <th class="min-w-100px">Giá trị</th>
                                    <th class="min-w-100px">Đã dùng</th>
                                    <th class="min-w-150px">Thời gian</th>
                                    <th class="text-end min-w-100px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($coupons as $coupon)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $coupon->id }}" />
                                        </div>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="ms-5">
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1"
                                                data-kt-ecommerce-coupon-filter="coupon_code">
                                                {{ $coupon->code }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($coupon->discount_type) }}</td>
                                    <td>
                                        {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . 'đ' }}
                                    </td>
                                    <td>{{ $coupon->usage_count }}/{{ $coupon->max_usage }}</td>
                                    <td>
                                        {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y H:i') : '-' }}
                                        <br>
                                        đến <br>
                                        {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    
                                    {{-- Thao tác --}}
                                   <td class="text-end">
    <div class="dropdown">
        <button class="btn btn-sm btn-light btn-active-light-primary"
            data-bs-toggle="dropdown">
            Hành Động <i class="fa fa-chevron-down ms-1"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <!-- Sửa -->
            <li>
                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="dropdown-item">
                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Sửa
                </a>
            </li>
            <!-- Xóa -->
            <li>
                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
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
                                    <td colspan="8" class="text-center text-muted">Không có mã giảm giá nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                        <!-- Phần pagination được sửa -->
                        @if($coupons->total() > 0)
                        <div class="d-flex flex-stack flex-wrap pt-10">
                            <div class="fs-6 fw-semibold text-gray-700">
                                Hiển thị {{ $coupons->firstItem() ?? 0 }} đến {{ $coupons->lastItem() ?? 0 }}
                                trong tổng số {{ $coupons->total() }} kết quả
                            </div>
                            @if($coupons->hasPages())
                            <div class="d-flex align-items-center">
                                {{ $coupons->appends(request()->query())->links('vendor.pagination.adminPagi') }}
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection