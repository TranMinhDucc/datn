<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\ShippingOrder;
use App\Models\Order;
use App\Models\ShippingLog;
use App\Models\ReturnRequest;

class SyncGHNOrderStatus extends Command
{
    protected $signature = 'ghn:sync-order-status';
    protected $description = 'Sync GHN order shipping status and update to DB';

    protected $statusDescriptions = [
        'ready_to_pick' => 'Tạo đơn vận chuyển thành công',
        'picking' => 'Đơn hàng đang được nhân viên GHN đến lấy tại shop.',
        'cancel' => 'Đơn hàng đã bị huỷ.',
        'money_collect_picking' => 'GHN đang thu tiền khi lấy hàng.',
        'picked' => 'Đơn hàng đã được GHN lấy thành công.',
        'storing' => 'Hàng đang được lưu tại kho GHN.',
        'transporting' => 'Đơn hàng đang trên đường vận chuyển.',
        'sorting' => 'Hàng đang phân loại tại kho trung chuyển.',
        'delivering' => 'Đơn hàng đang được giao đến khách.',
        'money_collect_delivering' => 'GHN đang giao và thu tiền (COD).',
        'delivered' => 'Đơn hàng đã giao thành công.',
        'delivery_fail' => 'GHN giao hàng thất bại.',
        'waiting_to_return' => 'Đang đợi trả hàng về cho SHOP',
        'return' => 'Đơn đang chuyển sang trả hàng.',
        'return_transporting' => 'Đang trả hàng về.',
        'return_sorting' => 'Đang phân loại khi trả.',
        'returning' => 'Đang trả lại shop.',
        'return_fail' => 'Trả hàng thất bại.',
        'returned' => 'Đã trả lại shop.',
    ];

    private function mapGhnToOrderStatus(string $ghn): ?string
    {
        $map = [
            'ready_to_pick'            => 'processing',
            'picking'                  => 'processing',
            'picked'                   => 'ready_for_dispatch',
            'storing'                  => 'shipping',
            'transporting'             => 'shipping',
            'sorting'                  => 'shipping',
            'delivering'               => 'shipping',
            'money_collect_delivering' => 'shipping',

            'delivered'                => 'delivered',
            'delivery_fail'            => 'delivery_failed',
            'waiting_to_return'        => 'delivery_failed',

            // luồng trả hàng GHN
            'return'                   => 'returning',
            'return_transporting'      => 'returning',
            'return_sorting'           => 'returning',
            'returning'                => 'returning',
            'return_fail'              => 'delivery_failed',
            'returned'                 => 'returned',

            'cancel'                   => 'cancelled',
            'exception'                => 'delivery_failed',
            'damage'                   => 'delivery_failed',
            'lost'                     => 'delivery_failed',
        ];
        return $map[$ghn] ?? null;
    }

    /**
     * Cập nhật bảng orders từ trạng thái GHN (map + timestamps).
     * Trả về true nếu có thay đổi status order.
     */
    private function updateOrderFromGhnStatus(Order $order, string $ghnStatus): bool
    {
        // lưu shipping_status (nếu có cột)
        if (Schema::hasColumn('orders', 'shipping_status')) {
            $order->shipping_status = $ghnStatus;
        }

        $mapped = $this->mapGhnToOrderStatus($ghnStatus);
        $changed = false;

        if ($mapped && $mapped !== $order->status) {
            // mốc thời gian
            if ($mapped === 'delivered' && Schema::hasColumn('orders', 'delivered_at') && !$order->delivered_at) {
                $order->delivered_at = now();
            }
            if ($mapped === 'cancelled' && Schema::hasColumn('orders', 'cancelled_at') && !$order->cancelled_at) {
                $order->cancelled_at = now();
            }
            if ($mapped === 'delivered') {
                // Nếu đơn chưa thanh toán (COD) → cập nhật thành paid
                if (Schema::hasColumn('orders', 'payment_status') && $order->payment_status !== 'paid') {
                    $order->payment_status = 'paid';
                    if (Schema::hasColumn('orders', 'is_paid')) {
                        $order->is_paid = 1;
                    }
                    if (Schema::hasColumn('orders', 'paid_at') && !$order->paid_at) {
                        $order->paid_at = now();
                    }
                }
            }
            // completed thường do cron khác xử lý sau delivered → completed
            $order->status = $mapped;
            $changed = true;
        }

        $order->save();

        return $changed;
    }

