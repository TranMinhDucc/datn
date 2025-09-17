@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')

@section('content')
    @php
        function vnd($n)
        {
            return number_format((float) $n, 0) . 'đ';
        } // nếu muốn dấu chấm: number_format((float)$n, 0, ',', '.')
    @endphp

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- Banner link tới các đơn đổi đã tạo --}}
            @if (!empty($exchangesByRR) && $exchangesByRR->count())
                @foreach ($exchangesByRR as $rrx)
                    <div class="alert alert-info d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fa-solid fa-rotate me-2"></i>
                            Đã tạo <strong>đơn đổi #{{ $rrx->exchange_order_id }}</strong>
                            từ yêu cầu #RR{{ $rrx->id }}.
                            <a class="fw-semibold text-primary"
                                href="{{ route('admin.orders.show', $rrx->exchange_order_id) }}">Xem đơn đổi</a>
                        </div>
                        <span class="badge badge-light-primary">Exchange</span>
                    </div>
                @endforeach
            @endif

            <div class="d-flex flex-column gap-7 gap-lg-10">

                <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                    {{-- Tabs --}}
                    <ul
                        class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                href="#kt_ecommerce_sales_order_summary">Thông tin đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                href="#kt_ecommerce_sales_order_history">Lịch sử đơn hàng</a>
                        </li>
                    </ul>

                    {{-- Nút trạng thái đơn --}}
                    <div class="d-flex gap-2">
                        @php
                            $statusLabels = $statusLabels ?? [
                                'pending' => '🕐 Chờ xác nhận',
                                'confirmed' => '✅ Đã xác nhận',
                                'processing' => '📦 Đang chuẩn bị hàng',
                                'ready_for_dispatch' => '📮 Chờ bàn giao VC',
                                'shipping' => '🚚 Đang giao',
                                'delivery_failed' => '⚠️ Giao thất bại',
                                'delivered' => '📬 Đã giao',
                                'completed' => '🎉 Hoàn tất',
                                'cancelled' => '❌ Đã hủy',
                                'return_requested' => '↩️ Yêu cầu trả hàng',
                                'returning' => '📦 Đang trả hàng về',
                                'returned' => '✅ Đã nhận hàng trả',
                                'exchange_requested' => '🔁 Yêu cầu đổi hàng',
                                'refund_processing' => '💳 Đang hoàn tiền',
                                'refunded' => '✅ Đã hoàn tiền',
                            ];
                            $availableStatuses =
                                $availableStatuses ?? array_diff(array_keys($statusLabels), [$order->status]);
                            $currentLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                        @endphp

                        @if ($order->status !== 'cancelled')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light-primary fw-bold dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    {{ $currentLabel }}
                                </button>
                                <ul class="dropdown-menu">
                                    @forelse ($availableStatuses as $next)
                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                class="d-inline js-status-form">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="{{ $next }}">
                                                <input type="hidden" name="reason" value="">
                                                @php
                                                    $needReason = in_array(
                                                        $next,
                                                        [
                                                            'cancelled',
                                                            'delivery_failed',
                                                            'refund_processing',
                                                            'refunded',
                                                        ],
                                                        true,
                                                    );
                                                @endphp
                                                <button type="submit" class="dropdown-item"
                                                    data-need-reason="{{ $needReason ? '1' : '0' }}">
                                                    {{ $statusLabels[$next] ?? ucfirst($next) }}
                                                </button>
                                            </form>
                                        </li>
                                    @empty
                                        <li><span class="dropdown-item text-muted">Không có trạng thái tiếp theo</span></li>
                                    @endforelse
                                </ul>
                            </div>
                        @else
                            <span class="badge bg-danger fw-bold">{{ $currentLabel }} – Đơn hàng đã bị huỷ</span>
                        @endif

                        @push('scripts')
                            <script>
                                document.querySelectorAll('.js-status-form button[type="submit"]').forEach(btn => {
                                    btn.addEventListener('click', function(e) {
                                        if (this.dataset.needReason === '1') {
                                            e.preventDefault();
                                            const form = this.closest('form');
                                            const label = this.textContent.trim();
                                            const reason = prompt(`Nhập lý do cho trạng thái: ${label}`);
                                            if (reason === null) return;
                                            form.querySelector('[name="reason"]').value = reason;
                                            form.submit();
                                        }
                                    });
                                });
                            </script>
                        @endpush

                        {{-- GHN actions --}}
                        <form action="{{ route('admin.orders.retryShipping', $order->id) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-rotate"></i> Giao lại đơn
                                hàng</button>
                        </form>

                        <form action="{{ route('admin.orders.ghn.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn hủy đơn GHN này không?')">
                                <i class="fa-solid fa-rotate-left"></i> Hủy đơn GHN
                            </button>
                        </form>

                        <form id="confirm-ghn-form" action="{{ route('admin.orders.confirm-ghn', $order->id) }}"
                            method="POST" style="display:none;">
                            @csrf
                        </form>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('confirm-ghn-form').submit();"
                            class="btn btn-success btn-sm">
                            <i class="fa-solid fa-paper-plane"></i> Xác nhận & Gửi đơn Shipping
                        </a>
                        <a href="{{ route('admin.orders.print-label', $order->id) }}" class="btn btn-info btn-sm"
                            target="_blank">
                            <i class="fa-solid fa-print"></i>
                        </a>
                    </div>
                </div>

                {{-- Thông báo cron --}}
                <div
                    class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row align-items-center justify-content-center p-5">
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <span>
                            <i class="fa-solid fa-bell"></i> Vui lòng chạy CRON:
                            <a class="text-primary" href="/cron/sync-ghn-orders" target="_blank">/cron/sync-ghn-orders</a>
                            mỗi 1 phút để tự động gửi đơn và cập nhật trạng thái đơn hàng !!
                        </span>
                    </div>
                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="fa-solid fa-xmark fs-1 text-info"></i>
                    </button>
                </div>

                {{-- Khiếu nại đơn hàng (nếu có) --}}
                @if ($order->return_reason)
                    <div class="alert alert-warning d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-rotate-left fs-2x text-warning"></i>
                            <h4 class="mb-0">Yêu cầu khiếu nại của đơn hàng</h4>
                        </div>
                        <div><strong>Lý do:</strong> {{ $order->return_reason }}</div>
                        @if ($order->return_image)
                            <div>
                                <strong>Ảnh đính kèm:</strong><br>
                                <img src="{{ asset('storage/' . $order->return_image) }}" alt="Ảnh khiếu nại"
                                    style="max-width:300px;" class="img-thumbnail">
                            </div>
                        @endif
                        @if (!is_null($order->refunded_at))
                            <div class="badge bg-success fs-6">Đã xử lý hoàn hàng vào
                                {{ $order->refunded_at->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                @endif

                {{-- ======= Order summary cards ======= --}}
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                    {{-- Order details --}}
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Chi tiết đơn hàng (#{{ $order->order_code }})</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-calendar-days fs-65 me-2 text-gray-400"></i> Ngày tạo
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-money-check-dollar fs-6 me-2 text-gray-400"></i>
                                                Phương thức thanh toán</td>
                                            <td class="fw-bold text-end">Online <img
                                                    src="../../../assets/media/svg/card-logos/visa.svg"
                                                    class="w-50px ms-2" /></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-truck-moving fs-6 me-2 text-gray-400"></i> Phương
                                                thức vận chuyển</td>
                                            <td class="fw-bold text-end">Flat Shipping Rate</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Customer details --}}
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Chi tiết khách hàng</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-circle-user fs-6 me-2 text-gray-400"></i> Khách hàng
                                            </td>
                                            <td class="fw-bold text-end">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                        <div class="symbol-label">
                                                            <img src="https://img.lovepik.com/png/20231019/customer-login-avatar-client-gray-head-portrait_269373_wh860.png"
                                                                class="w-100" />
                                                        </div>
                                                    </div>
                                                    <a href="{{ $order->user ? route('admin.users.edit', $order->user) : '#' }}"
                                                        class="text-gray-600 text-hover-primary">
                                                        {{ $order->user->fullname }}
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-envelope fs-6 me-2 text-gray-400"></i> Email</td>
                                            <td class="fw-bold text-end">
                                                <a
                                                    class="text-gray-600 text-hover-primary">{{ $order->user->email ?? 'Chưa cập nhật' }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-phone-volume fs-6 me-2 text-gray-400"></i> Phone
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->user->phone ?? 'Chưa cập nhật' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="card card-flush py-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Documents</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-mobile fs-6 me-2 text-gray-400"></i> Invoice</td>
                                            <td class="fw-bold text-end"><a href="#"
                                                    class="text-gray-600 text-hover-primary">#INV-000414</a></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-truck-moving fs-6 text-gray-400 me-2"></i> Shipping
                                            </td>
                                            <td class="fw-bold text-end"><a href="#"
                                                    class="text-gray-600 text-hover-primary">{{ $order->shippingOrder->shipping_code ?? 'Chưa tạo vận đơn' }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i
                                                    class="fa-solid fa-file-invoice text-gray-500 fs-6 me-2"></i> Thanh
                                                toán</td>
                                            <td class="fw-bold text-end">
                                                @switch($order->payment_status)
                                                    @case('unpaid')
                                                        <span class="badge badge-light-warning">Chưa thanh toán</span>
                                                    @break

                                                    @case('paid')
                                                        <span class="badge badge-light-success">Đã thanh toán</span>
                                                    @break

                                                    @case('refunded')
                                                        <span class="badge badge-light-danger">Đã hoàn tiền</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-light">Không xác định</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Lưu ý – Ghi chú cho GHN --}}
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <h3 class="card-title">Lưu ý – Ghi chú giao hàng (GHN)</h3>
                    </div>
                    <div class="card-body pt-0">
                        <form method="POST" action="{{ route('admin.orders.updateGhnNote', $order->id) }}"
                            class="js-ghn-note-form">
                            @csrf

                            <div class="row g-5">
                                <div class="col-md-6">
                                    <label class="form-label required">Lưu ý giao hàng</label>
                                    @php
                                        $required = $order->required_note_shipper ?? 'KHONGCHOXEMHANG';
                                    @endphp
                                    <div class="d-flex flex-column gap-3">
                                        <label class="d-flex align-items-center gap-3">
                                            <input type="radio" name="required_note_shipper" class="form-check-input"
                                                value="KHONGCHOXEMHANG"
                                                {{ $required === 'KHONGCHOXEMHANG' ? 'checked' : '' }}>
                                            <span>Không cho xem hàng</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-3">
                                            <input type="radio" name="required_note_shipper" class="form-check-input"
                                                value="CHOXEMHANGKHONGTHU"
                                                {{ $required === 'CHOXEMHANGKHONGTHU' ? 'checked' : '' }}>
                                            <span>Cho xem hàng không cho thử</span>
                                        </label>
                                        <label class="d-flex align-items-center gap-3">
                                            <input type="radio" name="required_note_shipper" class="form-check-input"
                                                value="CHOTHUHANG" {{ $required === 'CHOTHUHANG' ? 'checked' : '' }}>
                                            <span>Cho thử hàng</span>
                                        </label>
                                        <div class="form-text">
                                            * Cập nhật này yêu cầu đơn đã có mã GHN. Nếu chưa tạo vận đơn, hãy bấm “Xác nhận
                                            & Gửi đơn Shipping”.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea name="note_shipper" class="form-control" rows="5"
                                        placeholder="Ví dụ: Giao giờ hành chính, gọi trước 15 phút...">{{ old('note_shipper', $order->note_shipper) }}</textarea>
                                </div>
                            </div>

                            <div class="mt-5 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <span class="js-saving d-none spinner-border spinner-border-sm me-2"></span>
                                    Cập nhật ghi chú GHN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-flush py-4 mt-6">
                    <div class="card-header">
                        <h3 class="card-title">Điều chỉnh</h3>
                    </div>
                    <div class="card-body pt-0">
                        <form class="row row-cols-lg-auto g-3 align-items-end" method="POST"
                            action="{{ route('admin.orders.adjustments.store', $order) }}">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Nhãn</label>
                                <input name="label" class="form-control" placeholder="Phí vệ sinh / Chiết khấu..."
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mã</label>
                                <input name="code" class="form-control" placeholder="CLEANING / RETURN_SHIP">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Loại</label>
                                <select name="type" class="form-select">
                                    <option value="charge">Cộng</option>
                                    <option value="discount">Trừ</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Số tiền</label>
                                <input name="amount" type="number" step="0.01" min="0.01" class="form-control"
                                    required>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Thêm</button>
                            </div>
                        </form>

                        <div class="table-responsive mt-5">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nhãn</th>
                                        <th>Mã</th>
                                        <th>Loại</th>
                                        <th class="text-end">Số tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->adjustments as $adj)
                                        <tr>
                                            <td>{{ $adj->label }}</td>
                                            <td><code>{{ $adj->code }}</code></td>
                                            <td>{{ $adj->type === 'charge' ? 'Cộng' : 'Trừ' }}</td>
                                            <td class="text-end">{{ number_format($adj->amount, 2) }}</td>
                                            <td class="text-end">
                                                <form method="POST"
                                                    action="{{ route('admin.orders.adjustments.destroy', $adj) }}">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-light-danger">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-muted text-center">Chưa có điều chỉnh</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Tổng điều chỉnh</th>
                                        <th class="text-end">{{ number_format($order->adjustments_total, 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card card-flush py-4 mt-6">
                    <div class="card-header">
                        <h3 class="card-title">Thanh toán / Hoàn tiền</h3>
                    </div>
                    <div class="card-body pt-0">
                        <form class="row row-cols-lg-auto g-3 align-items-end" method="POST"
                            action="{{ route('admin.orders.payments.store', $order) }}">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Loại</label>
                                <select name="kind" class="form-select">
                                    <option value="payment">Thu thêm</option>
                                    <option value="refund">Hoàn lại</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phương thức</label>
                                <input name="method" class="form-control" placeholder="bank/cod/momo...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Số tiền</label>
                                <input name="amount" type="number" step="0.01" min="0.01" class="form-control"
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ghi chú</label>
                                <input name="note" class="form-control">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Ghi nhận</button>
                            </div>
                        </form>

                        <div class="table-responsive mt-5">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Loại</th>
                                        <th>PT</th>
                                        <th class="text-end">Số tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->payments as $p)
                                        <tr>
                                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $p->kind === 'payment' ? 'Thu thêm' : 'Hoàn lại' }}</td>
                                            <td>{{ $p->method }}</td>
                                            <td class="text-end">{{ number_format($p->amount, 2) }}</td>
                                            <td class="text-end">
                                                <form method="POST"
                                                    action="{{ route('admin.orders.payments.destroy', $p) }}">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-light-danger">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-muted text-center">Chưa có giao dịch</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex flex-column gap-1">
                            <div class="d-flex justify-content-between">
                                <span>Tổng hàng + VAT + ship</span>
                                <strong>{{ number_format($order->subtotal + $order->tax_amount + $order->shipping_fee, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tổng điều chỉnh</span>
                                <strong>{{ number_format($order->adjustments_total, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><u>Phải thu sau cùng</u></span>
                                <strong>{{ number_format($order->net_total, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Đã thu (payment)</span>
                                <strong>{{ number_format($order->paid_in, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Đã hoàn (refund)</span>
                                <strong>{{ number_format($order->refunded_out, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between fs-5">
                                <span><b>Số dư</b> (dương = KH còn thiếu, âm = cần hoàn)</span>
                                <strong>{{ number_format($order->balance, 0, ',', '.') }}đ</strong>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- @php
                    $refundLocked = \App\Models\Refund::where('return_request_id', $rr->id)
                        ->whereIn('status', ['pending', 'done'])
                        ->exists();
                @endphp --}}

                {{-- ======= Return/Exchange requests ======= --}}
                @if ($returnRequests->count())
                    @foreach ($returnRequests as $rr)
                        @php
                            $refundPending = \App\Models\Refund::where('return_request_id', $rr->id)
                                ->where('status', 'pending')
                                ->first();

                            $refundLocked = \App\Models\Refund::where('return_request_id', $rr->id)
                                ->whereIn('status', ['pending', 'done'])
                                ->exists();

                            $sumItemRefund = (float) $rr->items->sum(
                                fn($it) => (float) ($it->actions?->where('action', 'refund')->sum('refund_amount') ??
                                    0),
                            );
                        @endphp


                        {{-- Card chính cho Return Request --}}
                        <div class="card shadow-sm mb-6">
                            {{-- Header của card --}}
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    {{-- Thông tin request --}}
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-primary">
                                                @if ($rr->type === 'exchange')
                                                    <i class="fas fa-exchange-alt fs-2 text-white"></i>
                                                @else
                                                    <i class="fas fa-undo fs-2 text-white"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0">
                                                @if ($rr->type === 'exchange')
                                                    Yêu cầu đổi hàng
                                                @else
                                                    Yêu cầu hoàn hàng
                                                @endif
                                                <span class="text-muted">#{{ $rr->id }}</span>
                                            </h5>
                                            <div class="text-muted fs-7">{{ $rr->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>

                                    {{-- Status và actions --}}
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $requestBadgeClass = match ($rr->status) {
                                                'pending' => 'badge-warning',
                                                'approved' => 'badge-primary',
                                                'refunded' => 'badge-success',
                                                'rejected' => 'badge-danger',
                                                default => 'badge-secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge {{ $requestBadgeClass }} fs-7">{{ ucfirst($rr->status) }}</span>

                                        {{-- Action buttons --}}
                                        @php
                                            $canCreateExchange =
                                                in_array($rr->status, ['pending', 'approved']) &&
                                                empty($rr->exchange_order_id) &&
                                                $rr->items->sum(
                                                    fn($i) => $i->actions
                                                        ?->where('action', 'exchange')
                                                        ->sum('quantity') ?? 0,
                                                ) > 0;
                                        @endphp

                                        @if ($canCreateExchange)
                                            <form action="{{ route('admin.return-requests.exchange', $rr->id) }}"
                                                method="POST" class="js-exchange-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i>
                                                    Tạo đơn đổi
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- Refund section --}}
                                @if ($refundPending)
                                    {{-- ĐANG CHỜ CHUYỂN --}}
                                    <div class="mt-4 p-4 bg-light-primary rounded border-primary border border-dashed">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-warning fs-2 me-3"></i>
                                                <div>
                                                    <div class="fw-bold text-gray-800">Phiếu hoàn đang chờ xử lý</div>
                                                    <div class="text-muted fs-7">Phiếu #{{ $refundPending->id }} -
                                                        {{ vnd($refundPending->amount) }}</div>
                                                </div>
                                            </div>
                                            <form action="{{ route('admin.refunds.markDone', $refundPending) }}"
                                                method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <input name="bank_ref" class="form-control form-control-sm"
                                                    placeholder="Mã giao dịch" style="width: 150px;" required>
                                                <input type="datetime-local" name="transferred_at"
                                                    class="form-control form-control-sm" style="width: 180px;">
                                                <button class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i>Đã
                                                    chuyển</button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif (!$refundLocked)
                                    {{-- CHƯA CÓ PHIẾU HOÀN (pending/done) -> cho tạo --}}
                                    <div class="mt-4 p-4 bg-light-warning rounded border-warning border border-dashed">
                                        <form action="{{ route('admin.refunds.createFromRR', $rr) }}" method="POST"
                                            class="d-flex align-items-center gap-3">
                                            @csrf
                                            <label class="form-label mb-0 fw-semibold text-gray-800">Tạo phiếu
                                                hoàn:</label>
                                            <input type="number" step="0.01" name="amount"
                                                class="form-control form-control-sm w-200px"
                                                placeholder="Số tiền (mặc định: {{ vnd($sumItemRefund) }})">
                                            <button class="btn btn-warning btn-sm"><i class="fas fa-wallet me-1"></i>Tạo
                                                phiếu</button>
                                        </form>
                                    </div>
                                @else
                                    {{-- ĐÃ CÓ PHIẾU DONE -> chỉ hiển thị thông tin, KHÔNG cho tạo lại --}}
                                    <div class="mt-4 p-3 bg-light-success rounded border-success border border-dashed">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Đã có phiếu hoàn – đã khóa.
                                    </div>
                                @endif

                                @if ($refundPending)
                                    <div class="mt-4 p-4 bg-light-primary rounded border-primary border border-dashed">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-warning fs-2 me-3"></i>
                                                <div>
                                                    <div class="fw-bold text-gray-800">Phiếu hoàn đang chờ xử lý</div>
                                                    <div class="text-muted fs-7">Phiếu #{{ $refundPending->id }} -
                                                        {{ vnd($refundPending->amount) }}</div>
                                                </div>
                                            </div>
                                            <form action="{{ route('admin.refunds.markDone', $refundPending) }}"
                                                method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <input name="bank_ref" class="form-control form-control-sm"
                                                    placeholder="Mã giao dịch" style="width: 150px;" required>
                                                <input type="datetime-local" name="transferred_at"
                                                    class="form-control form-control-sm" style="width: 180px;">
                                                <button class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>
                                                    Đã chuyển
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Body - Danh sách items --}}
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 gy-5 gs-7">
                                        <thead>
                                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="min-w-300px">Sản phẩm</th>
                                                <th class="text-center min-w-80px">SL yêu cầu</th>
                                                <th class="text-center min-w-120px">Đã xử lý</th>
                                                <th class="text-center min-w-100px">Trạng thái</th>
                                                <th class="text-end min-w-200px">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rr->items as $it)
                                                @php
                                                    // Tính toán số lượng
                                                    $exQty =
                                                        (int) ($it->actions
                                                            ?->where('action', 'exchange')
                                                            ->sum('quantity') ?? 0);
                                                    $rfQty =
                                                        (int) ($it->actions
                                                            ?->where('action', 'refund')
                                                            ->sum('quantity') ?? 0);
                                                    $rjQty =
                                                        (int) ($it->actions
                                                            ?->where('action', 'reject')
                                                            ->sum('quantity') ?? 0);
                                                    $rfAmt =
                                                        (float) ($it->actions
                                                            ?->where('action', 'refund')
                                                            ->sum('refund_amount') ?? 0);

                                                    // Trạng thái item
                                                    $itemStatus = 'pending';
                                                    if ($exQty || $rfQty || $rjQty) {
                                                        if ($exQty && !$rfQty && !$rjQty) {
                                                            $itemStatus = 'approved_exchange';
                                                        } elseif ($rfQty && !$exQty && !$rjQty) {
                                                            $itemStatus = 'approved_refund';
                                                        } elseif ($rjQty && !$exQty && !$rfQty) {
                                                            $itemStatus = 'rejected';
                                                        } else {
                                                            $itemStatus = 'approved_mixed';
                                                        }
                                                    }

                                                    $statusBadge = match ($itemStatus) {
                                                        'approved_exchange' => [
                                                            'badge' => 'badge-success',
                                                            'text' => 'Đồng ý đổi',
                                                        ],
                                                        'approved_refund' => [
                                                            'badge' => 'badge-info',
                                                            'text' => 'Hoàn tiền',
                                                        ],
                                                        'approved_mixed' => [
                                                            'badge' => 'badge-primary',
                                                            'text' => 'Chia xử lý',
                                                        ],
                                                        'rejected' => ['badge' => 'badge-danger', 'text' => 'Từ chối'],
                                                        default => ['badge' => 'badge-warning', 'text' => 'Chờ xử lý'],
                                                    };

                                                    // Thuộc tính sản phẩm
                                                    $attrs = '';
                                                    $raw = $it->orderItem->variant_values ?? null;
                                                    $vals = is_string($raw)
                                                        ? json_decode($raw, true)
                                                        : (is_array($raw)
                                                            ? $raw
                                                            : []);
                                                    if (json_last_error() === JSON_ERROR_NONE && !empty($vals)) {
                                                        $pairs = [];
                                                        foreach ($vals as $k => $v) {
                                                            $label = is_string($k)
                                                                ? mb_convert_case(trim($k), MB_CASE_TITLE, 'UTF-8')
                                                                : $k;
                                                            $pairs[] = $label . ': ' . $v;
                                                        }
                                                        $attrs = ' • ' . implode(', ', $pairs);
                                                    }

                                                    // Lock logic
                                                    $locked =
                                                        $refundLocked ||
                                                        !empty($rr->exchange_order_id) ||
                                                        in_array($rr->status, ['refunded', 'rejected'], true);
                                                    $usedQty = (int) ($exQty + $rfQty + $rjQty);
                                                    $leftQty = max(0, (int) $it->quantity - $usedQty);
                                                @endphp

                                                <tr>
                                                    {{-- Tên sản phẩm --}}
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-50px me-3">
                                                                <div class="symbol-label bg-light-primary">
                                                                    <i class="fas fa-box text-primary fs-2"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ms-3">
                                                                <div class="fw-bold text-gray-800 fs-6">
                                                                    {{ $it->orderItem->product_name }}</div>
                                                                @if ($attrs)
                                                                    <div class="text-muted fs-7">{{ $attrs }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{-- Số lượng yêu cầu --}}
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-light-primary fs-6">{{ $it->quantity }}</span>
                                                    </td>

                                                    {{-- Đã xử lý --}}
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column gap-1">
                                                            @if ($exQty > 0)
                                                                <span class="badge badge-light-success fs-8">Đổi:
                                                                    {{ $exQty }}</span>
                                                            @endif
                                                            @if ($rfQty > 0)
                                                                <span class="badge badge-light-info fs-8">Hoàn:
                                                                    {{ $rfQty }}</span>
                                                            @endif
                                                            @if ($rjQty > 0)
                                                                <span class="badge badge-light-danger fs-8">Từ chối:
                                                                    {{ $rjQty }}</span>
                                                            @endif
                                                            @if ($rfAmt > 0)
                                                                <div class="text-muted fs-8 mt-1">{{ vnd($rfAmt) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Trạng thái --}}
                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $statusBadge['badge'] }} fs-7">{{ $statusBadge['text'] }}</span>
                                                    </td>

                                                    {{-- Thao tác --}}
                                                    <td class="text-end">
                                                        {{-- Danh sách actions đã thêm --}}
                                                        @if ($it->actions?->count())
                                                            <div class="mb-3">
                                                                @foreach ($it->actions as $act)
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between bg-light-gray-200 rounded p-2 mb-2">
                                                                        <div class="d-flex align-items-center">
                                                                            @if ($act->action === 'exchange')
                                                                                <span
                                                                                    class="badge badge-success me-2">Đổi</span>
                                                                                <span
                                                                                    class="fs-7">{{ optional($act->variant)->variant_name ?? (optional($act->variant)->sku ?? 'SKU hiện tại') }}
                                                                                    × {{ $act->quantity }}</span>
                                                                            @elseif ($act->action === 'refund')
                                                                                <span
                                                                                    class="badge badge-info me-2">Hoàn</span>
                                                                                <span class="fs-7">SL:
                                                                                    {{ $act->quantity }} @if ($act->refund_amount)
                                                                                        • {{ vnd($act->refund_amount) }}
                                                                                    @endif
                                                                                </span>
                                                                            @else
                                                                                <span class="badge badge-danger me-2">Từ
                                                                                    chối</span>
                                                                                <span class="fs-7">SL:
                                                                                    {{ $act->quantity }}</span>
                                                                            @endif
                                                                            @if ($act->note)
                                                                                <div class="text-muted fs-8 mt-1">
                                                                                    {{ $act->note }}</div>
                                                                            @endif
                                                                        </div>
                                                                        @if (!$locked)
                                                                            <form method="POST"
                                                                                action="{{ route('admin.return-requests.items.actions.destroy', $act->id) }}"
                                                                                onsubmit="return confirm('Xoá dòng xử lý này?');">
                                                                                @csrf @method('DELETE')
                                                                                <button
                                                                                    class="btn btn-icon btn-sm btn-light-danger">
                                                                                    <i
                                                                                        class="fas fa-trash text-danger"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        {{-- Nút thêm action --}}
                                                        @if (!$locked && $leftQty > 0)
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addExchangeActionModal"
                                                                    data-item-id="{{ $it->id }}"
                                                                    data-qty="{{ $it->quantity }}"
                                                                    data-used="{{ $usedQty }}"
                                                                    data-variants='@json(optional($it->orderItem->product)->variants?->map(fn($v) => [
                                                                                'id' => $v->id,
                                                                                'label' =>
                                                                                    ($v->variant_name ?? trim(($v->color ?? '') . ' ' . ($v->size ?? ''))) .
                                                                                    " — SKU: {$v->sku} — " .
                                                                                    number_format($v->price) .
                                                                                    'đ',
                                                                            ]) ?? []
                                                                    )'>
                                                                    <i class="fas fa-exchange-alt me-1"></i>
                                                                    Đổi
                                                                </button>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-light-warning"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addRefundActionModal"
                                                                    data-item-id="{{ $it->id }}"
                                                                    data-qty="{{ $it->quantity }}"
                                                                    data-used="{{ $usedQty }}">
                                                                    <i class="fas fa-wallet me-1"></i>
                                                                    Hoàn
                                                                </button>

                                                                <button type="button" class="btn btn-sm btn-light-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addRejectActionModal"
                                                                    data-item-id="{{ $it->id }}"
                                                                    data-qty="{{ $it->quantity }}"
                                                                    data-used="{{ $usedQty }}">
                                                                    <i class="fas fa-times me-1"></i>
                                                                    Từ chối
                                                                </button>
                                                            </div>
                                                        @else
                                                            <div class="text-muted fs-7 fst-italic">
                                                                @if ($refundLocked)
                                                                    Đã có phiếu hoàn - đã khóa
                                                                @elseif (!empty($rr->exchange_order_id))
                                                                    Đã có đơn đổi - đã khóa
                                                                @elseif (in_array($rr->status, ['refunded', 'rejected'], true))
                                                                    Yêu cầu đã kết thúc
                                                                @else
                                                                    Đã xử lý đủ số lượng
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- ======= Tab content ======= --}}
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                        {{-- Product list --}}
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Đơn hàng {{ $order->order_code }}</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px">Sản phẩm</th>
                                                    <th class="min-w-100px text-end">SKU</th>
                                                    <th class="min-w-70px text-end">Số lượng</th>
                                                    <th class="min-w-100px text-end">Đơn giá</th>
                                                    <th class="min-w-100px text-end">Tổng cộng</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                @foreach ($order->orderItems as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <a href="#" class="symbol symbol-50px">
                                                                    <span class="symbol-label"
                                                                        style="background-image:url('{{ $item->image_url ?? asset('default-image.png') }}');"></span>
                                                                </a>
                                                                <div class="ms-5">
                                                                    <a href="#"
                                                                        class="fw-bold text-gray-600 text-hover-primary">{{ $item->product->name }}</a>
                                                                    <div class="fs-7 text-muted">Ngày giao hàng:
                                                                        {{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d/m/Y') : '—' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">{{ $item->productVariant->sku ?? 'N/A' }}
                                                        </td>
                                                        <td class="text-end">{{ $item->quantity }}</td>
                                                        <td class="text-end">{{ number_format($item->price) }}đ</td>
                                                        <td class="text-end">{{ number_format($item->total_price) }}đ
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="4" class="text-end">Tổng cộng</td>
                                                    <td class="text-end">{{ number_format($order->subtotal) }}đ</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end">Thuế VAT</td>
                                                    <td class="text-end">{{ number_format($order->tax_amount) }}đ</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end">Phí vận chuyển</td>
                                                    <td class="text-end">{{ number_format($order->shipping_fee) }}đ
                                                    </td>
                                                </tr>

                                                @if ($order->coupon)
                                                    <tr>
                                                        <td colspan="4" class="text-end text-danger">Mã giảm giá
                                                            sản
                                                            phẩm ({{ $order->coupon->code }})</td>
                                                        <td class="text-end text-danger">
                                                            @if ($order->coupon->value_type === 'fixed')
                                                                -{{ number_format($order->coupon->discount_value) }}đ
                                                            @else
                                                                -{{ $order->coupon->discount_value }}%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif

                                                @if ($order->shippingCoupon)
                                                    <tr>
                                                        <td colspan="4" class="text-end text-danger">Mã giảm giá
                                                            vận
                                                            chuyển ({{ $order->shippingCoupon->code }})</td>
                                                        <td class="text-end text-danger">
                                                            @if ($order->shippingCoupon->value_type === 'fixed')
                                                                -{{ number_format($order->shippingCoupon->discount_value) }}đ
                                                            @else
                                                                -{{ $order->shippingCoupon->discount_value }}%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td colspan="4" class="fs-3 text-gray-900 text-end">Tổng cộng
                                                    </td>
                                                    <td class="text-gray-900 fs-3 fw-bolder text-end">
                                                        {{ number_format($order->total_amount) }}đ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- History tab --}}
                    <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <div class="card card-flush py-4 flex-row-fluid">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Order History</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px">Date Added</th>
                                                    <th class="min-w-175px">Comment</th>
                                                    <th class="min-w-70px">Order Status</th>
                                                    <th class="min-w-100px">Customer Notified</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                @forelse ($order->shippingLogs->sortByDesc('received_at') as $log)
                                                    <tr>
                                                        <td>{{ $log->received_at->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $log->description }}</td>
                                                        <td>
                                                            <div class="badge badge-light-success">
                                                                {{ ucfirst($log->status) }}</div>
                                                        </td>
                                                        <td>No</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Chưa có log
                                                            vận
                                                            chuyển</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 flex-row-fluid">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Order Data</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5">
                                            <tbody class="fw-semibold text-gray-600">
                                                <tr>
                                                    <td class="text-muted">IP Address</td>
                                                    <td class="fw-bold text-end">172.68.221.26</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Forwarded IP</td>
                                                    <td class="fw-bold text-end">89.201.163.49</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">User Agent</td>
                                                    <td class="fw-bold text-end">Mozilla/5.0 ...</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Accept Language</td>
                                                    <td class="fw-bold text-end">en-GB,en-US;q=0.9,en;q=0.8</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- /tab-content -->

            </div><!-- /gap -->
        </div><!-- /container -->
    </div><!-- /content -->

    {{-- Modals --}}
    <div class="modal fade" id="rejectItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="rejectItemForm">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="reject">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nhập lý do từ chối</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối..."></textarea>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Đóng</button><button type="submit" class="btn btn-danger">Xác nhận
                            từ chối</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="refundItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="refundItemForm">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="refund">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hoàn tiền cho sản phẩm</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Số tiền hoàn (để trống sẽ tự prorate)</label>
                        <input type="number" step="0.01" name="refund_amount" class="form-control">
                        <label class="mt-3">Lý do</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do..."></textarea>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Đóng</button><button type="submit" class="btn btn-warning">Xác nhận
                            hoàn tiền</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="splitItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="splitItemForm">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xử lý sản phẩm</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong id="modalProductName"></strong> – SL gốc: <span id="modalProductQty"></span></p>

                        <label>Đổi (exchange)</label>
                        <input type="number" class="form-control mb-2" id="exchange_qty" name="exchange_qty"
                            value="0" min="0">

                        {{-- chọn SKU cho đổi: KHÔNG dùng $item ở đây --}}
                        <label class="mt-2">Đổi sang variant (SKU)</label>
                        <select class="form-select mb-3" id="modalExchangeVariant" name="exchange_variant_id"></select>

                        <label class="mt-2">Hoàn tiền (refund)</label>
                        <div class="d-flex gap-2">
                            <input type="number" class="form-control" id="refund_qty" name="refund_qty" value="0"
                                min="0">
                            <input type="number" class="form-control" id="refund_amount" name="refund_amount"
                                placeholder="Số tiền hoàn">
                        </div>

                        <label class="mt-2">Từ chối (reject)</label>
                        <input type="number" class="form-control mb-2" id="reject_qty" name="reject_qty"
                            value="0" min="0">

                        <label class="mt-2">Lý do từ chối</label>
                        <textarea class="form-control mb-2" id="reject_reason" name="reject_reason" rows="2"
                            placeholder="Nhập lý do từ chối..."></textarea>

                        <input type="hidden" name="action" value="split">
                    </div>

                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Xác nhận</button></div>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal: thêm action ĐỔI --}}
    <div class="modal fade" id="addExchangeActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formAddExchange">
                @csrf
                <input type="hidden" name="action" value="exchange">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm dòng ĐỔI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Variant (SKU) đích</label>
                            <select name="exchange_variant_id" id="exVariant" class="form-select">
                                <option value="">-- Giữ SKU hiện tại --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="quantity" id="exQty" class="form-control" min="1">
                            <div class="form-text" id="exHint"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: thêm action HOÀN --}}
    <div class="modal fade" id="addRefundActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formAddRefund">
                @csrf
                <input type="hidden" name="action" value="refund">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm dòng HOÀN TIỀN</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="quantity" id="rfQty" class="form-control" min="1">
                            <div class="form-text" id="rfHint"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số tiền hoàn (để trống sẽ tự prorate)</label>
                            <input type="number" step="0.01" name="refund_amount" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: thêm action TỪ CHỐI --}}
    <div class="modal fade" id="addRejectActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formAddReject">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm dòng TỪ CHỐI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="quantity" id="rjQty" class="form-control" min="1">
                            <div class="form-text" id="rjHint"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lý do</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Nhập lý do..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // set action cho modal Reject
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("rejectItemModal");
            const form = document.getElementById("rejectItemForm");
            modal.addEventListener("show.bs.modal", function(e) {
                const id = e.relatedTarget.getAttribute("data-id");
                form.action = `/admin/return-requests/items/${id}`;
            });
        });

        // set action cho modal Refund
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("refundItemModal");
            const form = document.getElementById("refundItemForm");
            modal.addEventListener("show.bs.modal", function(e) {
                const id = e.relatedTarget.getAttribute("data-id");
                form.action = `/admin/return-requests/items/${id}`;
            });
        });

        // Split modal: kiểm tra tổng quantity, KHÔNG bắt buộc nhập refund_amount (prorate được)
        // const splitModal = document.getElementById("splitItemModal");
        // splitModal.addEventListener("show.bs.modal", function(event) {
        //     const button = event.relatedTarget;
        //     const id = button.getAttribute("data-id");
        //     const name = button.getAttribute("data-name");
        //     const qty = parseInt(button.getAttribute("data-qty"));

        //     document.getElementById("modalProductName").innerText = name;
        //     document.getElementById("modalProductQty").innerText = qty;

        //     const form = document.getElementById("splitItemForm");
        //     form.action = `/admin/return-requests/items/${id}`;

        //     form.onsubmit = function(e) {
        //         const ex = parseInt(form.querySelector("#exchange_qty").value) || 0;
        //         const rf = parseInt(form.querySelector("#refund_qty").value) || 0;
        //         const rj = parseInt(form.querySelector("#reject_qty").value) || 0;
        //         const total = ex + rf + rj;
        //         if (total > qty) {
        //             e.preventDefault();
        //             alert(`❌ Tổng số lượng (${total}) không được vượt quá số lượng gốc (${qty}).`);
        //             return false;
        //         }
        //     };
        // });
    </script>
    <script>
        const splitModal = document.getElementById("splitItemModal");
        splitModal.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute("data-id");
            const name = button.getAttribute("data-name");
            const qty = parseInt(button.getAttribute("data-qty")) || 0;
            const variants = JSON.parse(button.getAttribute("data-variants") || "[]");

            document.getElementById("modalProductName").innerText = name;
            document.getElementById("modalProductQty").innerText = qty;

            const form = document.getElementById("splitItemForm");
            form.action = `/admin/return-requests/items/${id}`;

            // fill select variant
            const sel = document.getElementById("modalExchangeVariant");
            sel.innerHTML = '<option value="">-- Giữ SKU hiện tại --</option>';
            variants.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.id;
                opt.textContent = v.label;
                sel.appendChild(opt);
            });

            // validate tổng SL
            form.onsubmit = function(e) {
                const ex = parseInt(form.querySelector("#exchange_qty").value) || 0;
                const rf = parseInt(form.querySelector("#refund_qty").value) || 0;
                const rj = parseInt(form.querySelector("#reject_qty").value) || 0;
                if (ex + rf + rj > qty) {
                    e.preventDefault();
                    alert(`❌ Tổng số lượng vượt quá ${qty}.`);
                }
            }
        });
    </script>
    <script>
        (function() {
            // helper: tính max còn lại = qty gốc - tổng qty đã dùng (mọi action)
            function calcMaxLeft(qty, used) {
                qty = parseInt(qty || 0);
                used = parseInt(used || 0);
                const left = Math.max(0, qty - used);
                return left;
            }

            // --- Modal EXCHANGE ---
            const mEx = document.getElementById('addExchangeActionModal');
            mEx.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                const id = btn.getAttribute('data-item-id');
                const qty = parseInt(btn.getAttribute('data-qty')) || 0;
                const used = parseInt(btn.getAttribute('data-used')) || 0;
                const left = calcMaxLeft(qty, used);

                const form = document.getElementById('formAddExchange');
                form.action = `/admin/return-requests/items/${id}/actions`;

                const sel = document.getElementById('exVariant');
                const list = JSON.parse(btn.getAttribute('data-variants') || '[]');
                sel.innerHTML = '<option value="">-- Giữ SKU hiện tại --</option>';
                list.forEach(v => {
                    const o = document.createElement('option');
                    o.value = v.id;
                    o.textContent = v.label;
                    sel.appendChild(o);
                });

                const qtyInput = document.getElementById('exQty');
                qtyInput.value = left > 0 ? 1 : 0;
                qtyInput.min = 1;
                qtyInput.max = left;
                document.getElementById('exHint').textContent = `Có thể thêm tối đa ${left} sản phẩm.`;

                form.onsubmit = function(ev) {
                    if (parseInt(qtyInput.value || 0) > left) {
                        ev.preventDefault();
                        alert(`❌ Vượt quá số lượng còn lại (${left}).`);
                    }
                };
            });

            // --- Modal REFUND ---
            const mRf = document.getElementById('addRefundActionModal');
            mRf.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                const id = btn.getAttribute('data-item-id');
                const qty = parseInt(btn.getAttribute('data-qty')) || 0;
                const used = parseInt(btn.getAttribute('data-used')) || 0;
                const left = calcMaxLeft(qty, used);

                const form = document.getElementById('formAddRefund');
                form.action = `/admin/return-requests/items/${id}/actions`;

                const qtyInput = document.getElementById('rfQty');
                qtyInput.value = left > 0 ? 1 : 0;
                qtyInput.min = 1;
                qtyInput.max = left;
                document.getElementById('rfHint').textContent = `Có thể hoàn tối đa ${left} sản phẩm.`;

                form.onsubmit = function(ev) {
                    if (parseInt(qtyInput.value || 0) > left) {
                        ev.preventDefault();
                        alert(`❌ Vượt quá số lượng còn lại (${left}).`);
                    }
                };
            });

            // --- Modal REJECT ---
            const mRj = document.getElementById('addRejectActionModal');
            mRj.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                const id = btn.getAttribute('data-item-id');
                const qty = parseInt(btn.getAttribute('data-qty')) || 0;
                const used = parseInt(btn.getAttribute('data-used')) || 0;
                const left = calcMaxLeft(qty, used);

                const form = document.getElementById('formAddReject');
                form.action = `/admin/return-requests/items/${id}/actions`;

                const qtyInput = document.getElementById('rjQty');
                qtyInput.value = left > 0 ? 1 : 0;
                qtyInput.min = 1;
                qtyInput.max = left;
                document.getElementById('rjHint').textContent = `Có thể từ chối tối đa ${left} sản phẩm.`;

                form.onsubmit = function(ev) {
                    if (parseInt(qtyInput.value || 0) > left) {
                        ev.preventDefault();
                        alert(`❌ Vượt quá số lượng còn lại (${left}).`);
                    }
                };
            });
        })();
    </script>
    <script>
        document.querySelectorAll('.js-exchange-form').forEach(f => {
            f.addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang tạo...';
            });
        });
    </script>
@endpush
