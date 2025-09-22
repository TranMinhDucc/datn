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

                                // Return flow
                                'return_requested' => '↩️ Yêu cầu trả hàng',
                                'returning' => '📦 Đang trả hàng về',
                                'returned' => '✅ Đã nhận hàng trả',

                                // Exchange flow
                                'exchange_requested' => '🔁 Yêu cầu đổi hàng',
                                'exchange_in_progress' => '🔄 Đang xử lý đổi',
                                'exchanged' => '✅ Đã đổi xong',

                                // Refund flow
                                'refund_processing' => '💳 Đang hoàn tiền',
                                'refunded' => '✅ Đã hoàn tiền',

                                // Kết hợp Exchange + Refund
                                'exchange_and_refund_processing' => '🔄💳 Đang đổi + hoàn tiền',

                                // Final closed
                                'closed' => '🔒 Đã đóng yêu cầu',
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
                                            <td class="text-muted">
                                                <i class="fa-solid fa-money-check-dollar fs-6 me-2 text-gray-400"></i>
                                                Phương thức thanh toán
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ $payment['label'] }}

                                                @if ($payment['img'])
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZcQPC-zWVyFOu9J2OGl0j2D220D49D0Z7BQ&s"
                                                        class="w-40px ms-2" alt="{{ $payment['label'] }}">
                                                @elseif ($payment['icon'])
                                                    <i class="{{ $payment['icon'] }} ms-2"></i>
                                                @endif

                                            </td>
                                        </tr>

                                        </tr>
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
                                            <td class="text-muted">
                                                <i class="fa-solid fa-mobile fs-6 me-2 text-gray-400"></i> Invoice
                                            </td>
                                            <td class="fw-bold text-end">
                                                @if ($onlineBill)
                                                    {{-- Có bill online (MoMo) --}}
                                                    <span class="badge badge-light-primary me-2">MoMo</span>
                                                    <span>#{{ $onlineBill }}</span>
                                                    {{-- Nếu sau này bạn có route xem chi tiết bill, đổi <span> ở trên thành <a href="..."> --}}
                                                @else
                                                    {{-- Không có bill online --}}
                                                    <span class="text-muted">Thanh toán online mới có bill</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-muted">
                                                <i class="fa-solid fa-truck-moving fs-6 text-gray-400 me-2"></i> Shipping
                                            </td>
                                            <td class="fw-bold text-end">
                                                @php $shipCode = $order->shippingOrder->shipping_code ?? null; @endphp

                                                @if ($shipCode)
                                                    <p class="mb-0 text-gray-600 text-hover-primary copy-text"
                                                        role="button" data-copy="{{ $shipCode }}"
                                                        title="Nhấp để sao chép">
                                                        {{ $shipCode }}
                                                        <i class="fa-regular fa-copy ms-2"></i>
                                                    </p>
                                                @else
                                                    <span class="text-muted">Chưa tạo vận đơn</span>
                                                @endif
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
                {{-- Shipping address --}}
                <div class="card card-flush py-4 flex-row-fluid shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shipping-fast text-primary me-3 fs-2"></i>
                                <h2 class="fw-bold text-gray-800 mb-0">Địa chỉ giao hàng</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @php $addr = $order->shippingAddress; @endphp

                        @if ($addr)
                            <div class="bg-light-primary rounded-3 p-4 mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold fs-5 text-gray-800 mb-1">
                                            {{ $addr->full_name }}
                                            @if ($addr->title)
                                                <span class="badge badge-light-info ms-2">{{ $addr->title }}</span>
                                            @endif
                                        </div>
                                        <div class="text-muted fs-6 mb-2">
                                            <i class="fas fa-phone me-1"></i>{{ $addr->phone }}
                                        </div>
                                        <div class="text-gray-700 fs-6 mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $shipToFull }}
                                        </div>
                                        @if ($addr->pincode)
                                            <div class="text-muted fs-7">
                                                <i class="fas fa-mail-bulk me-1"></i>Mã bưu chính: {{ $addr->pincode }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- (Tuỳ chọn) Hiện toàn bộ địa chỉ đã lưu của KH --}}
                            @if ($userAddresses->count())
                                <div class="separator separator-dashed my-4"></div>
                                <a class="btn btn-sm btn-light-primary" data-bs-toggle="collapse" href="#allAddresses">
                                    <i class="fas fa-address-book me-2"></i>
                                    Các địa chỉ đã lưu ({{ $userAddresses->count() }})
                                </a>
                                <div class="collapse mt-4" id="allAddresses">
                                    <div class="bg-light rounded-3 p-4">
                                        @foreach ($userAddresses as $a)
                                            <div
                                                class="d-flex align-items-start justify-content-between py-3 border-bottom border-gray-200">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold fs-6 text-gray-800 mb-1">
                                                        {{ $a->full_name }}
                                                        @if ($a->is_default)
                                                            <span class="badge badge-light-success ms-2">Mặc định</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted fs-7">{{ $a->phone }} •
                                                        {{ $a->full_address }}</div>
                                                </div>
                                                @if (($order->shipping_address_id ?? null) == $a->id)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check me-1"></i>Đang dùng cho đơn
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <div class="text-muted fs-4 mb-2">
                                    <i class="fas fa-map-marker-alt fs-2x text-gray-400 mb-3"></i>
                                </div>
                                <div class="text-muted fs-5">Chưa có địa chỉ giao hàng.</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Lưu ý – Ghi chú cho GHN --}}
                <div class="card card-flush py-4 shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-sticky-note text-warning me-3 fs-2"></i>
                                <h3 class="fw-bold text-gray-800 mb-0">Lưu ý – Ghi chú giao hàng (GHN)</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form method="POST" action="{{ route('admin.orders.updateGhnNote', $order->id) }}"
                            class="js-ghn-note-form">
                            @csrf
                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-4">
                                        <span class="required">Lưu ý giao hàng</span>
                                    </label>
                                    @php
                                        $required = $order->required_note_shipper ?? 'KHONGCHOXEMHANG';
                                    @endphp
                                    <div class="d-flex flex-column gap-4">
                                        <label class="form-check form-check-custom form-check-solid cursor-pointer">
                                            <input type="radio" name="required_note_shipper" class="form-check-input"
                                                value="KHONGCHOXEMHANG"
                                                {{ $required === 'KHONGCHOXEMHANG' ? 'checked' : '' }}>
                                            <span class="form-check-label fw-semibold text-gray-700">
                                                <i class="fas fa-eye-slash me-2 text-danger"></i>Không cho xem hàng
                                            </span>
                                        </label>
                                        <label class="form-check form-check-custom form-check-solid cursor-pointer">
                                            <input type="radio" name="required_note_shipper" class="form-check-input"
                                                value="CHOXEMHANGKHONGTHU"
                                                {{ $required === 'CHOXEMHANGKHONGTHU' ? 'checked' : '' }}>
                                            <span class="form-check-label fw-semibold text-gray-700">
                                                <i class="fas fa-eye me-2 text-info"></i>Cho xem hàng không cho thử
                                            </span>
                                        </label>
                                        <label class="form-check form-check-custom form-check-solid cursor-pointer">
                                            <input type="radio" name="required_note_shipper" class="form-check-input"
                                                value="CHOTHUHANG" {{ $required === 'CHOTHUHANG' ? 'checked' : '' }}>
                                            <span class="form-check-label fw-semibold text-gray-700">
                                                <i class="fas fa-hand-holding me-2 text-success"></i>Cho thử hàng
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-text mt-3 text-warning">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Cập nhật này yêu cầu đơn đã có mã GHN. Nếu chưa tạo vận đơn, hãy bấm "Xác nhận & Gửi
                                        đơn Shipping".
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Ghi chú</label>
                                    <textarea name="note_shipper" class="form-control form-control-solid" rows="7"
                                        placeholder="Ví dụ: Giao giờ hành chính, gọi trước 15 phút...">{{ old('note_shipper', $order->note_shipper) }}</textarea>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-6"></div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <span class="js-saving d-none spinner-border spinner-border-sm me-2"></span>
                                    <i class="fas fa-save me-2"></i>
                                    Cập nhật ghi chú GHN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Điều chỉnh --}}
                <div class="card card-flush py-4 shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cogs text-success me-3 fs-2"></i>
                                <h3 class="fw-bold text-gray-800 mb-0">Điều chỉnh</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form class="bg-light-primary rounded-3 p-4 mb-6" method="POST"
                            action="{{ route('admin.orders.adjustments.store', $order) }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Nhãn</label>
                                    <input name="label" class="form-control form-control-solid"
                                        placeholder="Phí vệ sinh / Chiết khấu..." required>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Mã</label>
                                    <input name="code" class="form-control form-control-solid" placeholder="CLEANING">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Loại</label>
                                    <select name="type" class="form-select form-select-solid">
                                        <option value="charge">
                                            <i class="fas fa-plus"></i> Cộng
                                        </option>
                                        <option value="discount">
                                            <i class="fas fa-minus"></i> Trừ
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Số tiền</label>
                                    <input name="amount" type="number" step="0.01" min="0.01"
                                        class="form-control form-control-solid" required>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Hiển thị KH</label>
                                    <select name="visible_to_customer" class="form-select form-select-solid">
                                        <option value="1">Có</option>
                                        <option value="0">Không</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Phân loại</label>
                                    <select name="category" class="form-select form-select-solid">
                                        <option value="exchange_credit">Tín dụng đổi hàng</option>
                                        <option value="price_diff">Chênh lệch giá</option>
                                        <option value="shipping_fee">Phí vận chuyển</option>
                                        <option value="manual_discount">Chiết khấu thủ công</option>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-12">
                                    <label class="form-label opacity-0">Action</label>
                                    <button class="btn btn-success w-100">
                                        <i class="fas fa-plus me-1"></i>Thêm
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 rounded-start">Nhãn</th>
                                        <th>Mã</th>
                                        <th>Loại</th>
                                        <th>Phân loại</th>
                                        <th>Hiển thị KH</th>
                                        <th class="text-end">Số tiền</th>
                                        <th class="text-end pe-4 rounded-end">Hành động</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($order->adjustments as $adj)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold text-gray-800">{{ $adj->label }}</div>
                                            </td>
                                            <td>
                                                <code class="badge badge-light-info">{{ $adj->code }}</code>
                                            </td>
                                            <td>
                                                @if ($adj->type === 'charge')
                                                    <span class="badge badge-light-success">
                                                        <i class="fas fa-plus me-1"></i>Cộng
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-danger">
                                                        <i class="fas fa-minus me-1"></i>Trừ
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge badge-light-primary">{{ $adj->category ?? '—' }}</span>
                                            </td>
                                            <td>
                                                @if ($adj->visible_to_customer)
                                                    <span class="badge bg-success">Có</span>
                                                @else
                                                    <span class="badge bg-secondary">Không</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($adj->amount, 0, ',', '.') }}đ
                                            </td>
                                            <td class="text-end pe-4">
                                                <form method="POST"
                                                    action="{{ route('admin.orders.adjustments.destroy', $adj) }}"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-light-danger"
                                                        onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-8">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fs-2x mb-3 text-gray-400"></i>
                                                    <div class="fs-5">Chưa có điều chỉnh</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                {{-- <tfoot>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th colspan="3" class="text-end ps-4">Tổng điều chỉnh</th>
                                        <th class="text-end">{{ number_format($order->adjustments_total, 0, ',', '.') }}đ
                                        </th>
                                        <th class="pe-4"></th>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>



                {{-- ======= Return/Exchange requests ======= --}}
                @if ($returnRequests->count())
                    @foreach ($returnRequests as $rr)
                        @php
                            $refundPending = \App\Models\Refund::where('return_request_id', $rr->id)
                                ->where('status', 'pending')
                                ->first();

                            if ($refundPending) {
                                // Ghi đè amount hiển thị chỉ tính refund QC passed
                                $amountQC = (float) $rr->items->sum(function ($it) {
                                    return (float) ($it->actions
                                        ?->where('action', 'refund')
                                        ->where('qc_status', 'passed')
                                        ->sum('refund_amount') ?? 0);
                                });
                                $refundPending->amount = $amountQC;
                            }

                            // $refundPending = \App\Models\Refund::where('return_request_id', $rr->id)
                            //     ->where('status', 'pending')
                            //     ->first();

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

                                            $canCreateExchange =
                                                in_array($rr->status, [
                                                    'pending',
                                                    'approved',
                                                    'exchange_in_progress',
                                                    'exchange_and_refund_processing',
                                                    'rejected_temp',
                                                ]) &&
                                                empty($rr->exchange_order_id) &&
                                                $rr->items->sum(
                                                    fn($i) => $i->actions
                                                        ?->where('action', 'exchange')
                                                        ->whereIn('qc_status', [
                                                            'passed',
                                                            'passed_import',
                                                            'passed_noimport',
                                                        ])
                                                        ->sum('quantity') ?? 0,
                                                ) > 0;

                                            $canCreateRefund =
                                                in_array($rr->status, [
                                                    'pending',
                                                    'approved',
                                                    'exchange_in_progress',
                                                    'refund_processing',
                                                    'exchange_and_refund_processing',
                                                    'rejected_temp',
                                                ]) &&
                                                !$refundPending &&
                                                $rr->items->sum(
                                                    fn($i) => $i->actions
                                                        ?->where('action', 'refund')
                                                        ->whereIn('qc_status', [
                                                            'passed',
                                                            'passed_import',
                                                            'passed_noimport',
                                                        ])
                                                        ->sum('quantity') ?? 0,
                                                ) > 0;
                                        @endphp

                                        <span class="badge {{ $requestBadgeClass }} fs-7">
                                            {{ ucfirst($rr->status) }}
                                        </span>

                                        {{-- Nút tạo đơn đổi --}}
                                        @if ($canCreateExchange)
                                            <form action="{{ route('admin.return-requests.exchange', $rr->id) }}"
                                                method="POST" class="js-exchange-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i> Tạo đơn đổi
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Nút tạo phiếu hoàn --}}
                                        @if ($canCreateRefund)
                                            <form action="{{ route('admin.return-requests.refund', $rr->id) }}"
                                                method="POST" class="js-refund-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-wallet me-1"></i> Tạo phiếu hoàn
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </div>

                                {{-- Nếu đã có phiếu hoàn pending thì hiển thị --}}
                                @if ($refundPending)
                                    <div class="mt-4 p-4 bg-light-primary rounded border-primary border border-dashed">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-warning fs-2 me-3"></i>
                                                <div>
                                                    <div class="fw-bold text-gray-800">Phiếu hoàn đang chờ xử lý</div>
                                                    <div class="text-muted fs-7">
                                                        Phiếu #{{ $refundPending->id }} -
                                                        {{ vnd($refundPending->amount) }}
                                                    </div>
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
                                                    <i class="fas fa-check me-1"></i> Đã chuyển
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Body - Danh sách items --}}
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 bg-light">
                                                <th class="ps-4 min-w-300px rounded-start">
                                                    <i class="fas fa-box fs-6 me-2"></i>
                                                    Sản phẩm
                                                </th>
                                                <th class="min-w-100px text-center">SL yêu cầu</th>
                                                <th class="min-w-150px text-center">Đã xử lý</th>
                                                <th class="min-w-120px text-center">Trạng thái</th>
                                                <th class="min-w-200px text-center">Ghi chú</th>
                                                <th class="min-w-150px text-center">Số tiền</th>
                                                <th class="min-w-250px text-end rounded-end pe-4">Thao tác</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($rr->items as $it)
                                                @php
                                                    $exQty =
                                                        (int) ($it->actions
                                                            ?->where('action', 'exchange')
                                                            ->where('qc_status', 'passed')
                                                            ->sum('quantity') ?? 0);

                                                    $rfQty =
                                                        (int) ($it->actions
                                                            ?->where('action', 'refund')
                                                            ->where('qc_status', 'passed')
                                                            ->sum('quantity') ?? 0);

                                                    $rjQty =
                                                        (int) ($it->actions
                                                            ?->where('action', 'reject')
                                                            ->sum('quantity') ?? 0);
                                                    $rfAmt =
                                                        (float) ($it->actions
                                                            ?->where('action', 'refund')
                                                            ->where('qc_status', 'passed')
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
                                                            'badge' => 'badge-light-success',
                                                            'icon' => 'fa-exchange-alt',
                                                            'text' => 'Đồng ý đổi',
                                                        ],
                                                        'approved_refund' => [
                                                            'badge' => 'badge-light-info',
                                                            'icon' => 'fa-wallet',
                                                            'text' => 'Hoàn tiền',
                                                        ],
                                                        'approved_mixed' => [
                                                            'badge' => 'badge-light-primary',
                                                            'icon' => 'fa-tasks',
                                                            'text' => 'Chia xử lý',
                                                        ],
                                                        'rejected' => [
                                                            'badge' => 'badge-light-danger',
                                                            'icon' => 'fa-times-circle',
                                                            'text' => 'Từ chối',
                                                        ],
                                                        default => [
                                                            'badge' => 'badge-light-warning',
                                                            'icon' => 'fa-clock',
                                                            'text' => 'Chờ xử lý',
                                                        ],
                                                    };

                                                    // Decode variant values
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
                                                        $attrs = implode(' • ', $pairs);
                                                    }

                                                    // ❌ KHÓA toàn bộ: chỉ khi request đã hoàn tất (refunded)
                                                    // Chỉ khóa toàn bộ nếu đã hoàn tiền xong
                                                    $locked = $rr->status === 'refunded';

                                                    // SL đã xử lý / còn lại
                                                    $usedQty = (int) ($exQty + $rfQty + $rjQty);
                                                    $leftQty = max(0, (int) $it->quantity - $usedQty);

                                                    // ❌ Khóa riêng item nếu hết số lượng
                                                    $itemLocked = $leftQty <= 0;
                                                @endphp

                                                <tr class="border-bottom border-gray-200">
                                                    {{-- Sản phẩm --}}
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-50px me-3">
                                                                <div class="symbol-label bg-light-primary">
                                                                    <i class="fas fa-cube fs-2x text-primary"></i>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span
                                                                    class="text-gray-800 fw-bold text-hover-primary fs-6">
                                                                    {{ $it->orderItem->product_name }}
                                                                </span>
                                                                @if ($attrs)
                                                                    <span
                                                                        class="text-gray-500 fw-semibold fs-7">{{ $attrs }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{-- SL yêu cầu --}}
                                                    <td class="text-center">
                                                        <div
                                                            class="badge badge-circle badge-lg badge-light-primary fw-bold">
                                                            {{ $it->quantity }}
                                                        </div>
                                                    </td>

                                                    {{-- Đã xử lý --}}
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column gap-2 align-items-center">
                                                            @if ($exQty > 0)
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="fas fa-exchange-alt fs-6 text-success me-1"></i>
                                                                    <span
                                                                        class="badge badge-light-success fw-semibold">Đổi:
                                                                        {{ $exQty }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($rfQty > 0)
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-wallet fs-6 text-info me-1"></i>
                                                                    <span class="badge badge-light-info fw-semibold">Hoàn:
                                                                        {{ $rfQty }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($rjQty > 0)
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="fas fa-times-circle fs-6 text-danger me-1"></i>
                                                                    <span class="badge badge-light-danger fw-semibold">Từ
                                                                        chối: {{ $rjQty }}</span>
                                                                </div>
                                                            @endif
                                                            @if (!$exQty && !$rfQty && !$rjQty)
                                                                <span class="text-gray-400 fs-7">—</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Trạng thái --}}
                                                    <td class="text-center">
                                                        <div class="badge {{ $statusBadge['badge'] }} fw-bold px-4 py-3">
                                                            <i class="fas {{ $statusBadge['icon'] }} fs-6 me-1"></i>
                                                            {{ $statusBadge['text'] }}
                                                        </div>
                                                    </td>

                                                    {{-- Ghi chú --}}
                                                    <td class="text-center">
                                                        @if ($it->actions->where('note', '!=', null)->count() > 0)
                                                            <div class="d-flex flex-column gap-1">
                                                                @foreach ($it->actions as $act)
                                                                    @if ($act->note)
                                                                        <div class="bg-light rounded p-2">
                                                                            <i
                                                                                class="fas fa-comment-dots fs-7 text-muted me-1"></i>
                                                                            <span
                                                                                class="text-gray-700 fs-7">{{ $act->note }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 fs-7">—</span>
                                                        @endif
                                                    </td>

                                                    {{-- Số tiền --}}
                                                    <td class="text-center">
                                                        @if ($rfAmt > 0)
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <i
                                                                    class="fas fa-money-bill-wave text-success fs-6 me-1"></i>
                                                                <span
                                                                    class="text-dark fw-bold fs-6">{{ vnd($rfAmt) }}</span>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 fs-7">—</span>
                                                        @endif
                                                    </td>

                                                    {{-- Thao tác --}}
                                                    <td class="text-end pe-4">
                                                        {{-- Các action đã thực hiện --}}
                                                        @if ($it->actions?->count())
                                                            <div class="d-flex flex-column gap-3">
                                                                @foreach ($it->actions as $act)
                                                                    <div class="card border-0 shadow-sm">
                                                                        <div
                                                                            class="card-header d-flex justify-content-between align-items-center py-2 px-3
                                    bg-{{ $act->action === 'exchange' ? 'success' : ($act->action === 'refund' ? 'info' : 'danger') }} bg-opacity-10">
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                @if ($act->action === 'exchange')
                                                                                    <span
                                                                                        class="badge bg-success text-white">
                                                                                        <i class="fas fa-exchange-alt"></i>
                                                                                        Đổi
                                                                                    </span>
                                                                                    <span class="fw-semibold">
                                                                                        {{ optional($act->variant)->variant_name ?? 'SKU hiện tại' }}
                                                                                    </span>
                                                                                @elseif ($act->action === 'refund')
                                                                                    <span class="badge bg-info text-white">
                                                                                        <i class="fas fa-wallet"></i> Hoàn
                                                                                    </span>
                                                                                    <span class="fw-semibold">1 sản
                                                                                        phẩm</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge bg-danger text-white">
                                                                                        <i class="fas fa-times-circle"></i>
                                                                                        Từ chối
                                                                                    </span>
                                                                                    <span class="fw-semibold">1 sản
                                                                                        phẩm</span>
                                                                                @endif
                                                                            </div>

                                                                            {{-- Xóa action --}}
                                                                            @if (!$locked && !$itemLocked)
                                                                                <form method="POST"
                                                                                    action="{{ route('admin.return-requests.items.actions.destroy', $act->id) }}">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="btn btn-sm btn-light-danger"
                                                                                        title="Xóa">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </div>

                                                                        {{-- Body: QC --}}
                                                                        <div class="card-body py-2 px-3">
                                                                            <form method="POST"
                                                                                action="{{ route('admin.return-actions.qc', $act->id) }}"
                                                                                class="d-flex flex-column gap-2">
                                                                                @csrf
                                                                                <div class="btn-group">
                                                                                    <button type="submit"
                                                                                        name="qc_status"
                                                                                        value="passed_import"
                                                                                        class="btn btn-sm btn-outline-success {{ $act->qc_status === 'passed_import' ? 'active' : '' }}">
                                                                                        <i class="fas fa-check"></i> QC đạt
                                                                                        + Nhập kho
                                                                                    </button>

                                                                                    <button type="submit"
                                                                                        name="qc_status"
                                                                                        value="passed_noimport"
                                                                                        class="btn btn-sm btn-outline-primary {{ $act->qc_status === 'passed_noimport' ? 'active' : '' }}">
                                                                                        <i class="fas fa-check-double"></i>
                                                                                        QC đạt (Không nhập kho)
                                                                                    </button>

                                                                                    <button type="submit"
                                                                                        name="qc_status" value="failed"
                                                                                        class="btn btn-sm btn-outline-danger {{ $act->qc_status === 'failed' ? 'active' : '' }}">
                                                                                        <i class="fas fa-times"></i> QC
                                                                                        hỏng
                                                                                    </button>
                                                                                </div>

                                                                                <input type="text" name="qc_note"
                                                                                    value="{{ $act->qc_note }}"
                                                                                    class="form-control form-control-sm"
                                                                                    placeholder="Ghi chú QC (nếu có)">
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        {{-- Nút thêm action mới --}}
                                                        @if ($locked)
                                                            <span class="badge badge-light-secondary">
                                                                <i class="fas fa-lock fs-6 me-1"></i> Đã khóa (toàn bộ)
                                                            </span>
                                                        @elseif ($itemLocked)
                                                            <span class="badge badge-light-secondary">
                                                                <i class="fas fa-lock fs-6 me-1"></i> Đã xử lý hết
                                                            </span>
                                                        @else
                                                            <div class="d-flex gap-2 justify-content-end">
                                                                {{-- ⚡ Giữ nguyên cụm 3 nút của bạn --}}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light-success btn-active-success"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addExchangeActionModal"
                                                                    data-item-id="{{ $it->id }}"
                                                                    data-qty="{{ $it->quantity }}"
                                                                    data-used="{{ $usedQty }}"
                                                                    data-variants='@json(optional($it->orderItem->product)->variants?->map(fn($v) => [
                                                                                'id' => $v->id,
                                                                                'label' =>
                                                                                    ($v->variant_name ?? $v->color . ' ' . $v->size) .
                                                                                    " — SKU: {$v->sku} — " .
                                                                                    number_format($v->price) .
                                                                                    'đ',
                                                                            ]) ?? []
                                                                    )'>
                                                                    <i class="fas fa-exchange-alt me-1"></i> Đổi
                                                                </button>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-light-warning btn-active-warning"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addRefundActionModal"
                                                                    data-item-id="{{ $it->id }}"
                                                                    data-qty="{{ $it->quantity }}"
                                                                    data-used="{{ $usedQty }}">
                                                                    <i class="fas fa-wallet me-1"></i> Hoàn
                                                                </button>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-light-danger btn-active-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addRejectActionModal"
                                                                    data-item-id="{{ $it->id }}"
                                                                    data-qty="{{ $it->quantity }}"
                                                                    data-used="{{ $usedQty }}">
                                                                    <i class="fas fa-times me-1"></i> Từ chối
                                                                </button>
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

                        {{-- Refund history --}}
                        @if ($order->refunds->count())
                            <div class="card mt-4 shadow-sm border-0">
                                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-wallet me-2 text-success"></i> Lịch sử phiếu hoàn
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr class="fw-semibold text-muted text-uppercase">
                                                    <th class="text-center" style="width: 60px">#</th>
                                                    <th>Số tiền</th>
                                                    <th>Mã giao dịch</th>
                                                    <th>Ngày chuyển</th>
                                                    <th>Người xử lý</th>
                                                    <th class="text-center">Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->refunds as $rf)
                                                    <tr>
                                                        <td class="text-center fw-bold">{{ $rf->id }}</td>
                                                        <td class="fw-bold text-success">{{ vnd($rf->amount) }}</td>
                                                        <td>
                                                            <span class="text-monospace">
                                                                {{ $rf->bank_ref ?? '—' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $rf->transferred_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                                        <td>{{ $rf->processor?->name ?? '—' }}</td>
                                                        <td class="text-center">
                                                            @if ($rf->status === 'pending')
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="fas fa-clock me-1"></i> Chờ xử lý
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check-circle me-1"></i> Đã chuyển
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                                    <td colspan="4" class="text-end">Phí vận chuyển (gốc)</td>
                                                    <td class="text-end">{{ number_format($order->shipping_fee) }}đ</td>
                                                </tr>
                                                @foreach ($order->adjustments as $adj)
                                                    @if ($adj->visible_to_customer)
                                                        <tr>
                                                            <td colspan="4" class="text-end">
                                                                {{ $adj->label }}
                                                                @if ($adj->category)
                                                                    <span
                                                                        class="badge bg-light-info text-dark ms-2">{{ strtoupper($adj->category) }}</span>
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-end {{ $adj->type === 'charge' ? 'text-success' : 'text-danger' }}">
                                                                {{ $adj->type === 'charge' ? '+' : '-' }}
                                                                {{ number_format($adj->amount, 0, ',', '.') }}đ
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

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

                                                {{-- <tr>
                                                    <td colspan="4" class="fs-3 text-gray-900 text-end">Tổng số tiền
                                                        phải
                                                        thanh toán</td>
                                                    <td class="text-gray-900 fs-3 fw-bolder text-end">
                                                        {{ number_format($order->balance, 0, ',', '.') }}đ
                                                    </td>
                                                </tr> --}}


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
                </div>
                {{-- Thanh toán / Hoàn tiền & Tổng kết tài chính --}}
                <div class="card card-flush py-4 shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title w-100">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-credit-card text-info me-3 fs-2"></i>
                                    <div>
                                        <h3 class="fw-bold text-gray-800 mb-0">Quản lý thanh toán</h3>
                                        <p class="text-muted mb-0 fs-7">Theo dõi thanh toán và tài chính đơn hàng</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        {{-- Form thêm giao dịch --}}
                        <form class="bg-light-info rounded-3 p-4 mb-6" method="POST"
                            action="{{ route('admin.orders.payments.store', $order) }}">
                            @csrf
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-plus-circle text-primary me-2 fs-4"></i>
                                <h6 class="mb-0 fw-bold text-gray-800">Thêm giao dịch mới</h6>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Loại</label>
                                    <select name="kind" class="form-select form-select-solid">
                                        <option value="payment">
                                            <i class="fas fa-arrow-down"></i> Thu thêm
                                        </option>
                                        <option value="refund">
                                            <i class="fas fa-arrow-up"></i> Hoàn lại
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Phương thức</label>
                                    <input name="method" class="form-control form-control-solid"
                                        placeholder="bank/cod/momo...">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Số tiền</label>
                                    <input name="amount" type="number" step="0.01" min="0.01"
                                        class="form-control form-control-solid" required>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-semibold fs-6 mb-2">Ghi chú</label>
                                    <input name="note" class="form-control form-control-solid"
                                        placeholder="Ghi chú thêm...">
                                </div>
                                <div class="col-lg-2 col-md-12">
                                    <label class="form-label opacity-0">Action</label>
                                    <button class="btn btn-info w-100">
                                        <i class="fas fa-save me-1"></i>Ghi nhận
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            {{-- Lịch sử giao dịch --}}
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-4">
                                    <i class="fas fa-history text-info me-2 fs-4"></i>
                                    <h5 class="mb-0 fw-bold text-gray-800">Lịch sử giao dịch</h5>
                                </div>

                                <div class="table-responsive mb-6" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                        <thead class="sticky-top">
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 rounded-start">Thời gian</th>
                                                <th>Loại</th>
                                                <th>PT</th>
                                                <th class="text-end">Số tiền</th>
                                                <th class="text-end pe-4 rounded-end">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($order->payments as $p)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="fw-semibold text-gray-700">
                                                            {{ $p->created_at->format('d/m/Y H:i') }}</div>
                                                        <div class="fs-7 text-muted">
                                                            {{ $p->created_at->diffForHumans() }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($p->kind === 'payment')
                                                            <span class="badge badge-light-success">
                                                                <i class="fas fa-arrow-down me-1"></i>Thu thêm
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light-warning">
                                                                <i class="fas fa-arrow-up me-1"></i>Hoàn lại
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light">{{ $p->method }}</span>
                                                    </td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($p->amount, 0, ',', '.') }}đ
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <form method="POST"
                                                            action="{{ route('admin.orders.payments.destroy', $p) }}"
                                                            class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-light-danger"
                                                                onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-8">
                                                        <div class="text-muted">
                                                            <i class="fas fa-money-bill-wave fs-2x mb-3 text-gray-400"></i>
                                                            <div class="fs-5">Chưa có giao dịch</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tổng kết tài chính --}}
                            <div class="col-12">
                                <div class="bg-light-secondary rounded-3 p-4 h-100">
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="fas fa-calculator text-primary me-2 fs-4"></i>
                                        <h5 class="fw-bold text-gray-800 mb-0">Tổng kết tài chính</h5>
                                    </div>

                                    <div class="d-flex flex-column gap-3">
                                        {{-- Tổng đơn hàng --}}
                                        <div
                                            class="d-flex justify-content-between align-items-center py-2 px-3 bg-white rounded">
                                            <span class="text-gray-600 fs-7 fw-semibold">Tổng hàng + VAT + ship</span>
                                            <strong
                                                class="text-gray-800 fs-6">{{ number_format($order->subtotal + $order->tax_amount + $order->shipping_fee, 0, ',', '.') }}đ</strong>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center py-2 px-3 bg-white rounded">
                                            <span class="text-gray-600 fs-7 fw-semibold">Tổng điều chỉnh</span>
                                            <strong
                                                class="text-gray-800 fs-6">{{ number_format($order->adjustments_total, 0, ',', '.') }}đ</strong>
                                        </div>

                                        {{-- Divider --}}
                                        <div class="separator border-gray-300 my-2"></div>

                                        {{-- Phải thu --}}
                                        <div
                                            class="d-flex justify-content-between align-items-center py-3 px-3 bg-light-primary rounded border border-primary border-dashed">
                                            <span class="fw-bold text-gray-800 fs-6">Phải thu sau cùng</span>
                                            <strong
                                                class="text-primary fs-5">{{ number_format($order->net_total, 0, ',', '.') }}đ</strong>
                                        </div>

                                        {{-- Divider --}}
                                        <div class="separator border-gray-300 my-2"></div>

                                        {{-- Đã thu/hoàn --}}
                                        <div
                                            class="d-flex justify-content-between align-items-center py-2 px-3 bg-white rounded">
                                            <span class="text-gray-600 fs-7 fw-semibold">Đã thu (payment)</span>
                                            <strong
                                                class="text-success fs-6">{{ number_format($order->paid_in, 0, ',', '.') }}đ</strong>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center py-2 px-3 bg-white rounded">
                                            <span class="text-gray-600 fs-7 fw-semibold">Đã hoàn (refund)</span>
                                            <strong
                                                class="text-warning fs-6">{{ number_format($order->refunded_out, 0, ',', '.') }}đ</strong>
                                        </div>

                                        {{-- Divider --}}
                                        <div class="separator border-gray-300 my-2"></div>

                                        {{-- Số dư --}}
                                        <div
                                            class="d-flex justify-content-between align-items-center py-3 px-3 bg-white rounded border border-2 {{ $order->balance > 0 ? 'border-danger' : ($order->balance < 0 ? 'border-info' : 'border-success') }}">
                                            <span class="fw-bold text-gray-800 fs-5">Số dư</span>
                                            <strong
                                                class="fs-4 {{ $order->balance > 0 ? 'text-danger' : ($order->balance < 0 ? 'text-info' : 'text-success') }}">
                                                {{ number_format($order->balance, 0, ',', '.') }}đ
                                            </strong>
                                        </div>

                                        {{-- Chú thích --}}
                                        <div class="alert alert-light-info mt-3 mb-0 py-3">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                                                <div class="text-info fs-7">
                                                    <strong>Chú thích:</strong> Số dương = KH còn thiếu, Số âm = cần hoàn
                                                    lại, Số 0 = đã thanh toán đủ
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        document.addEventListener('click', async (e) => {
            const el = e.target.closest('.copy-text');
            if (!el) return;

            const text = el.dataset.copy?.trim();
            if (!text) return;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                } else {
                    // Fallback cho browser cũ / không https
                    const ta = document.createElement('textarea');
                    ta.value = text;
                    ta.style.position = 'fixed';
                    ta.style.left = '-9999px';
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                }
                // Phản hồi nhỏ: đổi icon trong 1s
                const icon = el.querySelector('i');
                const old = icon?.className;
                if (icon) {
                    icon.className = 'fa-solid fa-check ms-2';
                    setTimeout(() => icon.className = old, 1200);
                }
                el.classList.add('text-success');
                setTimeout(() => el.classList.remove('text-success'), 1200);
            } catch (err) {
                console.error('Copy failed', err);
                alert('Không thể sao chép, vui lòng thử lại.');
            }
        });
    </script>
@endpush
@push('style')
    <style>
        /* Hover effect for table rows */
        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f5f8fa !important;
            transform: translateX(2px);
        }

        /* Badge circle styling */
        .badge-circle {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 14px;
        }

        /* Button hover effects */
        .btn-active-success:hover {
            background-color: #50cd89 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(80, 205, 137, 0.35);
        }

        .btn-active-success:hover i {
            animation: rotate 0.5s ease;
        }

        .btn-active-warning:hover {
            background-color: #ffc700 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 199, 0, 0.35);
        }

        .btn-active-danger:hover {
            background-color: #f1416c !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(241, 65, 108, 0.35);
        }

        /* Icon animations */
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(180deg);
            }
        }

        /* Smooth transitions */
        .btn,
        .badge {
            transition: all 0.2s ease;
        }

        /* Better spacing for actions */
        .table td:last-child {
            white-space: nowrap;
        }

        /* Symbol styling */
        .symbol-label {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.475rem;
        }

        /* Action cards hover */
        .bg-light-success,
        .bg-light-info,
        .bg-light-danger {
            transition: all 0.2s ease;
            cursor: default;
        }

        .bg-light-success:hover {
            background-color: rgba(80, 205, 137, 0.15) !important;
        }

        .bg-light-info:hover {
            background-color: rgba(114, 57, 234, 0.15) !important;
        }

        .bg-light-danger:hover {
            background-color: rgba(241, 65, 108, 0.15) !important;
        }

        /* Delete button animation */
        .btn-icon:hover i.fa-trash {
            animation: shake 0.3s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-2px);
            }

            75% {
                transform: translateX(2px);
            }
        }

        /* Status badge pulse */
        .badge-light-warning {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 199, 0, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 199, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 199, 0, 0);
            }
        }

        /* Responsive font sizes */
        @media (max-width: 768px) {
            .fs-6 {
                font-size: 0.9rem !important;
            }

            .fs-7 {
                font-size: 0.85rem !important;
            }
        }
    </style>
@endpush
