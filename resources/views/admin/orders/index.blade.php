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
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-xxl ">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <form method="GET" action="{{ route('admin.orders.index') }}"
                                class="d-flex w-100 justify-content-between">
                                <div class="card-title">
                                    <!--begin::Search-->
                                    <div class="card-title">
                                        <!--begin::Search-->
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                class="form-control form-control-solid w-250px ps-12"
                                                placeholder="Tìm mã đơn, tên khách hàng, mã vận đơn..." />
                                        </div>
                                        <!--end::Search-->
                                    </div>

                                    <!--end::Search-->
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                                    <!--end::Card title-->

                                    <!--begin::Card toolbar-->

                                    <!--begin::Flatpickr-->
                                    {{-- <div class="input-group w-250px">
                                        <input class="form-control form-control-solid rounded rounded-end-0"
                                            placeholder="Pick date range" id="kt_ecommerce_sales_flatpickr" />
                                        <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                                    class="path2"></span></i> </button>
                                    </div> --}}
                                    <!--end::Flatpickr-->

                                    <!-- Dropdown trạng thái bên phải -->
                                    <div class="w-100 mw-150px">
                                        <select class="form-select form-select-solid" name="status"
                                            onchange="this.form.submit()"> <!-- tự submit khi đổi -->
                                            <option value="">Tất cả trạng thái</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                Đang
                                                chờ xác nhận</option>
                                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã
                                                xác nhận</option>
                                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                                Đang xử lý</option>
                                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>
                                                Đang
                                                giao hàng</option>
                                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã
                                                giao hàng</option>
                                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                                Hoàn thành</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã
                                                hủy</option>
                                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>
                                                Đã
                                                trả hàng</option>
                                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>
                                                Đã
                                                hoàn tiền</option>
                                        </select>
                                    </div>




                                    <!--end::Add product-->

                            </form>
                            <!--begin::Add product-->
                            <a href="{{ route('admin.orders.cancel') }}" class="btn btn-warning">
                                Yêu cầu hủy đơn
                            </a>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table"
                                    style="min-width: 1300px;">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="text-start w-10px pe-2">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
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
                                            <th class="text-center ">Vận chuyển</th>
                                            <th class="text-center ">Mã vận đơn</th>
                                            <th class="text-center min-w-100px">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="text-start">
                                                    <div
                                                        class="form-check form-check-sm form-check-custom form-check-solid">
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
                                                        <a href="#"
                                                            class="text-gray-800 text-hover-primary fs-5 fw-bold">
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
                                                            'processing' => [
                                                                'label' => 'Đang xử lý',
                                                                'color' => 'warning',
                                                                'icon' => 'ki-settings',
                                                            ],
                                                            'ready_for_dispatch' => [
                                                                'label' => 'Sẵn sàng giao hàng',
                                                                'color' => 'info',
                                                                'icon' => 'ki-truck',
                                                            ],
                                                            'shipping' => [
                                                                'label' => 'Đang giao hàng',
                                                                'color' => 'info',
                                                                'icon' => 'ki-truck',
                                                            ],
                                                            'delivered' => [
                                                                'label' => 'Đã giao hàng',
                                                                'color' => 'success',
                                                                'icon' => 'ki-check-circle',
                                                            ],
                                                            'completed' => [
                                                                'label' => 'Hoàn thành',
                                                                'color' => 'success',
                                                                'icon' => 'ki-badge',
                                                            ],
                                                            'cancelled' => [
                                                                'label' => 'Đã hủy',
                                                                'color' => 'danger',
                                                                'icon' => 'ki-cross-circle',
                                                            ],
                                                            'delivery_failed' => [
                                                                'label' => 'Giao hàng thất bại',
                                                                'color' => 'danger',
                                                                'icon' => 'ki-close-circle',
                                                            ],
                                                            'returning' => [
                                                                'label' => 'Đang trả hàng',
                                                                'color' => 'warning',
                                                                'icon' => 'ki-undo',
                                                            ],
                                                            'returned' => [
                                                                'label' => 'Đã trả hàng',
                                                                'color' => 'secondary',
                                                                'icon' => 'ki-rotate-cw',
                                                            ],
                                                            'refunded' => [
                                                                'label' => 'Đã hoàn tiền',
                                                                'color' => 'secondary',
                                                                'icon' => 'ki-undo',
                                                            ],
                                                            'exchange_requested' => [
                                                                'label' => 'Yêu cầu đổi hàng',
                                                                'color' => 'warning',
                                                                'icon' => 'ki-refresh',
                                                            ],
                                                            'return_requested' => [
                                                                'label' => 'Yêu cầu trả hàng',
                                                                'color' => 'warning',
                                                                'icon' => 'ki-undo',
                                                            ],
                                                            'exchanged' => [
                                                                'label' => 'Đã đổi hàng',
                                                                'color' => 'success',
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
                                                                'label' =>
                                                                    'Đơn hàng đang trong tiến trình đang hoàn hàng',
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
                                                                'label' =>
                                                                    'Đơn hàng đã được bên vận chuyển lấy thành công',
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
                                                       Hành Động
                                                    </a>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                                class="menu-link px-3">Xem</a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a href="" class="menu-link px-3">Sửa</a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <form method="POST" action="">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="menu-link px-3 bg-transparent border-0">Xóa</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            {{ $orders->links('pagination::bootstrap-5') }}
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table"
                                style="min-width: 1300px;">
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
                                                    <input class="form-check-input" type="checkbox" value="{{ $order->id }}" />
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
                                                        'processing' => [
                                                            'label' => 'Đang xử lý',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-settings',
                                                        ],
                                                        'ready_for_dispatch' => [
                                                            'label' => 'Sẵn sàng giao hàng',
                                                            'color' => 'info',
                                                            'icon' => 'ki-truck',
                                                        ],
                                                        'shipping' => [
                                                            'label' => 'Đang giao hàng',
                                                            'color' => 'info',
                                                            'icon' => 'ki-truck',
                                                        ],
                                                        'delivered' => [
                                                            'label' => 'Đã giao hàng',
                                                            'color' => 'success',
                                                            'icon' => 'ki-check-circle',
                                                        ],
                                                        'completed' => [
                                                            'label' => 'Hoàn thành',
                                                            'color' => 'success',
                                                            'icon' => 'ki-badge',
                                                        ],
                                                        'cancelled' => [
                                                            'label' => 'Đã hủy',
                                                            'color' => 'danger',
                                                            'icon' => 'ki-cross-circle',
                                                        ],
                                                        'delivery_failed' => [
                                                            'label' => 'Giao hàng thất bại',
                                                            'color' => 'danger',
                                                            'icon' => 'ki-close-circle',
                                                        ],
                                                        'returning' => [
                                                            'label' => 'Đang trả hàng',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-undo',
                                                        ],
                                                        'returned' => [
                                                            'label' => 'Đã trả hàng',
                                                            'color' => 'secondary',
                                                            'icon' => 'ki-rotate-cw',
                                                        ],
                                                        'refunded' => [
                                                            'label' => 'Đã hoàn tiền',
                                                            'color' => 'secondary',
                                                            'icon' => 'ki-undo',
                                                        ],
                                                        'exchange_requested' => [
                                                            'label' => 'Yêu cầu đổi hàng',
                                                            'color' => 'warning',
                                                            'icon' => 'ki-refresh',
                                                        ],
                                                        'return_requested' => [
                                                            'label' => 'Yêu cầu trả hàng',
                                                            'color' => 'warning',
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
                                                            'label' =>
                                                                'Đơn hàng đang trong tiến trình đang hoàn hàng',
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
                                                            'label' =>
                                                                'Đơn hàng đã được bên vận chuyển lấy thành công',
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
                        </div>
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
@section('js')
    <script>
        $(document).ready(function () {
            const table = $('#kt_ecommerce_sales_table').DataTable({
                // order: [
                //     [4, 'desc']
                // ],
                paging: false, // ❌ Tắt phân trang
                info: false, // ❌ Tắt dòng "Showing x to y..."
                lengthChange: false, // ❌ Tắt dropdown chọn số dòng
                language: {
                    search: "Tìm kiếm:",
                    zeroRecords: "Không tìm thấy kết quả phù hợp",
                }
            });

            // // 🔍 Tìm kiếm theo từ khoá
            // $('[data-kt-ecommerce-order-filter="search"]').on('keyup', function() {
            //     table.search(this.value).draw();
            // });

            // // ✅ Lọc theo Trạng thái
            // $('[data-kt-ecommerce-order-filter="status"]').on('change', function() {
            //     let selected = $(this).val();
            //     if (selected === 'Tất cả' || selected === '') {
            //         table.column(5).search('').draw(); // Cột Trạng thái
            //     } else {
            //         table.column(5).search(selected, true, false).draw();
            //     }
            // });
        });
    </script>
@endsection