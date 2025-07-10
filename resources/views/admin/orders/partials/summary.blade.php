<div class="card card-flush py-4 flex-row-fluid">
    <div class="card-header">
        <div class="card-title">
            <h2>Chi tiết đơn hàng (#{{ $order->order_code }})</h2>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
            <tbody class="fw-semibold text-gray-600">
                <tr>
                    <td class="text-muted"><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Ngày đặt</td>
                    <td class="fw-bold text-end">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="text-muted"><i class="fa-solid fa-credit-card me-2 text-success"></i>Thanh toán</td>
                    <td class="fw-bold text-end">{{ $order->paymentMethod->name ?? 'Không xác định' }}</td>
                </tr>
                <tr>
                    <td class="text-muted"><i class="fa-solid fa-truck me-2 text-info"></i>Vận chuyển</td>
                    <td class="fw-bold text-end">Giao hàng tiêu chuẩn</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
