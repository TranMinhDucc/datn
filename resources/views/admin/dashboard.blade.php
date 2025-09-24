@extends('layouts.admin')

@section('content')
    <!-- Header with gradient background -->
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-8"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-white fw-bold fs-2 flex-column justify-content-center my-0">
                    📊 Bảng điều khiển
                </h1>
                {{-- <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-2">
                    <li class="breadcrumb-item">
                        <a href="../../../index.html" class="text-white-75 text-hover-white">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white-50 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white-75">Bảng điều khiển</li>
                </ul> --}}
            </div>
            <div class="card-toolbar">
                <div id="daterange" class="btn btn-lg btn-white d-flex align-items-center px-6 shadow-sm"
                    style="border-radius: 15px;">
                    <div class="text-white fw-bold">
                        <span id="daterange-text">
                            {{ request('daterange') ?? now()->subDays(30)->format('d/m/Y') . ' - ' . now()->format('d/m/Y') }}
                        </span>
                    </div>
                    <i class="ki-duotone ki-calendar-8 text-primary lh-0 fs-2 ms-3 me-0"></i>
                </div>
            </div>
            <input type="hidden" id="daterange-value" value="{{ request('daterange') }}">
        </div>
    </div>

    <div id="kt_app_content_container" class="app-container container-xxl" style="margin-top: -40px;">
        <!-- Stats Cards with modern design -->
        <div class="row g-4 mb-6">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-lg hover-elevate-up h-100"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                    <div class="card-body p-6 d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2 fw-semibold">Khách hàng mới</h6>
                            <h1 class="text-white fw-bold mb-1">{{ number_format($newCustomers) }}</h1>
                            <small class="text-white-50">+12% từ tháng trước</small>
                        </div>
                        <div class="symbol symbol-60px">
                            <div class="symbol-label bg-white-10" style="border-radius: 15px;">
                                <i class="bi bi-person-plus fs-1 text-black"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-lg hover-elevate-up h-100"
                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px;">
                    <div class="card-body p-6 d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2 fw-semibold">Tổng người dùng</h6>
                            <h1 class="text-white fw-bold mb-1">{{ number_format($totalUsersAll) }}</h1>

                            @if (request()->get('daterange'))
                                <small class="text-white-50">
                                    +{{ $newCustomers }} người dùng mới từ {{ $startDate->format('d/m/Y') }} đến
                                    {{ $endDate->format('d/m/Y') }}
                                </small>
                            @else
                                <small class="text-white-50">Tổng toàn hệ thống</small>
                            @endif
                        </div>
                        <div class="symbol symbol-60px">
                            <div class="symbol-label bg-white-10" style="border-radius: 15px;">
                                <i class="bi bi-people fs-1 text-black"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-lg hover-elevate-up h-100"
                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                    <div class="card-body p-6 d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2 fw-semibold">Tổng đơn hàng</h6>
                            <h1 class="text-white fw-bold mb-1">{{ number_format($totalOrdersAll) }}</h1>

                            @if (request()->get('daterange'))
                                <small class="text-white-50">
                                    Có {{ number_format(array_sum($orderCounts)) }} đơn từ {{ $startDate->format('d/m/Y') }}
                                    đến {{ $endDate->format('d/m/Y') }}
                                </small>
                            @else
                                <small class="text-white-50">Tổng toàn hệ thống</small>
                            @endif
                        </div>
                        <div class="symbol symbol-60px">
                            <div class="symbol-label bg-white-10" style="border-radius: 15px;">
                                <i class="bi bi-box-seam fs-1 text-black"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-lg hover-elevate-up h-100"
                    style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 20px;">
                    <div class="card-body p-6 d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-75 mb-2 fw-semibold">Doanh thu</h6>
                            <h1 class="text-white fw-bold mb-1">{{ number_format($revenue, 0, ',', '.') }} ₫</h1>
                            <small class="text-white-75">
                                Khoảng chọn: {{ number_format($revenueInRange, 0, ',', '.') }} ₫
                            </small>
                        </div>
                        <div class="symbol symbol-60px">
                            <div class="symbol-label bg-white-10" style="border-radius: 15px;">
                                <i class="bi bi-currency-dollar fs-1 text-black"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-6">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800 fs-3">📈 Nguồn lưu lượng truy cập</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Phân tích theo thời gian</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div
                            style="background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%); border-radius: 15px; padding: 20px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column w-100 text-center">
                            <span class="card-label fw-bold text-gray-800 fs-4">🛍️ Tổng quan đơn hàng</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Phân bố theo trạng thái</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border-radius: 15px;">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity Charts -->
        <div class="row g-4 mb-6">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800 fs-3">👥 Hoạt động của người dùng</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Theo dõi sự tham gia</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div
                            style="background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%); border-radius: 15px; padding: 20px;">
                            <canvas id="userActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column w-100 text-center">
                            <span class="card-label fw-bold text-gray-800 fs-4">🎯 Khách hàng mới</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Mỗi ngày trong tuần</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div
                            style="background: linear-gradient(135deg, #fffacd 0%, #ffffff 100%); border-radius: 15px; padding: 20px;">
                            <canvas id="newCustomersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Payment Charts -->
        <div class="row g-4 mb-6">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800 fs-3">📊 Tổng khách hàng theo thời gian</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Tăng trưởng liên tục</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div
                            style="background: linear-gradient(135deg, #e6f3ff 0%, #ffffff 100%); border-radius: 15px; padding: 20px;">
                            <canvas id="totalCustomersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column w-100 text-center">
                            <span class="card-label fw-bold text-gray-800 fs-4">💳 Phương thức thanh toán</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Tỷ lệ sử dụng</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div
                            style="background: linear-gradient(135deg, #faf0e6 0%, #ffffff 100%); border-radius: 15px; padding: 20px;">
                            <canvas id="paymentMethodsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotion Card & Recent Orders -->
        <div class="row g-4 mb-6">
            <!-- Promotion Card -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-lg h-100"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-8">
                        <div class="mb-4">
                            <div class="symbol symbol-100px mb-4">
                                <div class="symbol-label bg-white-10" style="border-radius: 50px;">
                                    <i class="bi bi-rocket-takeoff fs-2x text-black"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold text-white mb-3">
                                Khám phá tính năng mới!
                            </h2>
                            <p class="text-white-75 fs-6 mb-4">
                                Ứng dụng eCommerce với nhiều tính năng nâng cao đang chờ bạn khám phá
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-white btn-lg px-6"
                                style="border-radius: 15px;">
                                <i class="bi bi-eye me-2"></i>Cài đặt hệ thống ngay
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-white btn-lg px-6"
                                style="border-radius: 15px;">
                                <i class="bi bi-plus-circle me-2"></i>Sản phẩm mới
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h3 class="card-title fw-bold text-gray-800 fs-3">
                                    🛒 Đơn hàng gần đây
                                </h3>
                                <span class="text-muted fw-semibold fs-7">
                                    Trung bình: {{ $recentOrders->count() }} đơn
                                </span>
                            </div>

                            <div class="d-flex gap-3">
                                <select class="form-select form-select-sm" style="border-radius: 10px;"
                                    id="kt_filter_status">
                                    <option value="Show All" selected>Tất cả trạng thái</option>
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="confirmed">Đã xác nhận</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="shipping">Đang giao hàng</option>
                                    <option value="completed">Hoàn thành</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>

                                <div class="position-relative">
                                    <i
                                        class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" id="kt_search_orders"
                                        class="form-control form-control-sm ps-10" placeholder="Tìm kiếm..."
                                        style="border-radius: 10px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_recent_orders_table">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">Mã đơn</th>
                                        <th class="text-end min-w-100px">Ngày tạo</th>
                                        <th class="text-end min-w-125px">Khách hàng</th>
                                        <th class="text-end min-w-100px">Tổng tiền</th>
                                        <th class="text-end min-w-100px">Trạng thái</th>
                                        <th class="text-end min-w-50px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    @foreach ($recentOrders as $order)
                                        <tr class="order-row" style="transition: all 0.3s ease;">
                                            <td>
                                                <a href="#" class="text-primary text-hover-primary fw-bold">
                                                    #{{ $order->order_code }}
                                                </a>
                                            </td>
                                            <td class="text-end">{{ $order->created_at->diffForHumans() }}</td>
                                            <td class="text-end">
                                                <span class="text-gray-800 fw-semibold">
                                                    {{ $order->user->fullname ?? 'Khách vãng lai' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-success fw-bold">
                                                    {{ number_format($order->total_amount, 0, ',', '.') }} ₫
                                                </span>
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
                                                <span class="badge py-2 px-4 fs-7 badge-light-{{ $badgeColor }}"
                                                    style="border-radius: 10px;">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-light-primary btn-active-primary toggle"
                                                    data-kt-order-id="{{ $order->id }}"
                                                    style="border-radius: 10px; width: 35px; height: 35px;">
                                                    <i class="bi bi-plus fs-5"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Order details row -->
                                        <tr class="d-none" id="kt_order_details_{{ $order->id }}">
                                            <td colspan="6" class="p-0">
                                                <div class="bg-light-primary p-6 m-3" style="border-radius: 15px;">
                                                    <h6 class="fw-bold mb-4 text-primary">
                                                        📦 Chi tiết đơn hàng #{{ $order->order_code }}
                                                    </h6>

                                                    <div class="row g-3">
                                                        @foreach ($order->items as $item)
                                                            <div class="col-12">
                                                                <div class="card border-0 shadow-sm"
                                                                    style="border-radius: 12px;">
                                                                    <div class="card-body p-4">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="symbol symbol-60px me-4">
                                                                                <img src="{{ $item->image_url ?? '/placeholder.png' }}"
                                                                                    alt="{{ $item->product_name }}"
                                                                                    class="rounded-3 object-fit-cover"
                                                                                    style="width: 60px; height: 60px;">
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <h6 class="fw-bold text-gray-800 mb-1">
                                                                                    {{ \Illuminate\Support\Str::limit($item->product_name, 40, '...') }}
                                                                                </h6>
                                                                                <small class="text-muted">SKU:
                                                                                    {{ $item->sku ?? 'N/A' }}</small>
                                                                            </div>
                                                                            <div class="text-end">
                                                                                <div class="text-muted fs-7">Số lượng</div>
                                                                                <div class="fw-bold text-primary fs-6">
                                                                                    {{ $item->quantity }}</div>
                                                                            </div>
                                                                            <div class="text-end ms-4">
                                                                                <div class="text-muted fs-7">Đơn giá</div>
                                                                                <div class="fw-bold fs-6">
                                                                                    {{ number_format($item->price, 0, ',', '.') }}
                                                                                    ₫</div>
                                                                            </div>
                                                                            <div class="text-end ms-4">
                                                                                <div class="text-muted fs-7">Thành tiền
                                                                                </div>
                                                                                <div class="fw-bold text-success fs-5">
                                                                                    {{ number_format($item->total_price, 0, ',', '.') }}
                                                                                    ₫
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products & Low Stock -->
        <div class="row g-4">
            <!-- Top Products -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title fw-bold text-gray-800 fs-3 w-100 text-center">
                            🔥 Top sản phẩm bán chạy
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @forelse($topProducts as $index => $product)
                            <div class="d-flex align-items-center p-4 hover-elevate-up border-bottom border-light-primary"
                                style="transition: all 0.3s ease;">
                                <div class="symbol symbol-50px me-4">
                                    <div class="symbol-label bg-primary text-white fw-bold fs-4"
                                        style="border-radius: 15px;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="symbol symbol-50px me-4">
                                    <img src="{{ $product->image_url ?? '/images/products/placeholder.png' }}"
                                        alt="{{ $product->product_name }}" class="rounded-3 object-fit-cover"
                                        style="width: 50px; height: 50px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold text-gray-800 mb-1">
                                        {{ \Illuminate\Support\Str::limit($product->product_name, 30, '...') }}
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-light-success fs-8 me-2" style="border-radius: 8px;">
                                            {{ $product->total_sold }} lượt bán
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success fs-5">
                                        {{ number_format($product->total_revenue, 0, ',', '.') }} ₫
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="bi bi-graph-up fs-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có dữ liệu bán hàng</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-lg h-100" style="border-radius: 20px;">
                    <div class="card-header bg-transparent border-0 pt-6">
                        <h3 class="card-title fw-bold text-gray-800 fs-3 w-100 text-center">
                            ⚠️ Biến thể tồn kho thấp
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @forelse($lowStockVariants as $variant)
                            <a href="{{ route('admin.inventory.index', ['sku' => $variant->sku]) }}"
                                class="d-flex align-items-center p-4 hover-elevate-up border-bottom border-light-warning text-decoration-none"
                                style="transition: all 0.3s ease;">
                                <div class="symbol symbol-50px me-4">
                                    <img src="{{ $variant->product && $variant->product->image
                                        ? asset('storage/' . $variant->product->image)
                                        : asset('/images/products/placeholder.png') }}"
                                        alt="{{ $variant->product->name }}" class="rounded-3 object-fit-cover"
                                        style="width: 50px; height: 50px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold text-gray-800 mb-1">
                                        {{ \Illuminate\Support\Str::limit($variant->product->name, 25, '...') }}
                                    </h6>
                                    <small class="text-muted d-block">
                                        SKU: {{ $variant->sku ?? ($variant->product->sku ?? 'Chưa có') }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $variant->color ?? '' }} {{ $variant->size ?? '' }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span
                                        class="badge {{ $variant->quantity <= 3 ? 'badge-light-danger' : 'badge-light-warning' }} fs-7"
                                        style="border-radius: 10px;">
                                        Còn: {{ $variant->quantity }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10">
                                <i class="bi bi-check-circle fs-3x text-success mb-3"></i>
                                <p class="text-muted">Tất cả sản phẩm đều có đủ tồn kho</p>
                            </div>
                        @endforelse

                        @if ($lowStockVariants->hasPages())
                            <div class="p-4">
                                {{ $lowStockVariants->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
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
                $('#daterange-text').text(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                $('#daterange-value').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            }

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: false,
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
                    'Toàn thời gian': [moment('2000-01-01'), moment()]
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

        // Enhanced Chart.js configurations with modern styling
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#6c757d';

        // Sales Chart with gradient
        const salesCtx = document.getElementById('salesChart');
        const salesGradient1 = salesCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        salesGradient1.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        salesGradient1.addColorStop(1, 'rgba(102, 126, 234, 0.1)');

        const salesGradient2 = salesCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        salesGradient2.addColorStop(0, 'rgba(231, 74, 59, 0.8)');
        salesGradient2.addColorStop(1, 'rgba(231, 74, 59, 0.1)');

        const salesGradient3 = salesCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        salesGradient3.addColorStop(0, 'rgba(28, 200, 138, 0.8)');
        salesGradient3.addColorStop(1, 'rgba(28, 200, 138, 0.1)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Giới thiệu (Referral)',
                    data: @json($referral),
                    borderColor: '#667eea',
                    backgroundColor: salesGradient1,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Trực tiếp',
                    data: @json($direct),
                    borderColor: '#e74a3b',
                    backgroundColor: salesGradient2,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#e74a3b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Mạng xã hội',
                    data: @json($social),
                    borderColor: '#1cc88a',
                    backgroundColor: salesGradient3,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#1cc88a',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 10,
                        borderWidth: 0,
                        displayColors: true,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#8c98a4'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(140, 152, 164, 0.1)'
                        },
                        ticks: {
                            color: '#8c98a4'
                        }
                    }
                }
            }
        });

        // Orders Chart with modern doughnut design
        new Chart(document.getElementById('ordersChart'), {
            type: 'doughnut',
            data: {
                labels: @json($orderLabels),
                datasets: [{
                    data: @json($orderCounts),
                    backgroundColor: [
                        '#667eea', '#f093fb', '#4facfe', '#43e97b',
                        '#fa709a', '#fee140', '#a8edea', '#d299c2',
                        '#ffecd2', '#fcb69f', '#a8e6cf', '#dcedc1'
                    ],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 10,
                        padding: 12
                    }
                }
            }
        });

        // Total Customers Chart
        const totalCustomersCtx = document.getElementById('totalCustomersChart');
        const customerGradient = totalCustomersCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        customerGradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
        customerGradient.addColorStop(1, 'rgba(54, 162, 235, 0.1)');

        new Chart(totalCustomersCtx, {
            type: 'line',
            data: {
                labels: @json($totalCustomerLabels),
                datasets: [{
                    label: 'Tổng khách hàng',
                    data: @json($totalCustomerCounts),
                    borderColor: '#36a2eb',
                    backgroundColor: customerGradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#36a2eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 10,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#8c98a4'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(140, 152, 164, 0.1)'
                        },
                        ticks: {
                            color: '#8c98a4'
                        }
                    }
                }
            }
        });

        // User Activity Chart
        new Chart(document.getElementById('userActivityChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Người dùng hoạt động',
                    data: @json($active),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }, {
                    label: 'Người dùng không hoạt động',
                    data: @json($inactive),
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.1)',
                    borderDash: [5, 5],
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#f6c23e',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(140, 152, 164, 0.1)'
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // New Customers Chart
        new Chart(document.getElementById('newCustomersChart'), {
            type: 'bar',
            data: {
                labels: @json($newCustomerLabels),
                datasets: [{
                    label: 'Khách hàng mới',
                    data: @json($newCustomerCounts),
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: '#36a2eb',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(140, 152, 164, 0.1)'
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

       new Chart(document.getElementById('paymentMethodsChart'), {
    type: 'doughnut',
    data: {
        labels: ['Ví MoMo', 'Thanh toán khi nhận hàng'],
        datasets: [{
            data: @json($paymentCounts),
            backgroundColor: ['#4e73df', '#36b9cc'],
            borderWidth: 0,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 15,
                    usePointStyle: true
                }
            }
        }
    }
});

        // Orders table functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('[data-kt-order-id]');
            const statusFilter = document.getElementById('kt_filter_status');
            const searchInput = document.getElementById('kt_search_orders');

            // Toggle order details
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-kt-order-id');
                    const detailsRow = document.getElementById('kt_order_details_' + orderId);
                    const icon = this.querySelector('i');
                    const parentRow = this.closest('tr');

                    if (detailsRow.classList.contains('d-none')) {
                        detailsRow.classList.remove('d-none');
                        icon.classList.remove('bi-plus');
                        icon.classList.add('bi-dash');
                        parentRow.style.backgroundColor = '#f8f9ff';
                    } else {
                        detailsRow.classList.add('d-none');
                        icon.classList.remove('bi-dash');
                        icon.classList.add('bi-plus');
                        parentRow.style.backgroundColor = '';
                    }
                });
            });

            // Filter functionality
            function filterOrders() {
                const statusValue = statusFilter ? statusFilter.value : 'Show All';
                const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
                const rows = document.querySelectorAll('#kt_recent_orders_table tbody > tr.order-row');

                rows.forEach(row => {
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

                    // Show/hide row and its details
                    const button = row.querySelector('button[data-kt-order-id]');
                    const orderId = button.getAttribute('data-kt-order-id');
                    const detailsRow = document.getElementById('kt_order_details_' + orderId);

                    if (showRow) {
                        row.style.display = '';
                        if (!detailsRow.classList.contains('d-none')) {
                            detailsRow.style.display = '';
                        }
                    } else {
                        row.style.display = 'none';
                        detailsRow.style.display = 'none';
                    }
                });
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', filterOrders);
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', filterOrders);
            }

            // Add hover effects to order rows
            const orderRows = document.querySelectorAll('.order-row');
            orderRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9ff';
                    this.style.transform = 'translateY(-2px)';
                });

                row.addEventListener('mouseleave', function() {
                    if (!this.querySelector('.bi-dash')) {
                        this.style.backgroundColor = '';
                    }
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>

    <style>
        /* Modern styling improvements */
        .hover-elevate-up {
            transition: all 0.3s ease;
        }

        .hover-elevate-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Enhanced daterange picker styling */
        #daterange {
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        #daterange:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Header improvements */
        .page-heading {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .symbol-label {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Custom card styling */
        .card {
            transition: all 0.3s ease;
        }

        /* Modern button styling */
        .btn {
            transition: all 0.3s ease;
            font-weight: 500;
        }

        /* Enhanced table styling */
        .table thead th {
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f1f3f4;
        }

        .table tbody tr:hover {
            background-color: #f8f9ff;
        }

        /* Modern badge styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Chart container styling */
        #salesChart,
        #ordersChart,
        #userActivityChart,
        #newCustomersChart,
        #totalCustomersChart,
        #paymentMethodsChart {
            height: 300px !important;
        }

        /* Orders chart specific styling for better legend display */
        #ordersChart {
            height: 350px !important;
        }

        /* Button centering fix */
        .btn.toggle {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .btn.toggle i {
            margin: 0 !important;
            line-height: 1 !important;
        }

        /* Custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem !important;
            }

            .card-header {
                padding: 1rem 1rem 0 1rem !important;
            }

            #kt_app_toolbar {
                padding: 1rem 0 !important;
            }

            .page-heading {
                font-size: 1.5rem !important;
            }
        }

        /* Loading animation */
        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .loading-shimmer {
            position: relative;
            overflow: hidden;
        }

        .loading-shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 1.5s infinite;
        }
    </style>
@endpush
