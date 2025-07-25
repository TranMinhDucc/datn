<div class="mb-4">
    <h2>📝 Trạng thái đơn hàng:</h2>
    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}"
        id="status-form-{{ $order->id }}">
        @csrf
        @method('PUT')
        <select name="status" class="form-select fw-semibold"
            onchange="document.getElementById('status-form-{{ $order->id }}').submit();">
            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🕐 Chờ xác nhận</option>
            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Đã xác nhận</option>
            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>🚚 Đang giao</option>
            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>🎉 Đã hoàn tất</option>
            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Đã hủy</option>
        </select>
    </form>
</div>
