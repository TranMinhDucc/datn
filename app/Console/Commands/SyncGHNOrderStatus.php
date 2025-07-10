<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ShippingOrder;
use App\Models\Order;
use App\Models\ShippingLog;
use Illuminate\Support\Facades\Log;

class SyncGHNOrderStatus extends Command
{
    protected $signature = 'ghn:sync-order-status';
    protected $description = 'Sync GHN order shipping status and update to DB';
    protected $statusDescriptions = [
        'ready_to_pick' => 'Đơn đã sẵn sàng, chờ GHN đến lấy.',
        'picking' => 'Đơn hàng đang được nhân viên GHN đến lấy tại shop.',
        'cancel' => 'Đơn hàng đã bị huỷ.',
        'money_collect_picking' => 'GHN đang thu tiền khi lấy hàng (trạng thái hiện tại).',
        'picked' => 'Đơn hàng đã được GHN lấy thành công.',
        'storing' => 'Hàng đang được lưu tại kho GHN.',
        'transporting' => 'Đơn hàng đang trên đường vận chuyển đến kho tiếp theo hoặc khách hàng.',
        'sorting' => 'Hàng đang trong quá trình phân loại tại kho trung chuyển.',
        'delivering' => 'Đơn hàng đang được giao đến tay người nhận.',
        'money_collect_delivering' => 'Đơn hàng đang được giao và GHN sẽ thu tiền từ người nhận (COD).',
        'delivered' => 'Đơn hàng đã giao thành công cho người nhận.',
        'delivery_fail' => 'GHN giao hàng thất bại.',
        'waiting_to_return' => 'Đơn hàng đang chờ xử lý trả hàng.',
        'return' => 'Đơn hàng đã chuyển sang trạng thái trả hàng.',
        'return_transporting' => 'Đơn hàng đang trên đường trả về.',
        'return_sorting' => 'Đơn hàng đang được phân loại khi trả về.',
        'returning' => 'Đơn hàng đang trên đường trả lại shop.',
        'return_fail' => 'Trả hàng thất bại.',
        'returned' => 'Đơn hàng đã được trả lại shop.',
    ];

    public function handle()
    {
        Log::info('🔁 Bắt đầu sync đơn GHN');

        $pendingOrders = ShippingOrder::whereNotIn('status', ['delivered', 'cancelled'])->get();

        foreach ($pendingOrders as $shippingOrder) {
            Log::info("➡️ Đang xử lý đơn: " . $shippingOrder->shipping_code);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => config('services.ghn.token'),
            ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
                'order_code' => $shippingOrder->shipping_code,
            ]);

            if ($response->successful()) {
                $newStatus = $response->json('data.status');
                Log::info("✅ GHN trả về: $newStatus");

                $oldStatus = $shippingOrder->status;

                if ($newStatus !== $oldStatus) {
                    // Cập nhật shipping_orders
                    $shippingOrder->update(['status' => $newStatus]);

                    // Cập nhật đơn hàng nếu có
                    $order = Order::find($shippingOrder->order_id);
                    if ($order) {
                        $order->update(['shipping_status' => $newStatus]);
                    }

                    // Ghi log
                    ShippingLog::create([
                        'order_id'      => $shippingOrder->order_id,
                        'provider'      => 'ghn',
                        'tracking_code' => $shippingOrder->shipping_code,
                        'status'        => $newStatus,
                        'description' => $this->statusDescriptions[$newStatus] ?? 'Cập nhật trạng thái từ GHN',
                        'received_at'   => now(),
                    ]);

                    Log::info("📦 Ghi log trạng thái: {$shippingOrder->shipping_code} → $newStatus");
                } else {
                    Log::info("⏩ Không có thay đổi trạng thái với đơn: " . $shippingOrder->shipping_code);
                }
            } else {
                Log::warning("❌ GHN lỗi đơn: " . $shippingOrder->shipping_code . ' - ' . $response->body());
            }
        }
    }
}
