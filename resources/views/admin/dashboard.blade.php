@extends('layouts.admin')

@section('title', 'Dashboard')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@section('content')
    <style>
        .stat-card {
            min-height: 120px;
            /* Chiều cao tối thiểu */
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 15px;
        }

        .icon-box {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
        }

        .stat-content {
            flex: 1;
        }
    </style>

    <div class="container py-3">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="monthYear" class="fw-bold mb-2">Lọc theo khoảng thời gian</label>
                        <input type="text" name="filterDatetime" id="filterDatetime" class="form-control"
                            placeholder="DD/MM/YYYY - DD/MM/YYYY" readonly>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <button type="button" class="btn btn-primary" onclick="makeFilterTimerange()">Lọc</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Làm mới</a>
                    </div>
                </div>
            </div>
        </div>

        @php
        $filterDatetime = request('filterDatetime', '');
        @endphp

        {{-- Các card thống kê --}}
        <div class="row g-3">

            {{-- Thành viên đăng ký --}}
            <div class="col-md-3 col-sm-6">
                @php $iconUser = '<i class="fas fa-users"></i>'; @endphp
                @if(isset($userFilterRange) && $filterDatetime != '')
                    <div class="card shadow-sm mb-3 stat-card">
                        <div
                            class="icon-box bg-light rounded-circle text-purple fs-3 d-flex align-items-center justify-content-center me-3">
                            {!! $iconUser !!}
                        </div>
                        <div class="stat-content">
                            <div class="fw-bold text-purple">Thành viên đăng ký</div>
                            <div class="display-6 fw-bold">{{ $userFilterRange }}</div>
                            <span class="badge bg-light-purple text-purple mt-1">{{ $filterDatetime }}</span>
                        </div>
                    </div>
                @endif
                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-purple fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconUser !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersAll }}</div>
                        <span class="badge bg-light-purple text-purple mt-1">Toàn thời gian</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-purple fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconUser !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersMonth }}</div>
                        <span class="badge bg-light-purple text-purple mt-1">Tháng
                            {{ now()->format('m') }}</span>
                    </div>
                </div>
                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-purple fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconUser !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersWeek }}</div>
                        <span class="badge bg-light-purple text-purple mt-1">Tuần này</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-purple fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconUser !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersToday }}</div>
                        <span class="badge bg-light-purple text-purple mt-1">Hôm nay</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                {{-- Đơn hàng đã bán --}}
                @php $iconOrder = '<i class="fas fa-shopping-cart"></i>'; @endphp
                @if(isset($ordersFilterRange) && $filterDatetime != '')
                    <div class="card shadow-sm mb-3 stat-card">
                        <div
                            class="icon-box bg-light rounded-circle text-primary fs-3 d-flex align-items-center justify-content-center me-3">
                            {!! $iconOrder !!}
                        </div>
                        <div class="stat-content">
                            <div class="fw-bold text-primary">Đơn hàng đã bán</div>
                            <div class="display-6 fw-bold">{{ $ordersFilterRange }}</div>
                            <span class="badge bg-light-primary text-primary mt-1">{{ $filterDatetime }}</span>
                        </div>
                    </div>
                @endif
                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-primary fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconOrder !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersAll }}</div>
                        <span class="badge bg-light-primary text-primary mt-1">Toàn thời gian</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-primary fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconOrder !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersMonth }}</div>
                        <span class="badge bg-light-primary text-primary mt-1">Tháng
                            {{ now()->format('m') }}</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-primary fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconOrder !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersWeek }}</div>
                        <span class="badge bg-light-primary text-primary mt-1">Tuần này</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-primary fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconOrder !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersToday }}</div>
                        <span class="badge bg-light-primary text-primary mt-1">Hôm nay</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                {{-- Doanh thu --}}
                @php $iconMoney = '<i class="fas fa-dollar-sign"></i>'; @endphp
                @if(isset($revenueFilterRange) && $filterDatetime != '')
                    <div class="card shadow-sm mb-3 stat-card">
                        <div
                            class="icon-box bg-light rounded-circle text-warning fs-3 d-flex align-items-center justify-content-center me-3">
                            {!! $iconMoney !!}
                        </div>
                        <div class="stat-content">
                            <div class="fw-bold text-warning">Doanh thu đơn hàng</div>
                            <div class="display-6 fw-bold">{{ $revenueFilterRange }}</div>
                            <span class="badge bg-light-warning text-warning mt-1">{{ $filterDatetime }}</span>
                        </div>
                    </div>
                @endif
                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-warning fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconMoney !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueAll) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-1">Toàn thời gian</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-warning fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconMoney !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueMonth) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-1">Tháng
                            {{ now()->format('m') }}</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-warning fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconMoney !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueWeek) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-1">Tuần này</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-warning fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconMoney !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueToday) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-1">Hôm nay</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                {{-- Đơn đã thanh toán --}}
                @php $iconPaid = '<i class="fas fa-receipt"></i>'; @endphp
                @if(isset($paidFilterRange) && $filterDatetime != '')
                    <div class="card shadow-sm mb-3 stat-card">
                        <div
                            class="icon-box bg-light rounded-circle text-danger fs-3 d-flex align-items-center justify-content-center me-3">
                            {!! $iconPaid !!}
                        </div>
                        <div class="stat-content">
                            <div class="fw-bold text-danger">Đơn đã thanh toán</div>
                            <div class="display-6 fw-bold">{{ $paidFilterRange }}</div>
                            <span class="badge bg-light-danger text-danger mt-1">{{ $filterDatetime }}</span>
                        </div>
                    </div>
                @endif
                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-danger fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconPaid !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidAll }}</div>
                        <span class="badge bg-light-danger text-danger mt-1">Toàn thời gian</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-danger fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconPaid !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidMonth }}</div>
                        <span class="badge bg-light-danger text-danger mt-1">Tháng
                            {{ now()->format('m') }}</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-danger fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconPaid !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidWeek }}</div>
                        <span class="badge bg-light-danger text-danger mt-1">Tuần này</span>
                    </div>
                </div>

                <div class="card shadow-sm mb-3 stat-card">
                    <div
                        class="icon-box bg-light rounded-circle text-danger fs-3 d-flex align-items-center justify-content-center me-3">
                        {!! $iconPaid !!}
                    </div>
                    <div class="stat-content">
                        <div class="fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidToday }}</div>
                        <span class="badge bg-light-danger text-danger mt-1">Hôm nay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-2">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div class="row">
                <div class="col-6 mb-4">
                    <div class="card mt-5">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">THỐNG KÊ DOANH THU THEO THÁNG</h5>
                            <div>
                                <select name="monthOrderRevenueChart" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ (int) request('monthOrderRevenueChart', now()->month) === $m ? 'selected' : '' }}>
                                            Tháng {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="ordersChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="card mt-5">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">THỐNG KÊ TRẠNG THÁI ĐƠN HÀNG THEO THÁNG</h5>
                            <div>
                                <select name="monthOrderStatusChart" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ (int) 
                                        request('monthOrderStatusChart', now()->month) === $m ? 'selected' : '' }}>
                                                                    Tháng {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                                                </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container py-2">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="min-height: 50px">
                        <h5 class="mb-0">SẢN PHẨM BÁN CHẠY NHẤT</h5>
                        <div>
                            {{-- <select name="monthOrderRevenueChart" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (int) request('monthOrderRevenueChart', now()->month) === $m ? 'selected' : '' }}>
                                        Tháng {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor
                            </select> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">                                    
                                        <th class="p-0 w-75px pb-1">Sản phẩm</th>
                                        <th class="ps-0 min-w-140px"></th>
                                        <th class="min-w-140px p-0 text-end">Đơn giá</th>                                     
                                        <th class="p-0 min-w-140px text-end">Số lượng</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->

                                <!--begin::Table body-->
                                <tbody>
                                    @if (isset($bestSellingProducts))
                                        @foreach ($bestSellingProducts as $product)
                                            <tr>
                                                <td>                                    
                                                    <img src="{{ asset('storage/' . $product->image) }}" style="width:75px;height:75px;object-fit:cover" alt=""/>                             
                                                </td>
                                                <td class="ps-0">
                                                    <a href="{{ route('client.products.show', $product->slug) }}" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">{{ $product->name }}</a>
                                                    <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">{{ $product->name }}</span>
                                                </td>
                                                <td class="text-end">                                            
                                                    <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">{{ $product->sale_price }}</span>
                                                </td>    
                                                <td class="text-end">                                            
                                                    <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">{{ $product->total_sold }}</span>
                                                </td>                                       
                                            </tr>  
                                        @endforeach    
                                    @endif
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                </div>
            </div>
             <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="min-height: 50px">
                        <h5 class="mb-0">DANH MỤC BÁN CHẠY NHẤT</h5>
                        <div>
                            {{-- <select name="monthOrderRevenueChart" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (int) request('monthOrderRevenueChart', now()->month) === $m ? 'selected' : '' }}>
                                        Tháng {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor
                            </select> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">    
                                        <th class="p-0 w-75px pb-1">Danh mục</th>                                
                                        <th class="ps-0 min-w-140px"></th>
                                        <th class="p-0 min-w-140px text-end">Số lượng bán</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->

                                <!--begin::Table body-->
                                <tbody>
                                    @if (isset($bestSellingCategories))
                                        @foreach ($bestSellingCategories as $category)
                                            <tr>
                                                <td>                                    
                                                    <img src="{{ asset('storage/' . $category->image) }}" style="width:75px;height:75px;object-fit:cover" alt=""/>                             
                                                </td>
                                                <td class="ps-0">
                                                    <a href="javascript:;" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">{{ $category->name }}</a>
                                                </td>
                                                <td class="text-end">                                            
                                                    <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">{{ $category->total_sold }}</span>
                                                </td>                                      
                                            </tr>  
                                        @endforeach    
                                    @endif
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');

        const ordersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Doanh thu (VND)',
                        data: @json($chartRevenue),
                        backgroundColor: 'rgba(153, 102, 255, 0.7)'
                    },
                    {
                        label: 'Thuế (VND)',
                        data: @json($chartTax),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString() + 'đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return value.toLocaleString() + 'đ';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
        const statusChart = new Chart(ctxStatus, {
            type: 'bar', // Hoặc 'doughnut' nếu muốn hình tròn
            data: {
                labels: @json($chartStatusLabels),
                datasets: [{
                    label: 'Số lượng đơn hàng',
                    data: @json($chartStatusCounts),
                    backgroundColor: [
                        '#6c757d', '#0d6efd', '#0dcaf0', '#198754',
                        '#dc3545', '#ffc107', '#fd7e14'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    @php
    $defaultDateRange = [];
    if ($filterDatetime != '') {
        $dates = explode('-', $filterDatetime);

        if (count($dates) == 2) {
            // Xóa khoảng trắng thừa
            $start = trim($dates[0]);
            $end   = trim($dates[1]);

            // Chuyển d/m/Y => Y-m-d
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $start)->format('Y-m-d');
            $endDate   = \Carbon\Carbon::createFromFormat('d/m/Y', $end)->format('Y-m-d');

            $defaultDateRange = [$startDate, $endDate];
        }
    }
    @endphp

    <script>
        flatpickr("#filterDatetime", {
            mode: "range",
            dateFormat: "d/m/Y",
            locale: {
                weekdays: {
                    shorthand: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    longhand: [
                        'Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư',
                        'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'
                    ]
                },
                months: {
                    shorthand: [
                        'Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6',
                        'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'
                    ],
                    longhand: [
                        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4',
                        'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
                        'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                    ]
                },
            },
            @if(!empty($defaultDateRange))
            defaultDate: [
                new Date("{{ $defaultDateRange[0] }}"),
                new Date("{{ $defaultDateRange[1] }}")
            ],
            @endif
            onReady: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    let start = instance.formatDate(selectedDates[0], "d/m/Y");
                    let end = instance.formatDate(selectedDates[1], "d/m/Y");
                    instance.input.value = start + " - " + end;
                }
            },
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    let start = instance.formatDate(selectedDates[0], "d/m/Y");
                    let end = instance.formatDate(selectedDates[1], "d/m/Y");
                    instance.input.value = start + " - " + end;
                }
            },
            // onChange: function (selectedDates, dateStr) {
            //     // const url = new URL(window.location.href);
            //     // url.searchParams.set('filterDatetime', dateStr);
            //     // window.location.href = url.toString();
            // }
        });

        function makeFilterTimerange() {
            const currDateStr = document.getElementById("filterDatetime").value;
            const url = new URL(window.location.href);
            url.searchParams.set('filterDatetime', currDateStr);
            window.location.href = url.toString();
        }
    </script>
@endsection