@extends('layouts.admin')

@section('title', 'Dashboard')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Flatpickr plugin để chọn tháng -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

@section('content')
  <div class="container py-2">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <label for="monthYear">Lọc theo tháng/năm</label>
                    <input type="text" name="filterDatetime" id="filterDatetime" class="form-control"
                        placeholder="MM/YYYY" readonly>
                </div>
            </div>
        </div>
        <div class="row g-4">
            {{-- Thành viên đăng ký --}}
            @php $iconUser = '<i class="fas fa-users"></i>'; @endphp
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-purple">{!! $iconUser !!}</div>
                        <div class="fs-4 fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersAll }}</div>
                        <span class="badge bg-light-purple text-purple mt-2">Toàn thời gian</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-purple">{!! $iconUser !!}</div>
                        <div class="fs-4 fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersMonth }}</div>
                        <span class="badge bg-light-purple text-purple mt-2">Tháng
                            {{ request('filterDatetime', now()->format('m')) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-purple">{!! $iconUser !!}</div>
                        <div class="fs-4 fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersWeek }}</div>
                        <span class="badge bg-light-purple text-purple mt-2">Tuần này</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-purple">{!! $iconUser !!}</div>
                        <div class="fs-4 fw-bold text-purple">Thành viên đăng ký</div>
                        <div class="display-6 fw-bold">{{ $usersToday }}</div>
                        <span class="badge bg-light-purple text-purple mt-2">Hôm nay</span>
                    </div>
                </div>
            </div>

            {{-- Đơn hàng đã bán --}}
            @php $iconOrder = '<i class="fas fa-shopping-cart"></i>'; @endphp
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-primary">{!! $iconOrder !!}</div>
                        <div class="fs-4 fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersAll }}</div>
                        <span class="badge bg-light-primary text-primary mt-2">Toàn thời gian</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-primary">{!! $iconOrder !!}</div>
                        <div class="fs-4 fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersMonth }}</div>
                        <span class="badge bg-light-primary text-primary mt-2">Tháng
                            {{ request('filterDatetime', now()->format('m')) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-primary">{!! $iconOrder !!}</div>
                        <div class="fs-4 fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersWeek }}</div>
                        <span class="badge bg-light-primary text-primary mt-2">Tuần này</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-primary">{!! $iconOrder !!}</div>
                        <div class="fs-4 fw-bold text-primary">Đơn hàng đã bán</div>
                        <div class="display-6 fw-bold">{{ $ordersToday }}</div>
                        <span class="badge bg-light-primary text-primary mt-2">Hôm nay</span>
                    </div>
                </div>
            </div>

            {{-- Doanh thu --}}
            @php $iconMoney = '<i class="fas fa-dollar-sign"></i>'; @endphp
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-warning">{!! $iconMoney !!}</div>
                        <div class="fs-4 fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueAll) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-2">Toàn thời gian</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-warning">{!! $iconMoney !!}</div>
                        <div class="fs-4 fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueMonth) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-2">Tháng
                            {{ request('filterDatetime', now()->format('m')) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-warning">{!! $iconMoney !!}</div>
                        <div class="fs-4 fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueWeek) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-2">Tuần này</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-warning">{!! $iconMoney !!}</div>
                        <div class="fs-4 fw-bold text-warning">Doanh thu đơn hàng</div>
                        <div class="display-6 fw-bold">{{ number_format($revenueToday) }}đ</div>
                        <span class="badge bg-light-warning text-warning mt-2">Hôm nay</span>
                    </div>
                </div>
            </div>

            {{-- Đơn đã thanh toán (thay cho lợi nhuận) --}}
            @php $iconPaid = '<i class="fas fa-receipt"></i>'; @endphp
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-danger">{!! $iconPaid !!}</div>
                        <div class="fs-4 fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidAll }}</div>
                        <span class="badge bg-light-danger text-danger mt-2">Toàn thời gian</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-danger">{!! $iconPaid !!}</div>
                        <div class="fs-4 fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidMonth }}</div>
                        <span class="badge bg-light-danger text-danger mt-2">Tháng
                            {{ request('filterDatetime', now()->format('m')) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-danger">{!! $iconPaid !!}</div>
                        <div class="fs-4 fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidWeek }}</div>
                        <span class="badge bg-light-danger text-danger mt-2">Tuần này</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="fs-1 mb-2 text-danger">{!! $iconPaid !!}</div>
                        <div class="fs-4 fw-bold text-danger">Đơn đã thanh toán</div>
                        <div class="display-6 fw-bold">{{ $paidToday }}</div>
                        <span class="badge bg-light-danger text-danger mt-2">Hôm nay</span>
                    </div>
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
                                <h5 class="mb-0">THỐNG KÊ ĐƠN HÀNG THEO THÁNG</h5>
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
            $filterDatetime = request('filterDatetime', now()->format('Y-m'));
            $parts = explode('-', $filterDatetime);  // ['08', '2025']
            $jsDateString = $parts[1] . '-' . $parts[0] . '-01'; // "2025-08-01"
        @endphp
        <script>
            flatpickr("#filterDatetime", {
                dateFormat: "m/Y",
                defaultDate:  new Date("{{ $jsDateString }}"),
                plugins: [new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m/Y",
                    altFormat: "F Y"
                })],
                onChange: function (selectedDates, dateStr) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('filterDatetime', dateStr.replaceAll('/', '-'));
                    window.location.href = url.toString();
                }
            });
        </script>
    @endsection