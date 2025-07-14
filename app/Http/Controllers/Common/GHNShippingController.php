<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Shipping\GhnService;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\PartnerLocationCode;
use App\Services\GhnService as ServicesGhnService;
use Illuminate\Support\Facades\Log;

class GHNShippingController extends Controller
{
    protected $ghn;

    public function __construct(ServicesGhnService $ghn)
    {
        $this->ghn = $ghn;
    }

    public function createOrder(Request $request, $orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        $address = ShippingAddress::findOrFail($order->address_id);

        $districtCode = PartnerLocationCode::where([
            ['location_id', $address->district_id],
            ['type', 'district'],
            ['partner_code', 'ghn']
        ])->value('partner_code'); // hoặc 'partner_location_code' nếu bạn dùng cột đó

        $wardCode = PartnerLocationCode::where([
            ['location_id', $address->ward_id],
            ['type', 'ward'],
            ['partner_code', 'ghn']
        ])->value('partner_code');

        if (!$districtCode || !$wardCode) {
            return response()->json([
                'message' => 'Không tìm thấy mã district/ward GHN tương ứng.'
            ], 422);
        }

        $payload = [
            'payment_type_id' => 1,
            'note' => 'Giao hàng cho khách',
            'required_note' => 'KHONGCHOXEMHANG',
            'to_name' => $address->title ?? 'Người nhận',
            'to_phone' => $address->phone,
            'to_address' => $address->address,
            'to_district_id' => (int) $districtCode,
            'to_ward_code' => (string) $wardCode,
            'weight' => 20.0,
            'length' => 30,
            'width' => 20,
            'height' => 10,
            'service_id' => 53320, // bạn có thể cho động sau này
            'items' => $order->items->map(fn($item) => [
                'name' => $item->product_name ?? 'Sản phẩm',
                'quantity' => $item->quantity,
            ])->toArray(),
        ];

        Log::info('GHN Request', $payload);
        Log::info('DEBUG GHN Token + ShopID', [
            'token' => config('services.ghn.token'),
            'shop_id' => config('services.ghn.shop_id')
        ]);

        $response = $this->ghn->createOrder($payload);

        if ($response['success']) {
            return response()->json([
                'message' => 'Tạo đơn GHN thành công',
                'data' => $response['data']
            ]);
        }

        Log::error('GHN Order Error', [
            'request' => $payload,
            'response' => $response['raw'] ?? $response
        ]);

        return response()->json([
            'message' => 'Tạo đơn GHN thất bại',
            'error' => $response['message'] ?? 'Đã có lỗi xảy ra.'
        ], 400);
    }
}
