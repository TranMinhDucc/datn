<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ShippingOrder;
use App\Models\Order;
use App\Models\ShippingLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
            'ready_to_pick'            => 'processing',          // GHN chờ lấy
            'picking'                  => 'processing',
            'picked'                   => 'ready_for_dispatch',  // có thể đổi thành 'shipping' nếu bàn giao ngay
            'storing'                  => 'shipping',
            'transporting'             => 'shipping',
            'sorting'                  => 'shipping',
            'delivering'               => 'shipping',
            'money_collect_delivering' => 'shipping',

            'delivered'                => 'delivered',
            'delivery_fail'            => 'delivery_failed',
            'waiting_to_return'        => 'delivery_failed',     // chờ trả hàng: coi như giao thất bại để xử lý tiếp

            // Luồng trả hàng GHN
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
    // public function handle()
    // {
    //     Log::info('🔁 Bắt đầu sync đơn GHN');

    //     $pendingOrders = ShippingOrder::whereNotIn('status', ['delivered', 'cancelled'])->get();

    //     foreach ($pendingOrders as $shippingOrder) {
    //         Log::info("➡️ Đang xử lý đơn: " . $shippingOrder->shipping_code);

    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'Token' => config('services.ghn.token'),
    //         ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
    //             'order_code' => $shippingOrder->shipping_code,
    //         ]);

    //         if ($response->successful()) {
    //             $newStatus = $response->json('data.status');
    //             Log::info("✅ GHN trả về: $newStatus");

    //             $oldStatus = $shippingOrder->status;

    //             if ($newStatus !== $oldStatus) {
    //                 // Cập nhật shipping_orders
    //                 $shippingOrder->update(['status' => $newStatus]);

    //                 // Cập nhật đơn hàng nếu có
    //                 $order = Order::find($shippingOrder->order_id);
    //                 if ($order) {
    //                     $newOrderStatus = $order->status;

    //                     // Nếu đơn đã giao thành công
    //                     if ($newStatus === 'delivered') {
    //                         $newOrderStatus = 'completed';
    //                     }
    //                     // Nếu đang trong quá trình giao hàng
    //                     elseif (in_array($newStatus, [
    //                         'picked',
    //                         'storing',
    //                         'transporting',
    //                         'sorting',
    //                         'delivering',
    //                         'money_collect_delivering',
    //                     ])) {
    //                         $newOrderStatus = 'shipping';
    //                     }
    //                     // Nếu GHN huỷ đơn
    //                     elseif ($newStatus === 'cancel') {
    //                         $newOrderStatus = 'cancelled';
    //                     }

    //                     $order->update([
    //                         'shipping_status' => $newStatus,
    //                         'status' => $newOrderStatus,
    //                     ]);
    //                 }


    //                 // Ghi log
    //                 ShippingLog::create([
    //                     'order_id'      => $shippingOrder->order_id,
    //                     'provider'      => 'ghn',
    //                     'tracking_code' => $shippingOrder->shipping_code,
    //                     'status'        => $newStatus,
    //                     'description'   => $this->statusDescriptions[$newStatus] ?? 'Cập nhật trạng thái từ GHN',
    //                     'received_at'   => now(),
    //                 ]);

    //                 Log::info("📦 Ghi log trạng thái: {$shippingOrder->shipping_code} → $newStatus");
    //             } else {
    //                 Log::info("⏩ Không có thay đổi trạng thái với đơn: " . $shippingOrder->shipping_code);
    //             }
    //         } else {
    //             Log::warning("❌ GHN lỗi đơn: " . $shippingOrder->shipping_code . ' - ' . $response->body());
    //         }
    //     }
    // }
    public function handle()
    {
        Log::info('🔁 Bắt đầu sync đơn GHN');

        // chỉ đồng bộ những đơn GHN chưa "kết thúc" phía GHN
        $pendingOrders = ShippingOrder::whereNotIn('status', [
            'delivered',
            'cancel',
            'returned'
        ])->get();

        foreach ($pendingOrders as $shippingOrder) {
            Log::info("➡️ Đang xử lý: {$shippingOrder->shipping_code}");

            $resp = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => config('services.ghn.token'),
            ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
                'order_code' => $shippingOrder->shipping_code,
            ]);

            if (!$resp->successful()) {
                Log::warning("❌ GHN lỗi: {$shippingOrder->shipping_code}", ['body' => $resp->body()]);
                continue;
            }

            $ghnStatus = data_get($resp->json(), 'data.status');
            if (!$ghnStatus) continue;

            $oldGhn = $shippingOrder->status;
            if ($oldGhn !== $ghnStatus) {
                // cập nhật bảng shipping_orders
                $shippingOrder->update(['status' => $ghnStatus]);
            }

            // Ghi log shipping_logs (như hiện tại)
            ShippingLog::create([
                'order_id'      => $shippingOrder->order_id,
                'provider'      => 'ghn',
                'tracking_code' => $shippingOrder->shipping_code,
                'status'        => $ghnStatus,
                'description'   => $this->statusDescriptions[$ghnStatus] ?? 'Cập nhật trạng thái từ GHN',
                'received_at'   => now(),
            ]);

            // ===========================
            //  CẬP NHẬT BẢNG ORDERS
            // ===========================
            $order = Order::find($shippingOrder->order_id);
            if (!$order) continue;

            // luôn lưu "shipping_status" để tra cứu
            if (Schema::hasColumn('orders', 'shipping_status')) {
                $order->shipping_status = $ghnStatus;
            }


            $mapped = $this->mapGhnToOrderStatus($ghnStatus);
            if ($mapped && $mapped !== $order->status) {
                // set mốc thời gian nếu có cột (tránh lỗi Unknown column)
                if ($mapped === 'delivered' && Schema::hasColumn('orders', 'delivered_at') && !$order->delivered_at) {
                    $order->delivered_at = now();
                }
                if ($mapped === 'cancelled' && Schema::hasColumn('orders', 'cancelled_at') && !$order->cancelled_at) {
                    $order->cancelled_at = now();
                }
                // GHI NHỚ: 'completed' không set trực tiếp từ GHN.
                // Bạn có CRON riêng auto chuyển delivered -> completed sau N ngày.

                $order->status = $mapped;
            }

            $order->save();

            Log::info("📦 Sync: GHN {$ghnStatus} → ORDER {$order->status} (order_id={$order->id})");
        }
    }
}
