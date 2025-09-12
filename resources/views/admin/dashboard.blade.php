@extends('layouts.admin')


@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Bảng điều khiển
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="../../../index.html" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Bảng điều khiển</li>
                </ul>
            </div>
            <div class="card-toolbar">
                <div id="daterange" class="btn btn-sm btn-primary d-flex align-items-center px-4">
                    <div class="text-white fw-bold">
                        <span id="daterange-text">
                            {{ request('daterange') ?? now()->subDays(30)->format('d/m/Y') . ' - ' . now()->format('d/m/Y') }}
                        </span>
                    </div>
                    <i class="ki-duotone ki-calendar-8 text-white lh-0 fs-2 ms-2 me-0"></i>
                </div>
            </div>
            <input type="hidden" id="daterange-value" value="{{ request('daterange') }}">
        </div>
    </div>

    <div id="kt_app_content_container" class="app-container container-xxl">
        {{-- Row top cards --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card p-7 shadow-sm hover-elevate-up h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Khách hàng mới</h6>
                            <h1>{{ number_format($newCustomers) }}</h1>
                        </div>
                        <i class="bi bi-person-plus fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card p-7 shadow-sm hover-elevate-up h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Tổng người dùng</h6>
                            <h1>79,503</h1>
                        </div>
                        <i class="bi bi-people fs-2 text-info"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card p-7 shadow-sm hover-elevate-up h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Đơn hàng</h6>
                            <h1>15,503</h1>
                        </div>
                        <i class="bi bi-box-seam fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card p-7 shadow-sm hover-elevate-up h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Doanh thu</h6>
                            <h1>{{ number_format($revenue, 0, ',', '.') }} ₫</h1>
                            <small class="text-success">
                                (Trong khoảng chọn: {{ number_format($revenueInRange, 0, ',', '.') }} ₫)
                            </small>
                        </div>
                        <i class="bi bi-currency-dollar fs-2 text-warning"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sales Report + Orders Overview --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title fw-bold text-center">Nguồn lưu lượng truy cập</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title fw-bold text-center">Tổng quan đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Activity + New Customers --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title fw-bold text-center">Hoạt động của người dùng</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title fw-bold text-center">Khách hàng mới mỗi ngày</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="newCustomersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Customers + Payment Methods --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0 fw-bold text-center">Tổng khách hàng theo thời gian</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="totalCustomersChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0 fw-bold text-center">Phương thức thanh toán</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="row gy-5 g-xl-10" bis_skin_checked="1" data-select2-id="select2-data-128-8l1i">
            <!--begin::Col-->
            <div class="col-xl-4 mb-xl-10" bis_skin_checked="1">

                <!--begin::Engage widget 1-->
                <div class="card h-md-100" dir="ltr" bis_skin_checked="1">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column flex-center" bis_skin_checked="1">
                        <!--begin::Heading-->
                        <div class="mb-2" bis_skin_checked="1">
                            <!--begin::Title-->
                            <h1 class="fw-semibold text-gray-800 text-center lh-lg">
                                Have you tried <br> new
                                <span class="fw-bolder"> eCommerce App ?</span>
                            </h1>
                            <!--end::Title-->

                            <!--begin::Illustration-->
                            <div class="py-10 text-center" bis_skin_checked="1">
                                <img src="/metronic8/demo1/assets/media/svg/illustrations/easy/2.svg"
                                    class="theme-light-show w-200px" alt="">
                                <img src="/metronic8/demo1/assets/media/svg/illustrations/easy/2-dark.svg"
                                    class="theme-dark-show w-200px" alt="">
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::Heading-->

                        <!--begin::Links-->
                        <div class="text-center mb-1" bis_skin_checked="1">
                            <!--begin::Link-->
                            <a class="btn btn-sm btn-primary me-2"
                                href="/metronic8/demo1/apps/ecommerce/sales/listing.html">
                                View App </a>
                            <!--end::Link-->

                            <!--begin::Link-->
                            <a class="btn btn-sm btn-light"
                                href="/metronic8/demo1/apps/ecommerce/catalog/add-product.html">
                                New Product </a>
                            <!--end::Link-->
                        </div>
                        <!--end::Links-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Engage widget 1-->

            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xl-8 mb-5 mb-xl-10">
                <!--begin::Table Widget 4-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Đơn hàng gần đây</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Trung bình: {{ $recentOrders->count() }}
                                đơn</span>
                        </h3>
                        <!--end::Title-->

                        <!--begin::Actions-->
                        <div class="card-toolbar">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap gap-4">
                                <!--begin::Status-->
                                <div class="d-flex align-items-center fw-bold">
                                    <!--begin::Label-->
                                    <div class="text-gray-500 fs-7 me-2">Trạng thái</div>
                                    <!--end::Label-->

                                    <!--begin::Select-->
                                    <select
                                        class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto"
                                        data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px"
                                        data-placeholder="Chọn trạng thái" id="kt_filter_status">
                                        <option></option>
                                        <option value="Show All" selected>Hiển thị tất cả</option>
                                        <option value="pending">Chờ xử lý</option>
                                        <option value="confirmed">Đã xác nhận</option>
                                        <option value="processing">Đang xử lý</option>
                                        <option value="shipping">Đang giao hàng</option>
                                        <option value="completed">Hoàn thành</option>
                                        <option value="cancelled">Đã hủy</option>
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <!--end::Status-->

                                <!--begin::Search-->
                                <div class="position-relative my-1">
                                    <i
                                        class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" id="kt_search_orders" class="form-control w-150px fs-7 ps-12"
                                        placeholder="Tìm kiếm">
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--end::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-2">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_recent_orders_table">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">Mã đơn</th>
                                        <th class="text-end min-w-100px">Ngày tạo</th>
                                        <th class="text-end min-w-125px">Khách hàng</th>
                                        <th class="text-end min-w-100px">Tổng tiền</th>
                                        <th class="text-end min-w-100px">Trạng thái</th>
                                        <th class="text-end min-w-50px">Thao tác</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->

                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600">
                                    @foreach ($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <a href="#"
                                                    class="text-gray-800 text-hover-primary">#{{ $order->order_code }}</a>
                                            </td>
                                            <td class="text-end">{{ $order->created_at->diffForHumans() }}</td>
                                            <td class="text-end">
                                                <a href="#" class="text-gray-600 text-hover-primary">
                                                    {{ $order->user->fullname ?? 'Khách vãng lai' }}
                                                </a>
                                            </td>
                                            <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }} đ
                                            </td>
                                            <td class="text-end">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'confirmed' => 'primary',
                                                        'processing' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger',
                                                        'shipping' => 'info',
                                                        'delivered' => 'success',
                                                    ];
                                                    $badgeColor = $statusColors[$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge py-3 px-4 fs-7 badge-light-{{ $badgeColor }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px"
                                                    data-kt-order-id="{{ $order->id }}">
                                                    <i class="fa-solid fa-plus fs-4 m-0"></i>
                                                </button>
                                            </td>

                                        </tr>
                                        <!-- Order details row -->
                                        <tr class="d-none" id="kt_order_details_{{ $order->id }}">
                                            <td colspan="6" class="p-0 bg-light">
                                                <div class="p-4">
                                                    <h6 class="fw-bold mb-3">Chi tiết đơn hàng #{{ $order->order_code }}
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered align-middle mb-0">
                                                            <thead>
                                                                <tr
                                                                    class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                                    <th class="min-w-250px">Sản phẩm</th>
                                                                    <th class="text-center min-w-100px">Số lượng</th>
                                                                    <th class="text-end min-w-100px">Đơn giá</th>
                                                                    <th class="text-end min-w-100px">Thành tiền</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-semibold text-gray-600">
                                                                @foreach ($order->items as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <img src="{{ $item->image_url ?? '/placeholder.png' }}"
                                                                                    alt="{{ $item->product_name }}"
                                                                                    class="w-50px h-50px rounded me-3">
                                                                                <div>
                                                                                    <div class="fw-bold text-truncate"
                                                                                        style="max-width: 250px;">
                                                                                        {{ \Illuminate\Support\Str::limit($item->product_name, 30, '...') }}
                                                                                    </div>

                                                                                    <small class="text-muted">SKU:
                                                                                        {{ $item->sku ?? 'N/A' }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                                        <td class="text-end">
                                                                            {{ number_format($item->price, 0, ',', '.') }}
                                                                            đ</td>
                                                                        <td class="text-end">
                                                                            {{ number_format($item->total_price, 0, ',', '.') }}
                                                                            đ</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Table Widget 4-->
            </div>




            <!--end::Col-->


        </div>


        {{-- New Customers + Top Products --}}
        <div class="row g-4">
            <!-- Top sản phẩm -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <h6 class="card-title mb-0 fw-bold text-center w-100">🔥 Top sản phẩm bán chạy</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($topProducts as $product)
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between hover-elevate-up py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <img src="{{ $product->image_url ?? '/images/products/placeholder.png' }}"
                                                alt="{{ $product->product_name }}" class="rounded-2 object-fit-cover">
                                        </div>
                                        <div>
                                            <span class="fw-semibold d-block text-truncate" style="max-width: 250px;">
                                                {{ \Illuminate\Support\Str::limit($product->product_name, 120, '...') }}
                                            </span>
                                            <small class="text-muted">{{ $product->total_sold }} lượt bán</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-success">
                                        {{ number_format($product->total_revenue, 0, ',', '.') }} ₫
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Chưa có dữ liệu</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>



            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <h6 class="card-title mb-0 fw-bold text-center w-100">📦 Biến thể tồn kho thấp</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($lowStockVariants as $variant)
                                <a href="{{ route('admin.inventory.index', ['sku' => $variant->sku]) }}"
                                    class="list-group-item d-flex align-items-center hover-elevate-up py-3 border-bottom border-2 border-secondary text-decoration-none"
                                    style="cursor: pointer;">

                                    <div class="symbol symbol-50px me-3">
                                        <img src="{{ $variant->product && $variant->product->image
                                            ? asset('storage/' . $variant->product->image)
                                            : asset('/images/products/placeholder.png') }}"
                                            alt="{{ $variant->product->name }}" class="rounded-2 object-fit-cover">
                                    </div>

                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block text-truncate text-dark"
                                            style="max-width: 280px;">
                                            {{ $variant->product->name }}
                                        </span>
                                        <small class="text-muted d-block">
                                            SKU: {{ $variant->sku ?? ($variant->product->sku ?? 'Chưa có') }}
                                        </small>
                                        <small class="text-muted">
                                            {{ $variant->color ?? '' }} {{ $variant->size ?? '' }}
                                        </small>
                                    </div>

                                    <div class="text-end">
                                        <span
                                            class="fw-bold {{ $variant->quantity <= 3 ? 'text-danger' : 'text-warning' }}">
                                            Còn: {{ $variant->quantity }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <li class="list-group-item text-center text-muted">
                                    Không có biến thể nào tồn kho thấp
                                </li>
                            @endforelse
                        </ul>



                        <!-- Pagination chỉ trong card -->
                        <div class="p-3">
                            {{ $lowStockVariants->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>




        </div>

    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/locale/vi.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script>
        $(function() {
            let start = moment().subtract(29, 'days');
            let end = moment();

            @if (request('daterange'))
                let parts = "{{ request('daterange') }}".split(' - ');
                start = moment(parts[0], 'DD/MM/YYYY');
                end = moment(parts[1], 'DD/MM/YYYY');
            @endif

            function cb(start, end) {
                // ép format dd/mm/yyyy
                $('#daterange-text').text(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                $('#daterange-value').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            }

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: false, // ✅ khóa plugin không tự ghi label
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    customRangeLabel: 'Tùy chọn',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    monthNames: [
                        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                    '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Toàn thời gian': [moment('2000-01-01'), moment()] // hoặc 1 ngày rất cũ

                }
            }, cb);

            cb(start, end);

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                let value = picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY');
                window.location.href = "{{ route('admin.dashboard') }}" + "?daterange=" +
                    encodeURIComponent(value);
            });
        });





        // ========== Sales Report ==========
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                        label: 'Giới thiệu (Referral)',
                        data: @json($referral),
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,0.1)',
                        fill: true
                    },
                    {
                        label: 'Trực tiếp',
                        data: @json($direct),
                        borderColor: '#e74a3b',
                        backgroundColor: 'rgba(231,74,59,0.1)',
                        fill: true
                    },
                    {
                        label: 'Mạng xã hội',
                        data: @json($social),
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28,200,138,0.1)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
        // ========== Total Customers ==========
        new Chart(document.getElementById('totalCustomersChart'), {
            type: 'line',
            data: {
                labels: @json($totalCustomerLabels),
                datasets: [{
                    label: 'Tổng khách hàng',
                    data: @json($totalCustomerCounts),
                    borderColor: '#36a2eb',
                    backgroundColor: 'rgba(54,162,235,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // ========== Orders Overview ==========
        // ========== Orders Overview ==========
        new Chart(document.getElementById('ordersChart'), {
            type: 'doughnut',
            data: {
                labels: @json($orderLabels),
                datasets: [{
                    data: @json($orderCounts),
                    backgroundColor: [
                        '#6c757d', '#007bff', '#17a2b8', '#20c997',
                        '#0dcaf0', '#dc3545', '#28a745', '#198754',
                        '#e74a3b', '#ffc107', '#fd7e14', '#ffcd56',
                        '#6610f2', '#6f42c1', '#0d6efd', '#198754'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 15,
                            padding: 15
                        }
                    }
                }
            }
        });


        // ========== User Activity ==========
        new Chart(document.getElementById('userActivityChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                        label: 'Người dùng hoạt động',
                        data: @json($active),
                        borderColor: '#4e73df',
                        fill: false
                    },
                    {
                        label: 'Người dùng không hoạt động',
                        data: @json($inactive),
                        borderColor: '#f6c23e',
                        borderDash: [5, 5],
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // ========== Current Users ==========
        // ========== New Customers ==========
        new Chart(document.getElementById('newCustomersChart'), {
            type: 'bar',
            data: {
                labels: @json($newCustomerLabels),
                datasets: [{
                    label: 'Khách hàng mới',
                    data: @json($newCustomerCounts),
                    backgroundColor: '#36a2eb'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // ========== Payment Methods ==========
        new Chart(document.getElementById('paymentMethodsChart'), {
            type: 'doughnut',
            data: {
                labels: ['VNPAY', 'COD'],
                datasets: [{
                    data: [66516490, 61680985], // dữ liệu mẫu
                    backgroundColor: ['#4e73df', '#36b9cc']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Hiển thị số người dùng hiện tại
        document.getElementById('currentUsersCount').innerText = "{{ $currentUsers }}";

        // ========== Country Purchased ==========
        new Chart(document.getElementById('countryChart'), {
            type: 'bar',
            data: {
                labels: ['India', 'USA', 'Turkey', 'UK', 'Poland'],
                datasets: [{
                    label: '% Purchase',
                    data: [18, 12, 8, 6, 5],
                    backgroundColor: '#36b9cc'
                }]
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle order details
            const toggleButtons = document.querySelectorAll('[data-kt-order-id]');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-kt-order-id');
                    const detailsRow = document.getElementById('kt_order_details_' + orderId);
                    const icon = this.querySelector('i');

                    if (detailsRow.classList.contains('d-none')) {
                        // mở chi tiết
                        detailsRow.classList.remove('d-none');
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    } else {
                        // đóng chi tiết
                        detailsRow.classList.add('d-none');
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    }
                });
            });

            // Filter functionality
            const statusFilter = document.getElementById('kt_filter_status');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    filterOrders();
                });
            }

            // Search functionality
            const searchInput = document.getElementById('kt_search_orders');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    filterOrders();
                });
            }

            function filterOrders() {
                const statusValue = statusFilter ? statusFilter.value : 'Show All';
                const searchValue = searchInput ? searchInput.value.toLowerCase() : '';

                const rows = document.querySelectorAll('#kt_recent_orders_table tbody > tr');

                rows.forEach(row => {
                    if (row.id && row.id.startsWith('kt_order_details_')) {
                        // This is a details row, skip it
                        return;
                    }

                    let showRow = true;
                    const statusCell = row.querySelector('td:nth-child(5)');
                    const orderCodeCell = row.querySelector('td:first-child');
                    const customerCell = row.querySelector('td:nth-child(3)');

                    // Filter by status
                    if (statusValue !== 'Show All') {
                        const statusText = statusCell.textContent.trim().toLowerCase();
                        if (!statusText.includes(statusValue)) {
                            showRow = false;
                        }
                    }

                    // Filter by search text
                    if (searchValue && showRow) {
                        const orderCode = orderCodeCell.textContent.toLowerCase();
                        const customer = customerCell.textContent.toLowerCase();

                        if (!orderCode.includes(searchValue) && !customer.includes(searchValue)) {
                            showRow = false;
                        }
                    }

                    if (showRow) {
                        row.style.display = '';
                        // Also show the details row if it's expanded
                        const orderId = row.querySelector('button').getAttribute('data-kt-order-id');
                        const detailsRow = document.getElementById('kt_order_details_' + orderId);
                        if (!detailsRow.classList.contains('d-none')) {
                            detailsRow.style.display = '';
                        }
                    } else {
                        row.style.display = 'none';
                        // Also hide the details row
                        const orderId = row.querySelector('button').getAttribute('data-kt-order-id');
                        const detailsRow = document.getElementById('kt_order_details_' + orderId);
                        detailsRow.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <style>
        /* Order details container */
        .order-details-container {
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 0.475rem;
            margin: 0.75rem;
        }

        .order-details-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #3f4254;
        }

        /* Order item card */
        .order-items-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .order-item-card {
            display: grid;
            grid-template-columns: 70px 1fr;
            grid-template-rows: auto auto;
            gap: 0.75rem 1rem;
            padding: 1rem;
            background-color: white;
            border-radius: 0.475rem;
            box-shadow: 0 0.1rem 0.5rem rgba(0, 0, 0, 0.05);
            align-items: start;
        }

        .order-item-image {
            grid-row: 1 / 3;
            width: 70px;
            height: 70px;
            border-radius: 0.475rem;
            overflow: hidden;
            background-color: #f5f8fa;
        }

        .order-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .order-item-info {
            grid-column: 2 / 5;
        }

        .order-item-name {
            font-weight: 600;
            color: #3f4254;
            margin-bottom: 0.25rem;
        }

        .order-item-sku {
            font-size: 0.85rem;
            color: #7e8299;
        }

        .order-item-quantity,
        .order-item-price,
        .order-item-total {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .order-item-quantity .label,
        .order-item-price .label,
        .order-item-total .label {
            font-size: 0.8rem;
            color: #7e8299;
            margin-bottom: 0.25rem;
        }

        .order-item-quantity .value,
        .order-item-price .value,
        .order-item-total .value {
            font-weight: 600;
            color: #3f4254;
        }

        /* Responsive design */
        @media (min-width: 768px) {
            .order-item-card {
                grid-template-columns: 70px 1fr auto auto auto;
                grid-template-rows: auto;
            }

            .order-item-info {
                grid-column: 2 / 3;
            }

            .order-item-quantity,
            .order-item-price,
            .order-item-total {
                align-items: flex-end;
            }
        }

        @media (max-width: 767px) {
            .order-item-card {
                grid-template-columns: 60px 1fr;
                grid-template-rows: auto auto auto;
                gap: 0.5rem;
            }

            .order-item-info {
                grid-column: 2 / 3;
            }

            .order-item-quantity,
            .order-item-price,
            .order-item-total {
                grid-column: 1 / 3;
                flex-direction: row;
                justify-content: space-between;
                padding-top: 0.5rem;
                border-top: 1px solid #f5f8fa;
            }
        }

        /* Fix alignment for nested tables */
        #kt_recent_orders_table>tbody>tr[id^="kt_order_details_"]>td {
            border-top: none;
            padding: 0;
        }
    </style>
@endpush
