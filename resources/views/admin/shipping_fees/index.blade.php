@extends('layouts.admin')

@section('title', 'Quản lý phí vận chuyển')

@section('content')
<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Quản lý phí vận chuyển
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.shipping-fees.create') }}" class="btn btn-sm fw-bold btn-primary">
                    <i class="fa-solid fa-plus fs-2"></i>
                    Thêm phí mới
                </a>
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
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!-- begin::Search-->
                        <!-- <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-shipping-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Tìm kiếm phí vận chuyển" />
                        </div> -->
                        <!--end::Search -->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-shipping-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="fa-solid fa-filter fs-2"></i>
                                Lọc
                            </button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">Tùy chọn lọc</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5">
                                    <form method="GET" action="{{ route('admin.shipping-fees.index') }}" data-kt-shipping-table-filter="form">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Khu vực:</label>
                                            <select class="form-select form-select-solid fw-bold"
                                                data-kt-select2="true"
                                                data-placeholder="Chọn khu vực"
                                                data-allow-clear="true"
                                                data-kt-shipping-table-filter="zones"
                                                name="zones[]"
                                                multiple="multiple">
                                                @foreach($zones as $zone)
                                                <option value="{{ $zone->id }}"
                                                    {{ is_array(request('zones')) && in_array($zone->id, request('zones')) ? 'selected' : '' }}>
                                                    {{ $zone->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Phương thức:</label>
                                            <select class="form-select form-select-solid fw-bold"
                                                data-kt-select2="true"
                                                data-placeholder="Chọn phương thức"
                                                data-allow-clear="true"
                                                data-kt-shipping-table-filter="methods"
                                                name="methods[]"
                                                multiple="multiple">
                                                @foreach($methods as $method)
                                                <option value="{{ $method->id }}"
                                                    {{ is_array(request('methods')) && in_array($method->id, request('methods')) ? 'selected' : '' }}>
                                                    {{ $method->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.shipping-fees.index') }}"
                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                data-kt-menu-dismiss="true">
                                                Reset
                                            </a>
                                            <button type="submit"
                                                class="btn btn-primary fw-semibold px-6"
                                                data-kt-menu-dismiss="true">
                                                Áp dụng
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_shipping_table">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_shipping_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-125px">Khu vực</th>
                                <th class="min-w-125px">Phương thức</th>
                                <th class="min-w-125px">Phí vận chuyển</th>
                                <th class="min-w-125px">Miễn phí từ</th>
                                <th class="min-w-70px text-end">Hành động</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($shippingFees as $fee)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $fee->id }}" />
                                    </div>
                                </td>
                                <td class="d-flex align-items-center">
                                    <!--begin::User details-->
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $fee->zone->name }}</a>
                                        <span class="text-muted fs-7">{{ $fee->zone->description ?? 'Không có mô tả' }}</span>
                                    </div>
                                    <!--begin::User details-->
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px symbol-circle me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="fa-solid fa-truck fs-2 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="text-gray-800 text-hover-primary mb-1">{{ $fee->method->name }}</div>
                                            <div class="text-muted fs-7">{{ $fee->method->description ?? 'Giao hàng tiêu chuẩn' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge badge-light-success fs-7 fw-bold">
                                        {{ number_format($fee->price) }} đ
                                    </div>
                                </td>
                                <td>
                                    @if ($fee->free_shipping_minimum)
                                    <div class="badge badge-light-info fs-7 fw-bold">
                                        {{ number_format($fee->free_shipping_minimum) }} đ
                                    </div>
                                    @else
                                    <span class="text-muted fs-7">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Hành động
                                        <i class="fa-solid fa-chevron-down fs-5 ms-1"></i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('admin.shipping-fees.edit', $fee->id) }}" class="menu-link px-3">
                                                Chỉnh sửa
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-shipping-table-filter="delete_row" data-fee-id="{{ $fee->id }}">
                                                Xóa
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->

<!-- Delete Form (Hidden) -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    // Class definition
    var KTShippingFeesTable = function() {
        // Shared variables
        var table;
        var datatable;

        // Private functions
        var initDatatable = function() {
            datatable = $(table).DataTable({
                "info": false,
                'order': [],
                'pageLength': 10,
                'columnDefs': [{
                        orderable: false,
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: 5
                    }
                ]
            });
        }

        var handleDeleteRows = function() {
            table.addEventListener('click', function(e) {
                const deleteButton = e.target.closest('[data-kt-shipping-table-filter="delete_row"]');
                if (!deleteButton) return;

                e.preventDefault();

                const feeId = deleteButton.getAttribute('data-fee-id');

                Swal.fire({
                    text: "Bạn có chắc chắn muốn xóa phí vận chuyển này?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có, xóa!",
                    cancelButtonText: "Không, hủy",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function(result) {
                    if (result.value) {
                        const form = document.getElementById('delete-form');
                        form.action = `/admin/shipping-fees/${feeId}`;
                        form.submit();
                    }
                });
            });
        }
        return {
            init: function() {
                table = document.querySelector('#kt_shipping_table');

                if (!table) return;

                initDatatable();
                handleDeleteRows();
            }
        };
    }();

    // On document ready
    document.addEventListener('DOMContentLoaded', function() {
        KTShippingFeesTable.init();
    });
    // Khởi tạo Select2 cho multi-select
    $(document).ready(function() {
        // Khởi tạo Select2 cho dropdown khu vực
        $('select[name="zones[]"]').select2({
            placeholder: "Chọn khu vực",
            allowClear: true,
            width: '100%',
            closeOnSelect: false, // Không đóng dropdown sau khi chọn
            language: {
                noResults: function() {
                    return "Không tìm thấy kết quả";
                },
                searching: function() {
                    return "Đang tìm kiếm...";
                }
            }
        });

        // Khởi tạo Select2 cho dropdown phương thức
        $('select[name="methods[]"]').select2({
            placeholder: "Chọn phương thức",
            allowClear: true,
            width: '100%',
            closeOnSelect: false, // Không đóng dropdown sau khi chọn
            language: {
                noResults: function() {
                    return "Không tìm thấy kết quả";
                },
                searching: function() {
                    return "Đang tìm kiếm...";
                }
            }
        });

        // Xử lý reset form
        $('[data-kt-shipping-table-filter="reset"]').on('click', function(e) {
            e.preventDefault();

            // Clear tất cả Select2
            $('select[name="zones[]"]').val(null).trigger('change');
            $('select[name="methods[]"]').val(null).trigger('change');

            // Redirect về trang không có filter
            window.location.href = $(this).attr('href');
        });

        // Xử lý submit form
        $('[data-kt-shipping-table-filter="form"]').on('submit', function(e) {
            // Có thể thêm validation hoặc xử lý khác ở đây nếu cần
        });
    });
</script>
@endpush