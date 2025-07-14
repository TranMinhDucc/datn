<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingOrder;
use App\Models\ShippingLog;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class GhnWebhookController extends Controller
{
    /**
     * Xử lý webhook từ GHN
     */
    public function handle(Request $request)
    {
        // Ghi log đầu vào nếu cần debug
        Log::info('GHN Webhook Received', $request->all());

        // Lấy dữ liệu từ GHN gửi về
        $data = $request->all();

        $trackingCode = $data['OrderCode'] ?? null;
        $status = $data['Status'] ?? null;
        $description = $data['Reason'] ?? null;
        $receivedAt = now();

        if (!$trackingCode || !$status) {
            return response()->json(['message' => 'Thiếu dữ liệu bắt buộc'], 400);
        }

        // Tìm đơn giao hàng tương ứng
        $shippingOrder = ShippingOrder::where('shipping_code', $trackingCode)->first();

        if (!$shippingOrder) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng tương ứng'], 404);
        }

        // Cập nhật trạng thái đơn hàng nếu muốn (tuỳ bạn)
        $shippingOrder->update([
            'status' => $status,
        ]);

        // Ghi log vào bảng shipping_logs
        ShippingLog::create([
            'order_id'      => $shippingOrder->order_id,
            'provider'      => 'ghn',
            'tracking_code' => $trackingCode,
            'status'        => $status,
            'description'   => $description,
            'received_at'   => $receivedAt,
        ]);

        return response()->json(['message' => 'OK'], 200);
    }
}
