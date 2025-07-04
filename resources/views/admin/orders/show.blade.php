@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')
@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid ">


    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container  container-xxl ">
        <!--begin::Order details page-->
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_summary">Order Summary</a>
                    </li>
                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_history">Order History</a>
                    </li>
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->

                <!--begin::Button-->
                <a href="listing.html" class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
                    <i class="ki-duotone ki-left fs-2"></i> </a>
                <!--end::Button-->

                <!--begin::Button-->
                <a href="edit-order.html" class="btn btn-success btn-sm me-lg-n7">Edit Order</a>
                <!--end::Button-->

                <!--begin::Button-->
                <a href="add-order.html" class="btn btn-primary btn-sm">Add New Order</a>
                <!--end::Button-->
            </div>
            <!--begin::Order summary-->
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
                                    <!-- Date Added -->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-calendar-days fs-4 me-2 text-primary"></i>
                                                Ngày đặt hàng
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $order->created_at->format('d/m/Y') }}</td>
                                    </tr>

                                    <!-- Payment Method -->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-credit-card fs-4 me-2 text-success"></i>
                                                Payment Method
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            {{ $order->paymentMethod->name ?? 'Không xác định' }}
                                        </td>
                                    </tr>

                                    <!-- Shipping Method -->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-truck fs-4 me-2 text-info"></i>
                                                Shipping Method
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            Giao hàng tiêu chuẩn
                                        </td>
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
                            <h2>Chi tiết người dùng</h2>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <tbody class="fw-semibold text-gray-600">
                                    <!-- Tên khách hàng + Avatar -->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-user fs-4 me-2 text-primary"></i> Khách hàng
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <!-- Avatar -->
                                                <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                    <div class="symbol-label">
                                                        <img src="{{ $order->user->avatar_url ?? asset('default-avatar.png') }}" class="w-100" alt="{{ $order->user->name }}">
                                                    </div>
                                                </div>
                                                <!-- Tên -->
                                                <span class="text-gray-600">{{ $order->user->fullname }}</span>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Email -->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-envelope fs-4 me-2 text-info"></i> E-mail
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <a href="mailto:{{ $order->user->email }}" class="text-gray-600 text-hover-primary">
                                                {{ $order->user->email }}
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Điện thoại -->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-phone fs-4 me-2 text-success"></i> Điện thoại
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $order->user->phone ?? '—' }}</td>
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
                                                <i class="ki-duotone ki-devices fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Invoice


                                                <span class="ms-1" data-bs-toggle="tooltip" title="View the invoice generated by this order.">
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end"><a href="../../invoices/view/invoice-3.html" class="text-gray-600 text-hover-primary">#INV-000414</a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-truck fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Shipping


                                                <span class="ms-1" data-bs-toggle="tooltip" title="View the shipping manifest generated by this order.">
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end"><a href="#" class="text-gray-600 text-hover-primary">#SHP-0025410</a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-discount fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Reward Points


                                                <span class="ms-1" data-bs-toggle="tooltip" title="Reward value earned by customer when purchasing this order">
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">600</td>
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
            <div class="mb-4">
                <h2>📝 Trạng thái đơn hàng:</h2>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" id="status-form-{{ $order->id }}">
                    @csrf
                    @method('PUT')

                    <select name="status"
                        class="form-select fw-semibold"
                        onchange="document.getElementById('status-form-{{ $order->id }}').submit();">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🕐 Chờ xác nhận</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Đã xác nhận</option>
                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>🚚 Đang giao hàng</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>🎉 Đã hoàn tất</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Đã huỷ</option>
                    </select>
                </form>
            </div>






            <!--end::Order summary-->

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
                                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                    <i class="ki-solid ki-two-credit-cart" style="font-size: 14em">
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
                                    {{ $order->address->title ?? '' }}<br />
                                    {{ $order->address->address ?? '' }}<br />
                                    {{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }}<br />
                                    {{ $order->address->country ?? '' }} - {{ $order->address->pincode ?? '' }}
                                </div>

                                <!--end::Card body-->
                            </div>
                            <!--end::Payment address-->
                            <!--begin::Shipping address-->
                            <div class="card card-flush py-4 flex-row-fluid position-relative">
                                <!--begin::Background-->
                                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                    <i class="ki-solid ki-delivery" style="font-size: 13em">
                                    </i>
                                </div>
                                <!--end::Background-->

                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Shipping Address</h2>
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
                            <!--end::Shipping address-->
                        </div>

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
                                                            <span class="symbol-label" style="background-image:url('{{ $item->image_url ?? asset('default-image.png') }}');"></span>

                                                        </a>
                                                        <!-- Tên + ngày giao -->
                                                        <div class="ms-5">
                                                            <a href="#" class="fw-bold text-gray-600 text-hover-primary">
                                                                {{ $item->product->name }}
                                                            </a>
                                                            <div class="fs-7 text-muted">Ngày giao hàng: {{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d/m/Y') : '—' }}</div>
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
                                            <tr>
                                                <td>02/06/2025</td>
                                                <td>
                                                    Order completed </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-success">Completed</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    No </td>
                                            </tr>
                                            <tr>
                                                <td>01/06/2025</td>
                                                <td>
                                                    Order received by customer </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-success">Delivered</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    Yes </td>
                                            </tr>
                                            <tr>
                                                <td>31/05/2025</td>
                                                <td>
                                                    Order shipped from warehouse </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-primary">Delivering</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    Yes </td>
                                            </tr>
                                            <tr>
                                                <td>30/05/2025</td>
                                                <td>
                                                    Payment received </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-primary">Processing</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    No </td>
                                            </tr>
                                            <tr>
                                                <td>29/05/2025</td>
                                                <td>
                                                    Pending payment </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-warning">Pending</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    No </td>
                                            </tr>
                                            <tr>
                                                <td>28/05/2025</td>
                                                <td>
                                                    Payment method updated </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-warning">Pending</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    No </td>
                                            </tr>
                                            <tr>
                                                <td>27/05/2025</td>
                                                <td>
                                                    Payment method expired </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-danger">Failed</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    Yes </td>
                                            </tr>
                                            <tr>
                                                <td>26/05/2025</td>
                                                <td>
                                                    Pending payment </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-warning">Pending</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    No </td>
                                            </tr>
                                            <tr>
                                                <td>25/05/2025</td>
                                                <td>
                                                    Order received </td>
                                                <td>
                                                    <!--begin::Badges-->
                                                    <div class="badge badge-light-warning">Pending</div>
                                                    <!--end::Badges-->
                                                </td>
                                                <td>
                                                    Yes </td>
                                            </tr>
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
                                                <td class="fw-bold text-end">Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36</td>
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

@endsection