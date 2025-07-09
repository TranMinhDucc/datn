@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')
@section('content')

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Order details page-->
            <div class="d-flex flex-column gap-7 gap-lg-10">

                <!--begin::Xác nhận đơn hàng-->
                @if ($order->status === 'pending')
                    <form action="{{ route('admin.orders.confirm-ghn', $order->id) }}" method="POST" class="mb-5">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-paper-plane"></i> Xác nhận & Gửi đơn GHN
                        </button>
                    </form>
                @endif
                <!--end::Xác nhận đơn hàng-->

                <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                    <ul
                        class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                href="#kt_ecommerce_sales_order_summary">Tóm tắt đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                href="#kt_ecommerce_sales_order_history">Lịch sử đơn hàng</a>
                        </li>
                    </ul>

                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
                        <i class="ki-duotone ki-left fs-2"></i> Trở lại
                    </a>
                </div>

                @include('admin.orders.partials.summary', ['order' => $order])
                @include('admin.orders.partials.address', ['order' => $order])
                @include('admin.orders.partials.products', ['order' => $order])
                @include('admin.orders.partials.status', ['order' => $order])

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                        <!-- Toàn bộ thông tin tóm tắt đơn hàng đã bao gồm trong partials -->
                    </div>
                    <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                        @include('admin.orders.partials.history', ['order' => $order])
                    </div>
                </div>
            </div>
            <!--end::Order details page-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

@endsection
