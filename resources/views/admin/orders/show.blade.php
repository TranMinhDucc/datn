@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')

@section('content')
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
                            <div class="d-flex justify-content-between"><span>Tổng hàng + VAT +
                                    ship</span><strong>{{ number_format($order->subtotal + $order->tax_amount + $order->shipping_fee, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between"><span>Tổng điều
                                    chỉnh</span><strong>{{ number_format($order->adjustments_total, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span><u>Phải thu sau
                                        cùng</u></span><strong>{{ number_format($order->net_total, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Đã thu
                                    (payment)</span><strong>{{ number_format($order->paid_in, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Đã hoàn
                                    (refund)</span><strong>{{ number_format($order->refunded_out, 2) }}</strong></div>
                            <div class="d-flex justify-content-between fs-5">
                                <span><b>Số dư</b> (dương = KH còn thiếu, âm = cần hoàn)</span>
                                <strong>{{ number_format($order->balance, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======= Return/Exchange requests ======= --}}
                @if ($returnRequests->count())
                    @foreach ($returnRequests as $rr)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        @if ($rr->type === 'exchange')
                                            🔁 Yêu cầu đổi hàng
                                        @elseif ($rr->type === 'return')
                                            ↩️ Yêu cầu hoàn hàng
                                        @endif
                                        #{{ $rr->id }}
                                    </strong>
                                    @php
                                        // status cấp REQUEST: chỉ pending|approved|rejected|refunded
                                        $requestBadgeClass = match ($rr->status) {
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-primary',
                                            'refunded' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="ms-2 badge {{ $requestBadgeClass }}">{{ ucfirst($rr->status) }}</span>
                                </div>

                                @php
                                    $canCreateExchange =
                                        in_array($rr->status, ['pending', 'approved']) &&
                                        empty($rr->exchange_order_id) && // <<< thêm dòng này
                                        $rr->items->sum(
                                            fn($i) => $i->actions?->where('action', 'exchange')->sum('quantity') ?? 0,
                                        ) > 0;
                                @endphp


                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">{{ $rr->created_at->format('d/m/Y H:i') }}</small>

                                    @if ($canCreateExchange)
                                        <form action="{{ route('admin.return-requests.exchange', $rr->id) }}"
                                            method="POST" class="js-exchange-form d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Tạo đơn đổi</button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Bảng item trong request --}}
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th class="text-center" style="width:100px;">SL yêu cầu</th>
                                            <th class="text-center" style="width:210px;">Xử lý</th>
                                            <th class="text-center" style="width:160px;">Trạng thái</th>
                                            <th class="text-center" style="width:260px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rr->items as $it)
                                            @php
                                                // ==== tổng xử lý của item ====
                                                $exQty =
                                                    (int) ($it->actions
                                                        ?->where('action', 'exchange')
                                                        ->sum('quantity') ?? 0);
                                                $rfQty =
                                                    (int) ($it->actions?->where('action', 'refund')->sum('quantity') ??
                                                        0);
                                                $rjQty =
                                                    (int) ($it->actions?->where('action', 'reject')->sum('quantity') ??
                                                        0);
                                                $rfAmt =
                                                    (float) ($it->actions
                                                        ?->where('action', 'refund')
                                                        ->sum('refund_amount') ?? 0);

                                                // ==== trạng thái item ====
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
                                                        'badge' => 'bg-success',
                                                        'text' => '✅ Đồng ý đổi',
                                                    ],
                                                    'approved_refund' => [
                                                        'badge' => 'bg-info',
                                                        'text' => '💳 Hoàn tiền',
                                                    ],
                                                    'approved_mixed' => [
                                                        'badge' => 'bg-primary',
                                                        'text' => '🧩 Đã chia xử lý',
                                                    ],
                                                    'rejected' => ['badge' => 'bg-danger', 'text' => '❌ Từ chối'],
                                                    default => ['badge' => 'bg-warning', 'text' => '⏳ Chờ xử lý'],
                                                };

                                                // ==== thuộc tính biến thể hiển thị sau tên sản phẩm ====
                                                $attrs = '';
                                                $raw = $it->orderItem->variant_values ?? null; // có thể là JSON string hoặc array
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
                                                    $attrs = ' – ' . implode(', ', $pairs);
                                                } else {
                                                    // fallback từ quan hệ variant
                                                    $variant = $it->orderItem->productVariant ?? null;
                                                    if ($variant) {
                                                        $parts = [];
                                                        if (!empty($variant->color)) {
                                                            $parts[] = 'Màu: ' . $variant->color;
                                                        }
                                                        if (!empty($variant->size)) {
                                                            $parts[] = 'Size: ' . $variant->size;
                                                        }
                                                        if (
                                                            method_exists($variant, 'options') &&
                                                            $variant->options?->count()
                                                        ) {
                                                            foreach ($variant->options as $o) {
                                                                $n = optional($o->attribute)->name;
                                                                $v = optional($o->value)->value;
                                                                if ($n && $v) {
                                                                    $parts[] = $n . ': ' . $v;
                                                                }
                                                            }
                                                        }
                                                        if ($parts) {
                                                            $attrs = ' – ' . implode(', ', $parts);
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <tr>
                                                <td>{{ $it->orderItem->product_name }}{{ $attrs }}</td>

                                                <td class="text-center"><strong>{{ $it->quantity }}</strong></td>

                                                <td class="text-center">
                                                    <div class="small text-muted">
                                                        Đổi: <b>{{ $exQty }}</b> •
                                                        Hoàn: <b>{{ $rfQty }}</b> •
                                                        Từ chối: <b>{{ $rjQty }}</b>
                                                    </div>
                                                    @if ($rfAmt > 0)
                                                        <div class="small text-muted">Tổng hoàn:
                                                            {{ number_format($rfAmt, 2) }}</div>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $statusBadge['badge'] }}">{{ $statusBadge['text'] }}</span>
                                                </td>

                                                <td class="text-center">
                                                    {{-- DANH SÁCH ACTIONS ĐÃ THÊM --}}
                                                    @php
                                                        // Khóa khi RR đã có đơn đổi hoặc request đã kết thúc
                                                        $locked =
                                                            !empty($rr->exchange_order_id) ||
                                                            in_array($rr->status, ['refunded', 'rejected'], true);

                                                        // SL đã dùng & còn lại cho dòng này
                                                        $usedQty = (int) ($exQty + $rfQty + $rjQty);
                                                        $leftQty = max(0, (int) $it->quantity - $usedQty);
                                                    @endphp
                                                    @if ($it->actions?->count())
                                                        <ul class="list-unstyled mb-2 text-start small">
                                                            @foreach ($it->actions as $act)
                                                                <li
                                                                    class="d-flex justify-content-between align-items-center border rounded px-2 py-1 mb-1">
                                                                    <div>
                                                                        @if ($act->action === 'exchange')
                                                                            <span class="badge bg-success">Đổi</span>
                                                                            {{ optional($act->variant)->variant_name ?? (optional($act->variant)->sku ?? 'SKU hiện tại') }}
                                                                            × <b>{{ $act->quantity }}</b>
                                                                        @elseif ($act->action === 'refund')
                                                                            <span class="badge bg-info">Hoàn</span>
                                                                            SL: <b>{{ $act->quantity }}</b>
                                                                            @if ($act->refund_amount)
                                                                                —
                                                                                {{ number_format($act->refund_amount, 2) }}
                                                                            @endif
                                                                        @else
                                                                            <span class="badge bg-danger">Từ chối</span>
                                                                            SL: <b>{{ $act->quantity }}</b>
                                                                        @endif
                                                                        @if ($act->note)
                                                                            <em class="text-muted">—
                                                                                {{ $act->note }}</em>
                                                                        @endif
                                                                    </div>

                                                                    {{-- Xoá action --}}
                                                                    {{-- Xoá action (ẩn nếu đã khóa) --}}
                                                                    @if (!$locked)
                                                                        <form method="POST"
                                                                            action="{{ route('admin.return-requests.items.actions.destroy', $act->id) }}"
                                                                            onsubmit="return confirm('Xoá dòng xử lý này?');">
                                                                            @csrf @method('DELETE')
                                                                            <button
                                                                                class="btn btn-xs btn-light-danger">Xoá</button>
                                                                        </form>
                                                                    @endif

                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    @php
                                                        // Khoá sửa/xoá khi RR đã có đơn đổi hoặc request đã kết thúc
                                                        $locked =
                                                            !empty($rr->exchange_order_id) ||
                                                            in_array($rr->status, ['refunded', 'rejected'], true);

                                                        // SL đã dùng & còn lại cho dòng này
                                                        $usedQty = (int) ($exQty + $rfQty + $rjQty);
                                                        $leftQty = max(0, (int) $it->quantity - $usedQty);
                                                    @endphp

                                                    {{-- NÚT THÊM ACTION --}}
                                                    @if (!$locked && $leftQty > 0)
                                                        <div class="btn-group">
                                                            {{-- + Đổi --}}
                                                            <button type="button" class="btn btn-sm btn-primary"
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
                                                                + Đổi
                                                            </button>

                                                            {{-- + Hoàn --}}
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addRefundActionModal"
                                                                data-item-id="{{ $it->id }}"
                                                                data-qty="{{ $it->quantity }}"
                                                                data-used="{{ $usedQty }}">
                                                                + Hoàn
                                                            </button>

                                                            {{-- + Từ chối --}}
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addRejectActionModal"
                                                                data-item-id="{{ $it->id }}"
                                                                data-qty="{{ $it->quantity }}"
                                                                data-used="{{ $usedQty }}">
                                                                + Từ chối
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="text-muted small">
                                                            @if ($locked)
                                                                Yêu cầu đã khoá (đã tạo đơn đổi hoặc đã kết thúc).
                                                            @else
                                                                Đã xử lý đủ số lượng.
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
                                                        <td class="text-end">{{ number_format($item->total_price) }}đ</td>
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
                                                    <td class="text-end">{{ number_format($order->shipping_fee) }}đ</td>
                                                </tr>

                                                @if ($order->coupon)
                                                    <tr>
                                                        <td colspan="4" class="text-end text-danger">Mã giảm giá sản
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
                                                        <td colspan="4" class="text-end text-danger">Mã giảm giá vận
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
                                                    <td colspan="4" class="fs-3 text-gray-900 text-end">Tổng cộng</td>
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
                                                        <td colspan="4" class="text-center text-muted">Chưa có log vận
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
