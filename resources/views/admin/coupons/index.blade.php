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
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    Thêm mã giảm giá mới
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
                                placeholder="Tìm kiếm mã giảm giá" />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <!-- Có thể thêm nút filter hoặc export -->
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_coupon_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
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
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold">
                                                {{ $coupon->code }}
                                            </a>
                                        </td>
                                        <td>
                                            @switch($coupon->type)
                                                @case('product_discount') Sản phẩm @break
                                                @case('shipping_discount') Phí vận chuyển @break
                                                @case('order_discount') Toàn đơn hàng @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($coupon->value_type === 'percentage')
                                                {{ $coupon->discount_value }}%
                                            @else
                                                {{ number_format($coupon->discount_value) }}đ
                                            @endif
                                        </td>
                                        <td>
                                            {{ $coupon->used_count }} /
                                            {{ $coupon->usage_limit ?? '∞' }}
                                        </td>
                                        <td>
                                            {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y H:i') : '-' }}
                                            <br> đến <br>
                                            {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Hành động
                                                <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                        class="menu-link px-3">Sửa</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                                        method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="menu-link px-3 btn btn-link p-0 m-0">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có mã giảm giá nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $coupons->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
