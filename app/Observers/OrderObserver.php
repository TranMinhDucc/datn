<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatusHistory;

class OrderObserver
{
    /**
     * Tạo log khởi tạo khi đơn hàng được tạo
     */
    public function created(Order $order): void
    {
        // Nếu muốn chỉ tạo khi có status
        if (!empty($order->status)) {
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $order->status,
                'changed_by' => auth()->id() ?? 'system',
                'note'       => 'Khởi tạo đơn hàng',
                'meta'       => [
                    'ip'   => request()?->ip(),
                    'ua'   => request()?->userAgent(),
                    'src'  => 'created',
                ],
            ]);
        }
    }

    /**
     * Tự động log khi status thay đổi
     */
    public function updating(Order $order): void
    {
        // Chỉ log khi thật sự đổi status
        if ($order->isDirty('status')) {
            // Giá trị mới đã có ngay trên $order->status
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $order->status,
                'changed_by' => auth()->id() ?? 'system',
                // Có thể truyền note từ form bằng input name="note"
                'note'       => request()?->input('note'),
                'meta'       => [
                    'ip'   => request()?->ip(),
                    'ua'   => request()?->userAgent(),
                    'src'  => 'observer',
                ],
            ]);
        }
    }
}