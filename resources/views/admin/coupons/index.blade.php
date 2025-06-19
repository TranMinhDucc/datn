@extends('layouts.admin')

@section('title', 'Danh sách mã giảm giá')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Danh sách mã giảm giá
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Filter menu-->
                    <div class="m-0">
                        <!--begin::Menu toggle-->
                        <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bold"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="fa-solid fa-filter fs-6 me-1"></i> Bộ lọc
                        </a>
                        <!--end::Menu toggle-->

                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                            id="kt_menu_coupons_filter">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Tùy chọn bộ lọc</div>
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
                                    <label class="form-label fw-semibold">Loại giảm giá:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" multiple data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="Chọn loại"
                                            data-dropdown-parent="#kt_menu_coupons_filter" data-allow-clear="true">
                                            <option></option>
                                            <option value="percent">Phần trăm</option>
                                            <option value="fixed">Cố định</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">Đặt lại</button>
                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Áp
                                        dụng</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Menu 1-->
                    </div>
                    <!--end::Filter menu-->
                    <!--begin::Primary button-->

                    <!--end::Primary button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

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
                            <form method="GET" action="{{ route('admin.search') }}">
                                <input type="hidden" name="module" value="coupons">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"><span
                                            class="path1"></span><span class="path2"></span></i>
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                        class="form-control form-control-solid w-250px ps-12" placeholder="Search Coupon" />
                                </div>
                            </form>
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
                    <!--end::Card header-->

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
                                                    <input class="form-check-input" type="checkbox" value="{{ $coupon->id }}" />
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
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    Hành động
                                                    <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                                </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                            class="menu-link px-3 btn btn-link p-0 m-0">
                                                            Sửa
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                                            method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="menu-link px-3 btn btn-link p-0 m-0">Xóa</button>
                                                        </form>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
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
                            {{-- Pagination links --}}
                            <div class="d-flex justify-content-end mt-4">
                                {{ $coupons->appends(request()->query())->links('pagination::bootstrap-5') }}

                            </div>
                            {{-- end pagination --}}
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Coupons-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection