@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')
@section('content')

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content  flex-column-fluid ">





        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl ">

            <!--begin::Order details page-->
            <!--begin::Xác nhận đơn hàng-->

            <!--end::Xác nhận đơn hàng-->
            {{-- Banner theo từng yêu cầu đổi có link tới đơn đổi đã tạo --}}
            @if (!empty($exchangesByRR) && $exchangesByRR->count())
                @foreach ($exchangesByRR as $rrx)
                    <div class="alert alert-info d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fa-solid fa-rotate me-2"></i>
                            Đã tạo <strong>đơn đổi #{{ $rrx->exchange_order_id }}</strong>
                            từ yêu cầu đổi <strong>#RR{{ $rrx->id }}</strong>.
                            <a class="fw-semibold text-primary"
                                href="{{ route('admin.orders.show', $rrx->exchange_order_id) }}">
                                Xem đơn đổi
                            </a>
                        </div>
                        <span class="badge badge-light-primary">Exchange</span>
                    </div>
                @endforeach
            @endif


            <div class="d-flex flex-column gap-7 gap-lg-10">

                <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                    <!--begin:::Tabs-->
                    <ul
                        class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                href="#kt_ecommerce_sales_order_summary">Thông tin đơn hàng</a>
                        </li>
                        <!--end:::Tab item-->

                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                href="#kt_ecommerce_sales_order_history">Lịch sử đơn hàng</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->

                    <!--begin::Button-->
                    <a href="listing.html" class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
                        <i class="fa-solid fa-arrow-left fs-2"></i> </a>
                    <!--end::Button-->
                    {{-- NÚT TRẠNG THÁI (chỉ thay block dropdown cũ bằng block này) --}}
                    <div class="d-flex gap-2">

                        @php
                            // Nếu controller đã truyền, dùng trực tiếp; nếu chưa thì dùng fallback 5 trạng thái cơ bản
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
                                'exchanged' => '✅ Đã đổi xong',
                                'refund_processing' => '💳 Đang hoàn tiền',
                                'refunded' => '✅ Đã hoàn tiền',
                            ];

                            // Danh sách trạng thái kế tiếp hợp lệ; nếu chưa có thì cho phép tất cả trừ trạng thái hiện tại
                            $availableStatuses =
                                $availableStatuses ?? array_diff(array_keys($statusLabels), [$order->status]);

                            $currentLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                        @endphp

                        @if ($order->status !== 'cancelled')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light-primary fw-bold dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
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
                                                    // Các trạng thái yêu cầu nhập lý do
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
                            <span class="badge bg-danger fw-bold">
                                {{ $currentLabel }} – Đơn hàng đã bị huỷ
                            </span>
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
                                            if (reason === null) return; // người dùng hủy
                                            form.querySelector('[name="reason"]').value = reason;
                                            form.submit();
                                        }
                                    });
                                });
                            </script>
                        @endpush


                        @php
                            $latestLog = $order->shippingLogs->sortByDesc('created_at')->first();
                        @endphp
                        {{-- @if ($order->status === 'confirmed' && in_array(optional($order->latestShippingLog)->status, ['returning', 'return', 'return_fail'])) --}}
                        <form action="{{ route('admin.orders.retryShipping', $order->id) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fa fa-rotate"></i> Giao lại đơn hàng
                            </button>
                        </form>
                        {{-- @endif --}}
                        <form action="{{ route('admin.orders.ghn.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn hủy đơn hàng GHN này không?')">
                                <i class="fa-solid fa-rotate-left"></i> Hủy đơn GHN
                            </button>
                        </form>

                        {{-- @if ($order->status === 'pending' || ($latestLog && $latestLog->status === 'cancel')) --}}
                        <form id="confirm-ghn-form" action="{{ route('admin.orders.confirm-ghn', $order->id) }}"
                            method="POST" style="display: none;">
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
                        {{-- @endif --}}
                    </div>
                </div>
                <!--begin::Order summary-->
                <!-- Modal for creating exchange order -->
                <div class="modal fade" id="exchangeOrderModal" tabindex="-1" aria-labelledby="exchangeOrderModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            @if ($returnRequests->count() > 0)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exchangeOrderModal">
                                    🔄 Tạo đơn đổi hàng
                                </button>
                            @else
                                <span class="text-muted">Không có yêu cầu đổi hàng</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div
                    class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row align-items-center justify-content-center p-5 ">
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <span>
                            <i class="fa-solid fa-bell"></i> Vui lòng thực hiện CRON JOB liên kết:
                            <a class="text-primary" href="/cron/sync-ghn-orders" target="_blank">CRON ORDER SHIPPING</a>
                            1 phút 1 lần hoặc nhanh hơn để hệ thống xử lý gửi đơn hàng shipping tự động.
                        </span>
                    </div>

                    <!--end::Wrapper-->

                    <!--begin::Close-->
                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="fa-solid fa-xmark fs-1 text-info"><span class="path1"></span><span
                                class="path2"></span></i>
                    </button>
                    <!--end::Close-->
                </div>
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
                                    style="max-width: 300px;" class="img-thumbnail">
                            </div>
                        @endif

                        @if (is_null($order->refunded_at))
                            <div class="d-flex gap-2">
                            </div>
                        @else
                            <div class="badge bg-success fs-6">
                                Đã xử lý hoàn hàng vào {{ $order->refunded_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                @endif

                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                    <!--begin::Order details-->

                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Chi tiết đơn hàng (#{{ $order->order_code }})</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-calendar-days fs-65 me-2 text-gray-400"></i> Ngày
                                                    tạo
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $order->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-money-check-dollar fs-6 me-2 text-gray-400"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span></i> Phương
                                                    thức thanh toán
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                Online
                                                <img src="../../../assets/media/svg/card-logos/visa.svg"
                                                    class="w-50px ms-2" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-truck-moving fs-6 me-2 text-gray-400"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span><span
                                                            class="path5"></span></i> Phương thức vận chuyển
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">Flat Shipping Rate</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order details-->

                    <!--begin::Customer details-->
                    <div class="card card-flush py-4  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Chi tiết khách hàng</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-circle-user fs-6 me-2 text-gray-400"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i> Khách hàng
                                                </div>
                                            </td>

                                            <td class="fw-bold text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <!--begin:: Avatar -->
                                                    <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                        <a href="../customers/details.html">
                                                            <div class="symbol-label">
                                                                <img src="https://img.lovepik.com/png/20231019/customer-login-avatar-client-gray-head-portrait_269373_wh860.png"
                                                                    alt="Dan Wilson" class="w-100" />
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <!--end::Avatar-->

                                                    <!--begin::Name-->
                                                    <a href="{{ $order->user ? route('admin.users.edit', $order->user) : '#' }}"
                                                        class="text-gray-600 text-hover-primary">
                                                        {{ $order->user->fullname }} </a>
                                                    <!--end::Name-->
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-envelope fs-6 me-2 text-gray-400"><span
                                                            class="path1"></span><span class="path2"></span></i> Email
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <a href="../../user-management/users/view.html"
                                                    class="text-gray-600 text-hover-primary">
                                                    {{ $order->user->email ?? 'Chưa cập nhật' }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-phone-volume fs-6 me-2 text-gray-400"><span
                                                            class="path1"></span><span class="path2"></span></i> Phone
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ $order->user->phone ?? 'Chưa cập nhật' }}
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Customer details-->
                    <!--begin::Documents-->
                    <div class="card card-flush py-4  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Documents</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-mobile fs-6 me-2 text-gray-400	"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span><span
                                                            class="path5"></span></i> Invoice


                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="View the invoice generated by this order.">
                                                        <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i></span>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end"><a href="../../invoices/view/invoice-3.html"
                                                    class="text-gray-600 text-hover-primary">#INV-000414</a></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-truck-moving fs-6 text-gray-400	 me-2"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span><span
                                                            class="path5"></span></i> Shipping


                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Mã vận đơn từ bên phía giao hàng">
                                                        <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i></span>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end"><a href="#"
                                                    class="text-gray-600 text-hover-primary">{{ $order->shippingOrder->shipping_code ?? 'Chưa tạo vận đơn' }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-file-invoice text-gray-500 fs-6 me-2"><span
                                                            class="path1"></span><span class="path2"></span></i> Thanh
                                                    toán


                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Khách hàng đã thanh toán hay chưa">
                                                        <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i></span>
                                                </div>
                                            </td>
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
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Documents-->
                </div>
                <!--end::Order summary-->
                @if ($returnRequests->count())
                    @foreach ($returnRequests as $request)
                        <div class="card mb-3">
                            <div class="card-header">
                                Yêu cầu
                                @if ($request->type === 'exchange')
                                    Đổi hàng
                                @elseif ($request->type === 'return')
                                    Hoàn hàng
                                @endif
                                #{{ $request->id }} – Trạng thái: {{ $request->status }}
                            </div>

                            <div class="card-body">
                                <ul>
                                    @foreach ($request->items as $item)
                                        @php
                                            $variant = $item->orderItem->productVariant ?? null;
                                            $variantAttributes = '';

                                            if ($variant && $variant->options && $variant->options->count()) {
                                                $attributes = $variant->options
                                                    ->map(
                                                        fn($opt) => optional($opt->attribute)->name .
                                                            ': ' .
                                                            optional($opt->value)->value,
                                                    )
                                                    ->toArray();
                                                $variantAttributes = ' – ' . implode(', ', $attributes);
                                            }
                                        @endphp

                                        <li>
                                            {{ $item->orderItem->product_name }}{!! $variantAttributes !!} – SL:
                                            {{ $item->quantity }}
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Các bước xử lý --}}
                                @if ($request->status === 'pending')
                                    <form action="{{ route('admin.return-requests.approve', $request->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Duyệt yêu cầu</button>
                                    </form>
                                    <form action="{{ route('admin.return-requests.reject', $request->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <!-- Nút mở modal từ chối -->
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#rejectReasonModal" data-id="{{ $request->id }}">
                                            Từ chối
                                        </button>

                                    </form>
                                @elseif ($request->status === 'approved')
                                    @if ($request->type === 'exchange')
                                        <a href="{{ route('admin.return-requests.exchange.form', $request->id) }}"
                                            class="btn btn-primary btn-sm">
                                            Tạo đơn hàng đổi
                                        </a>
                                    @elseif ($request->type === 'return')
                                        <a href="{{ route('admin.return-requests.refund', $request->id) }}"
                                            class="btn btn-warning btn-sm">
                                            Xử lý hoàn tiền
                                        </a>
                                    @endif
                                @elseif ($request->status === 'exchanged' && $request->exchange_order_id)
                                    <a href="{{ route('admin.orders.show', $request->exchange_order_id) }}"
                                        class="btn btn-info btn-sm">
                                        Xem đơn hàng đổi
                                    </a>
                                @elseif ($request->status === 'refunded')
                                    <span class="badge bg-success">Đã hoàn tiền</span>
                                @elseif ($request->status === 'rejected')
                                    <span class="badge bg-danger">Bị từ chối</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif


                {{-- <h5 class="mt-4">Lịch sử đổi/trả hàng</h5>

                @if ($returnRequests->isEmpty())
                    <p>Chưa có yêu cầu đổi/trả nào cho đơn hàng này.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Loại</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Lý do</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returnRequests as $request)
                                @foreach ($request->items as $item)
                                    <tr>
                                        <td>#{{ $request->id }}</td>
                                        <td>{{ $request->type ?? 'exchange' }}</td>
                                        <td>
                                            {{ $item->orderItem->product->name }}
                                            @if ($item->orderItem->productVariant)
                                                - {{ $item->orderItem->productVariant->variant_name }}
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->reason }}</td>
                                        <td>{{ ucfirst($request->status) }}</td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-primary">Xem</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endif --}}

                <!--begin::Tab content-->
                <div class="tab-content">
                    <!--begin::Tab pane-->
                    <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                        <!--begin::Orders-->
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                                <!--begin::Payment address-->
                                <div class="card card-flush py-4 flex-row-fluid position-relative">
                                    <!--begin::Background-->
                                    <div
                                        class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                        <i class="fa-solid fa-credit-card" style="font-size: 14em">
                                        </i>
                                    </div>
                                    <!--end::Background-->

                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Billing Address</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        Unit 1/23 Hastings Road,<br />
                                        Melbourne 3000,<br />
                                        Victoria,<br />
                                        Australia.
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Payment address-->
                                <!--begin::Shipping address-->
                                <div class="card card-flush py-4 flex-row-fluid position-relative">
                                    <!--begin::Background-->
                                    <div
                                        class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                        <i class="fa-solid fa-truck" style="font-size: 13em">
                                        </i>
                                    </div>
                                    <!--end::Background-->

                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Địa chỉ nhận hàng</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        @if ($order->shippingAddress)
                                            <p><strong>Họ tên:</strong> {{ $order->shippingAddress->full_name }}</p>
                                            <p><strong>Địa chỉ:</strong> {{ $order->shippingAddress->address }}</p>
                                            <p><strong>Phường/Xã:</strong>
                                                {{ $order->shippingAddress->ward->name ?? '---' }}
                                            </p>
                                            <p><strong>Quận/Huyện:</strong>
                                                {{ $order->shippingAddress->district->name ?? '---' }}
                                            </p>
                                            <p><strong>Tỉnh/Thành phố:</strong>
                                                {{ $order->shippingAddress->province->name ?? '---' }}
                                            </p>
                                        @else
                                            <p class="text-danger">Không có thông tin địa chỉ giao hàng</p>
                                        @endif
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Shipping address-->
                            </div>
                            <!--begin::Shipping note-->
                            <div class="card card-flush py-4 flex-row-fluid">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Thông tin giao hàng (Shipper)</h2>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                    <form method="POST" action="{{ route('admin.orders.updateGhnNote', $order->id) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Lưu ý giao hàng *</label>
                                            <select name="required_note_shipper" class="form-select" required>
                                                <option value="KHONGCHOXEMHANG"
                                                    {{ $order->required_note_shipper == 'KHONGCHOXEMHANG' ? 'selected' : '' }}>
                                                    Không cho xem hàng
                                                </option>
                                                <option value="CHOXEMHANGKHONGTHU"
                                                    {{ $order->required_note_shipper == 'CHOXEMHANGKHONGTHU' ? 'selected' : '' }}>
                                                    Cho xem hàng, không cho thử
                                                </option>
                                                <option value="CHOTHUHANG"
                                                    {{ $order->required_note_shipper == 'CHOTHUHANG' ? 'selected' : '' }}>
                                                    Cho thử hàng
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ghi chú giao hàng</label>
                                            <input type="text" name="note_shipper" class="form-control"
                                                value="{{ old('note_shipper', $order->note_shipper) }}">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Cập nhật GHN</button>
                                    </form>



                                </div>
                            </div>
                            <!--end::Shipping note-->

                            <!--begin::Product List-->
                            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Đơn hàng {{ $order->order_code }}</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->

                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
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
                                                        <!-- Sản phẩm + ảnh -->
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <!-- Thumbnail -->
                                                                <a href="#" class="symbol symbol-50px">
                                                                    <span class="symbol-label"
                                                                        style="background-image:url('{{ $item->image_url ?? asset('default-image.png') }}');"></span>

                                                                </a>
                                                                <!-- Tên + ngày giao -->
                                                                <div class="ms-5">
                                                                    <a href="#"
                                                                        class="fw-bold text-gray-600 text-hover-primary">
                                                                        {{ $item->product->name }}
                                                                    </a>
                                                                    <div class="fs-7 text-muted">Ngày giao hàng:
                                                                        {{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d/m/Y') : '—' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <!-- Mã sản phẩm -->
                                                        <td class="text-end">
                                                            {{ $item->productVariant->sku ?? 'N/A' }}
                                                        </td>

                                                        <!-- Số lượng -->
                                                        <td class="text-end">
                                                            {{ $item->quantity }}
                                                        </td>

                                                        <!-- Đơn giá -->
                                                        <td class="text-end">
                                                            {{ number_format($item->price) }}đ
                                                        </td>

                                                        <!-- Tổng cộng -->
                                                        <td class="text-end">
                                                            {{ number_format($item->total_price) }}đ
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <!-- Tổng phụ -->
                                                <tr>
                                                    <td colspan="4" class="text-end">Tổng cộng</td>
                                                    <td class="text-end">{{ number_format($order->subtotal) }}đ</td>
                                                </tr>

                                                <!-- Thuế -->
                                                <tr>
                                                    <td colspan="4" class="text-end">Thuế VAT</td>
                                                    <td class="text-end">{{ number_format($order->tax_amount) }}đ</td>
                                                </tr>

                                                <!-- Phí vận chuyển -->
                                                <tr>
                                                    <td colspan="4" class="text-end">Phí vận chuyển</td>
                                                    <td class="text-end">{{ number_format($order->shipping_fee) }}đ</td>
                                                </tr>
                                                <!-- Giảm giá sản phẩm -->
                                                @if ($order->coupon)
                                                    <tr>
                                                        <td colspan="4" class="text-end text-danger">
                                                            Mã giảm giá sản phẩm ({{ $order->coupon->code }})
                                                        </td>
                                                        <td class="text-end text-danger">
                                                            @if ($order->coupon->value_type === 'fixed')
                                                                -{{ number_format($order->coupon->discount_value) }}đ
                                                            @else
                                                                -{{ $order->coupon->discount_value }}%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif


                                                <!-- Giảm giá phí vận chuyển -->
                                                @if ($order->shippingCoupon)
                                                    <tr>
                                                        <td colspan="4" class="text-end text-danger">
                                                            Mã giảm giá vận chuyển ({{ $order->shippingCoupon->code }})
                                                        </td>
                                                        <td class="text-end text-danger">
                                                            @if ($order->shippingCoupon->value_type === 'fixed')
                                                                -{{ number_format($order->shippingCoupon->discount_value) }}đ
                                                            @else
                                                                -{{ $order->shippingCoupon->discount_value }}%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                <!-- Phí xử lý -->


                                                <!-- Tổng cuối -->
                                                <tr>
                                                    <td colspan="4" class="fs-3 text-gray-900 text-end">Tổng cộng</td>
                                                    <td class="text-gray-900 fs-3 fw-bolder text-end">
                                                        {{ number_format($order->total_amount) }}đ
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Product List-->
                        </div>
                        <!--end::Orders-->
                    </div>
                    <!--end::Tab pane-->

                    <!--begin::Tab pane-->
                    <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                        <!--begin::Orders-->
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <!--begin::Order history-->
                            <div class="card card-flush py-4 flex-row-fluid">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Order History</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->

                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px">Date Added</th>
                                                    <th class="min-w-175px">Comment</th>
                                                    <th class="min-w-70px">Order Status</th>
                                                    <th class="min-w-100px">Customer Notifed</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                @forelse ($order->shippingLogs->sortByDesc('received_at') as $log)
                                                    <tr>
                                                        <td>{{ $log->received_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            {{ $log->description }}
                                                        </td>
                                                        <td>
                                                            <!--begin::Badges-->
                                                            <div class="badge badge-light-success">
                                                                {{ ucfirst($log->status) }}
                                                            </div>
                                                            <!--end::Badges-->
                                                        </td>
                                                        <td>
                                                            No </td>
                                                    </tr>

                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Chưa có log vận
                                                            chuyển</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Order history-->
                            <!--begin::Order data-->
                            <div class="card card-flush py-4 flex-row-fluid">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Order Data</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->

                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
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
                                                    <td class="fw-bold text-end">Mozilla/5.0 (Windows NT 10.0; Win64; x64)
                                                        AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110
                                                        Safari/537.36</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Accept Language</td>
                                                    <td class="fw-bold text-end">en-GB,en-US;q=0.9,en;q=0.8</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Order data-->
                        </div>
                        <!--end::Orders-->
                    </div>
                    <!--end::Tab pane-->
                </div>
                <!--end::Tab content-->
            </div>


            <!--end::Order details page-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    <!-- Modal nhập lý do huỷ -->
    <div class="modal fade" id="cancelReasonModal" tabindex="-1" aria-labelledby="cancelReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lý do huỷ đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <textarea id="cancelReasonInput" class="form-control" rows="4" placeholder="Nhập lý do huỷ đơn..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-danger" onclick="submitCancelOrder()">Xác nhận huỷ</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal nhập lý do từ chối -->
    <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nhập lý do từ chối</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" rows="4" placeholder="Nhập lý do từ chối..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("rejectReasonModal");
            modal.addEventListener("show.bs.modal", function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute("data-id");
                const form = modal.querySelector("#rejectForm");
                form.action = "/admin/return-requests/" + id + "/reject"; // route reject
            });
        });
    </script>
@endpush
