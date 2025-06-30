@extends('layouts.admin')
@section('title', 'Yêu cầu hủy đơn hàng')
@section('content')

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Yêu cầu hủy đơn hàng
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Đơn hàng</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Yêu cầu hủy</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <h3 class="mb-0">Danh sách yêu cầu hủy đơn</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th class="text-end">Tổng tiền</th>
                                    <th class="text-end">Trạng thái</th>
                                    <th class="text-end">Lý do hủy</th>
                                    <th class="text-end">Ngày yêu cầu</th>
                                    <th class="text-end">Tên sản phẩm</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary fw-bold">
                                            {{ $order->order_code ?? '#DH' . $order->id }}
                                        </a>
                                    </td>
                                    <td>{{ $order->user->fullname ?? 'Khách lẻ' }}</td>
                                    <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td class="text-end">
                                        <span class="badge badge-light-warning">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ $order->cancel_reason ?? 'Không rõ' }}</td>
                                    <td class="text-end">{{ $order->updated_at->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        @foreach ($order->items->take(1) as $item)
                                        {{ $item->product_name }}{{ $order->items->count() > 1 ? ' +' . ($order->items->count() - 1) . ' SP' : '' }}
                                        @endforeach
                                    </td>

                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="menu-link px-3"> Xem chi tiết</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <form action="{{ route('admin.orders.approve_cancel', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="menu-link px-3 bg-transparent border-0"> Duyệt yêu cầu</button>
                                                </form>
                                            </div>
                                            <div class="menu-item px-3">
                                                <form action="{{ route('admin.orders.reject_cancel', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="menu-link px-3 bg-transparent border-0"> Từ chối yêu cầu</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có yêu cầu hủy đơn nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>


                        <div class="mt-5">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection