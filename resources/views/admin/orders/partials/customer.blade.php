<div class="card card-flush py-4 flex-row-fluid">
    <div class="card-header">
        <div class="card-title">
            <h2>Thông tin khách hàng</h2>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
            <tbody class="fw-semibold text-gray-600">
                <tr>
                    <td class="text-muted">Tên</td>
                    <td class="fw-bold text-end">{{ $order->user->fullname }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Email</td>
                    <td class="fw-bold text-end">{{ $order->user->email }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Số điện thoại</td>
                    <td class="fw-bold text-end">{{ $order->user->phone ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
