@extends('layouts.admin')
@section('title', 'Danh sách đơn hàng')
@section('content')

    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Danh sách đơn hàng
                        </h1>
                        <!--end::Title-->


                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="../../../index.html" class="text-muted text-hover-primary">
                                    Home </a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                eCommerce </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                Sales </li>
                            <!--end::Item-->

                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page title-->
                    <!--begin::Actions-->

                    <!--end::Actions-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-xxl ">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                           <!--begin::Card title-->
<!--begin::Card title-->
<!--begin::Card title-->
<div class="card-title w-100">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end mb-5">

        <!-- Mã đơn hàng -->
        <div class="col-md-2">
            <input type="text" name="order_code" class="form-control" placeholder="Mã Đơn Hàng" value="{{ request('order_code') }}">
        </div>

        <!-- Trạng thái đơn hàng -->
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                <option value="returning" {{ request('status') == 'returning' ? 'selected' : '' }}>Đang hoàn</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Đã hoàn</option>
            </select>
        </div>

        <!-- Từ ngày -->
        <div class="col-md-2">
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>

        <!-- Đến ngày -->
        <div class="col-md-2">
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>

        <!-- Nút tìm kiếm -->
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Tìm</button>
        </div>

        <!-- Nút yêu cầu hủy đơn -->
        <div class="col-md-2 d-grid">
            <a href="{{ route('admin.orders.cancel') }}" class="btn btn-warning">Yêu cầu hủy đơn</a>
        </div>
    </form>
</div>
<!--end::Card title-->
<!--end::Card title-->
<!--end::Card title-->
                            <!--end::Card title-->

                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                <!--begin::Flatpickr-->
                                {{-- <div class="input-group w-250px">
                                    <input class="form-control form-control-solid rounded rounded-end-0"
                                        placeholder="Pick date range" id="kt_ecommerce_sales_flatpickr" />
                                    <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </button>
                                </div> --}}
                                <!--end::Flatpickr-->

                                {{-- <div class="w-100 mw-150px">
                                    <!--begin::Select2-->
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="true" data-placeholder="Status"
                                        data-kt-ecommerce-order-filter="status">
                                        <option></option>
                                        <option value="all">All</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Denied">Denied</option>
                                        <option value="Expired">Expired</option>
                                        <option value="Failed">Failed</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Processing">Processing</option>
                                        <option value="Refunded">Refunded</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Delivering">Delivering</option>
                                    </select>
                                    <!--end::Select2-->
                                </div> --}}

                                <!--begin::Add product-->

                                <!--end::Add product-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="text-start w-10px pe-2">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                    data-kt-check-target="#kt_ecommerce_sales_table .form-check-input"
                                                    value="1" />
                                            </div>
                                        </th>
                                        <th class="text-start">Mã đơn </th>
                                        <th class="text-start">Khách hàng</th>
                                        <th class="text-center min-w-100px">Tổng tiền</th>
                                        <th class="text-center min-w-100px">Ngày tạo</th>
                                        <th class="text-center ">Trạng thái</th>
                                        {{-- <th class="text-center min-w-100px">Ngày cập nhật </th> --}}
                                        <th class="text-center ">Vận chuyển</th>
                                        <th class="text-center ">Mã vận đơn</th>
                                        <th class="text-center min-w-100px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="text-start">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $order->id }}" />
                                                </div>
                                            </td>
                                            <td class="text-start">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="text-gray-800 text-hover-primary fw-bold">
                                                    {{ $order->order_code ?? $order->id }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="text-center d-flex align-items-center">
                                                    <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">
                                                        {{ $order->user->fullname ?? 'Khách lẻ' }}
                                                    </a>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <span class="fw-bold">{{ number_format($order->total_amount) }}đ</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">{{ $order->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $orderStatuses = [
                                                        'pending' => [
                                                            'label' => 'Đang chờ xác nhận',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-clock',
                                                        ],
                                                        'confirmed' => [
                                                            'label' => 'Đã xác nhận',
                                                            'color' => 'primary',
                                                            'icon' => 'ki-check-square',
                                                        ],
                                                        'shipping' => [
                                                            'label' => 'Đang giao hàng',
                                                            'color' => 'info',
                                                            'icon' => 'ki-settings',
                                                        ],
                                                        'completed' => [
                                                            'label' => 'Hoàn thành',
                                                            'color' => 'success',
                                                            'icon' => 'ki-check-circle',
                                                        ],
                                                        'cancelled' => [
                                                            'label' => 'Đã hủy',
                                                            'color' => 'danger',
                                                            'icon' => 'ki-cross-circle',
                                                        ],
                                                        'refunded' => [
                                                            'label' => 'Đã hoàn tiền',
                                                            'color' => 'secondary',
                                                            'icon' => 'ki-undo',
                                                        ],
                                                    ];

                                                    $status = $order->status ?? 'pending';

                                                    $orderStatus = $orderStatuses[$status] ?? [
                                                        'label' => ucfirst($status),
                                                        'color' => 'light',
                                                        'icon' => 'ki-question-circle',
                                                    ];
                                                @endphp

                                                <span class="badge badge-light-{{ $orderStatus['color'] }}">
                                                    <i class="ki-duotone {{ $orderStatus['icon'] }} fs-6 me-1"></i>
                                                    {{ $orderStatus['label'] }}
                                                </span>
                                            </td>

                                            {{-- <td class="text-center">
                                                <span class="fw-bold">{{ $order->updated_at->format('d/m/Y') }}</span>
                                            </td> --}}
                                            <td class="text-center">
                                                @php
                                                    $shippingStatuses = [
                                                        'pending' => [
                                                            'label' => 'Chưa tạo đơn',
                                                            'color' => 'secondary',
                                                            'icon' => 'ki-clock',
                                                        ],
                                                        'created' => [
                                                            'label' => 'Đã tạo vận đơn',
                                                            'color' => 'primary',
                                                            'icon' => 'ki-document',
                                                        ],
                                                        'storing' => [
                                                            'label' => 'Chờ giao hàng',
                                                            'color' => 'info',
                                                            'icon' => 'ki-box',
                                                        ],
                                                        'picking' => [
                                                            'label' => 'Đang lấy hàng',
                                                            'color' => 'info',
                                                            'icon' => 'ki-truck',
                                                        ],
                                                        'delivering' => [
                                                            'label' => 'Đơn hàng đang được giao đến tay người nhận',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-send',
                                                        ],
                                                        'delivered' => [
                                                            'label' => 'Giao thành công cho người nhận',
                                                            'color' => 'success',
                                                            'icon' => 'ki-check-circle',
                                                        ],
                                                        'failed' => [
                                                            'label' => 'Thất bại',
                                                            'color' => 'danger',
                                                            'icon' => 'ki-cross-circle',
                                                        ],
                                                        'returning' => [
                                                            'label' => 'Đơn hàng đang trong tiến trình đang hoàn hàng',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-refresh',
                                                        ],
                                                        'return_fail' => [
                                                            'label' =>
                                                                'Trả hàng thất bại (shop không nhận, không liên hệ được,...)',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-refresh',
                                                        ],
                                                        'return' => [
                                                            'label' => 'Đơn hàng bắt đầu quá trình trả lại',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-refresh',
                                                        ],
                                                        'return_sorting' => [
                                                            'label' => 'Hàng hoàn đang trong kho phân loại',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-refresh',
                                                        ],
                                                        'return_transporting' => [
                                                            'label' => 'Hàng đang được vận chuyển về shop',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-refresh',
                                                        ],
                                                        'returned' => [
                                                            'label' => 'Đơn hàng đã hoàn trả về shop thành công',
                                                            'color' => 'danger',
                                                            'icon' => 'ki-undo',
                                                        ],
                                                        'cancel' => [
                                                            'label' => 'Đã hủy đơn',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'ready_to_pick' => [
                                                            'label' => 'Đơn đã sẵn sàng, Chờ GHN đến lấy',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'money_collect_picking' => [
                                                            'label' => 'Đang thu tiền khi lấy hàng',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'delivery_fail' => [
                                                            'label' => 'Giao hàng thất bại',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'waiting_to_return' => [
                                                            'label' => 'Đang chờ xử lý hoàn trả đơn hàng về shop',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'picked' => [
                                                            'label' => 'Đơn hàng đã được bên vận chuyển lấy thành công',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'transporting' => [
                                                            'label' => 'Đơn hàng đang trên đường vận chuyển',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'sorting' => [
                                                            'label' =>
                                                                'Hàng đang trong quá trình phân loại tại kho trung chuyển',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                        'money_collect_delivering' => [
                                                            'label' =>
                                                                'Đơn hàng đang được giao và GHN sẽ thu tiền từ người nhận (COD)',
                                                            'color' => 'dark',
                                                            'icon' => 'ki-ban',
                                                        ],
                                                    ];

                                                    $status = $order->shippingOrder->status ?? 'pending';

                                                    $shipping = $shippingStatuses[$status] ?? [
                                                        'label' => ucfirst($status),
                                                        'color' => 'light',
                                                        'icon' => 'ki-question-circle',
                                                    ];
                                                @endphp

                                                <span class="badge badge-light-{{ $shipping['color'] }}">
                                                    <i class="ki-duotone {{ $shipping['icon'] }} fs-6 me-1"></i>
                                                    {{ $shipping['label'] }}
                                                </span>
                                            </td>


                                            <td class="text-center">
                                                {{ $order->shippingOrder->shipping_code ?? 'Chưa tạo vận đơn' }}
                                            </td>


                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    Actions
                                                </a>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                                            class="menu-link px-3">View</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="" class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form method="POST" action="">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="menu-link px-3 bg-transparent border-0">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{ $orders->links('pagination::bootstrap-5') }}
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Products-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->

        </div>

    @endsection