    /**
     * Nếu là đơn ĐỔI và đã delivered/completed ⇒ đánh dấu ĐƠN GỐC → exchanged (idempotent).
     */
    private function markOriginExchangedIfNeeded(Order $order): void
    {
        // Chỉ chạy cho đơn ĐỔI
        if (!($order->is_exchange || $order->exchange_of_return_request_id)) return;

        // Chỉ khi đơn đổi đã xong
        if (!in_array($order->status, ['delivered', 'completed'], true)) return;

        $rrId = $order->exchange_of_return_request_id;
        if (!$rrId) return;

        $rr = ReturnRequest::with('order')->find($rrId);
        $origin = $rr?->order;
        if (!$origin) return;

        if ($origin->status !== 'exchanged') {
            // Tùy bạn nghiêm ngặt thì chỉ set khi đang 'exchange_requested'
            // if ($origin->status !== 'exchange_requested') return;

            $old = $origin->status;
            $origin->status = 'exchanged';
            $origin->save();

            ShippingLog::create([
                'order_id'      => $origin->id,
                'provider'      => 'manual',
                'tracking_code' => null,
                'status'        => 'exchanged',
                'description'   => "Đơn đổi #{$order->order_code} đã {$order->status}. Đánh dấu đơn gốc là exchanged (từ {$old}).",
                'received_at'   => now(),
            ]);

            Log::info("🔄 Mark origin exchanged: origin_id={$origin->id}, by exchange_order_id={$order->id}");
        }
    }

    public function handle()
    {
        Log::info('🔁 Bắt đầu sync đơn GHN');

        ShippingOrder::whereNotIn('status', ['delivered', 'cancel', 'returned'])
            ->orderBy('id')
            ->chunk(100, function ($shippingOrders) {
                foreach ($shippingOrders as $shippingOrder) {
                    $this->syncOne($shippingOrder); // gọi hàm xử lý riêng
                }
            });
    }
    private function syncOne(ShippingOrder $shippingOrder)
    {
        Log::info("➡️ Đang xử lý: {$shippingOrder->shipping_code}");

        $resp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => config('services.ghn.token'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
            'order_code' => $shippingOrder->shipping_code,
        ]);

        if (!$resp->successful()) {
            Log::warning("❌ GHN lỗi: {$shippingOrder->shipping_code}", ['body' => $resp->body()]);
            return;
        }

        $ghnStatus = data_get($resp->json(), 'data.status');
        if (!$ghnStatus) return;

        $oldGhn = $shippingOrder->status;

        if ($oldGhn !== $ghnStatus) {
            $shippingOrder->update(['status' => $ghnStatus]);

            ShippingLog::create([
                'order_id'      => $shippingOrder->order_id,
                'provider'      => 'ghn',
                'tracking_code' => $shippingOrder->shipping_code,
                'status'        => $ghnStatus,
                'description'   => $this->statusDescriptions[$ghnStatus] ?? 'Cập nhật trạng thái từ GHN',
                'received_at'   => now(),
            ]);
        }

        $order = Order::find($shippingOrder->order_id);
        if (!$order) return;

        $changed = $this->updateOrderFromGhnStatus($order, $ghnStatus);

        if ($changed || in_array($order->status, ['delivered', 'completed'], true)) {
            $this->markOriginExchangedIfNeeded($order);
        }

        Log::info("📦 Sync: GHN {$ghnStatus} → ORDER {$order->status} (order_id={$order->id})");

        // để tránh GHN chặn khi gọi quá nhiều request
        usleep(100 * 1000); // nghỉ 100ms
    }
}
