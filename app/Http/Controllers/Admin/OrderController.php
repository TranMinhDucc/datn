<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PartnerLocationCode;
use App\Models\ShopSetting;
use App\Services\GhnService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product', 'paymentMethod', 'address'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled'
        ]);

        $order->status = $validated['status'];
        $order->save();

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
    public function cancel()
    {
        $orders = Order::where('cancel_request', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.orders.cancel', compact('orders'));
    }

    public function approveCancel(Order $order)
    {
        if ($order->cancel_request && $order->status === 'confirmed') {
            $order->status = 'cancelled';
            $order->cancel_request = false;
            $order->cancelled_at = now();
            $order->save();

            return back()->with('success', 'Đã duyệt yêu cầu hủy đơn.');
        }

        return back()->with('error', 'Yêu cầu không hợp lệ hoặc đơn đã bị hủy.');
    }

    public function rejectCancel(Order $order)
    {
        if ($order->cancel_request && $order->status === 'confirmed') {
            $order->cancel_request = false;
            $order->save();

            return back()->with('success', 'Đã từ chối yêu cầu hủy đơn.');
        }

        return back()->with('error', 'Yêu cầu không hợp lệ.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    // public function confirm(Request $request, Order $order, GhnService $ghnService)
    // {
    //     if ($order->status !== 'pending') {
    //         return back()->with('error', 'Đơn hàng đã được xử lý.');
    //     }

    //     $address = $order->shippingAddress;

    //     // ✅ Tính trọng lượng & kích thước
    //     $totalWeight = $order->orderItems->sum(fn($item) => $item->weight * $item->quantity);
    //     $length = $order->orderItems->max('length');
    //     $width  = $order->orderItems->max('width');
    //     $height = $order->orderItems->sum(fn($item) => $item->height * $item->quantity);

    //     // ✅ Tạo dữ liệu gửi đến GHN
    //     $ghnData = [
    //         "payment_type_id"    => 2, // Người nhận trả phí ship
    //         "note"               => "Kiểm tra hàng",
    //         "required_note"      => "KHONGCHOXEMHANG",
    //         "from_district_id"   => (int) env('GHN_FROM_DISTRICT_ID'),
    //         "service_type_id"    => 2,
    //         "to_name"            => $address->name,
    //         "to_phone"           => $address->phone,
    //         "to_address"         => $address->detail,
    //         "to_ward_code"       => $address->ward->ghn_ward_code,
    //         "to_district_id"     => $address->district->ghn_district_id,
    //         "cod_amount"         => $order->total_price,
    //         "content"            => "Giao đơn hàng thời trang",
    //         "weight"             => $totalWeight,
    //         "length"             => $length,
    //         "width"              => $width,
    //         "height"             => $height,
    //         "insurance_value"    => $order->total_price,
    //         "items" => $order->orderItems->map(function ($item) {
    //             return [
    //                 "name"    => $item->product_name,
    //                 "quantity" => $item->quantity,
    //                 "price"   => $item->price,
    //                 "weight"  => $item->weight,
    //             ];
    //         })->toArray(),
    //     ];

    //     // ✅ Gửi đơn hàng đến GHN
    //     $orderCode = $ghnService->createShippingOrder($ghnData);

    //     if (!$orderCode) {
    //         return back()->with('error', 'Không thể gửi đơn hàng sang GHN.');
    //     }

    //     // ✅ Cập nhật đơn hàng
    //     $order->update([
    //         'status'          => 'confirmed',
    //         'shipping_fee'    => $order->shipping_fee,
    //         'ghn_order_code'  => $orderCode,
    //     ]);

    //     return back()->with('success', 'Đơn hàng đã được xác nhận và gửi GHN.');
    // }
    public function createShippingOrder(array $data)
    {
        // Ghi log debug Token và ShopId
        Log::info('GHN Token + ShopID', [
            'token' => env('GHN_TOKEN'),
            'shop_id' => env('GHN_SHOP_ID'),
        ]);

        // Ghi log payload gửi GHN
        Log::info('GHN Payload gửi đi', $data);

        // Gửi yêu cầu POST
        $response = Http::withHeaders([
            'Token' => env('GHN_TOKEN'),
            'Content-Type' => 'application/json',
            'ShopId' => env('GHN_SHOP_ID'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create', $data);

        // Ghi lại phản hồi đầy đủ từ GHN
        Log::info('GHN Response Raw', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        // Nếu thành công
        if ($response->successful() && isset($response['data']['order_code'])) {
            Log::info('GHN Order Created', [
                'order_code' => $response['data']['order_code'],
            ]);
            return $response['data']['order_code'];
        }

        // Nếu thất bại, ghi log chi tiết để điều tra
        Log::error('GHN Order Error', [
            'request' => $data,
            'response_status' => $response->status(),
            'response_body' => $response->body(),
        ]);

        return false;
    }

    public function confirmGHN($id, Request $request, GhnService $service)
    {
        $order = Order::with('items.productVariant.product', 'user', 'address')->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Đơn hàng không thể gửi đi do trạng thái không hợp lệ.');
        }

        $totalWeight = 0;
        $maxLength = 0;
        $maxWidth = 0;
        $totalHeight = 0;

        // Tính toán lại chính xác kích thước và cân nặng
        foreach ($order->items as $item) {
            $variant = $item->productVariant;

            $totalWeight += $variant->weight * $item->quantity;

            if ($variant->length > $maxLength) {
                $maxLength = $variant->length;
            }

            if ($variant->width > $maxWidth) {
                $maxWidth = $variant->width;
            }

            $totalHeight += $variant->height * $item->quantity;
        }

        $toDistrictId = PartnerLocationCode::where([
            'type' => 'district',
            'location_id' => $order->address->district_id,
            'partner_code' => 'ghn'
        ])->value('partner_id');

        $toWardCode = PartnerLocationCode::where([
            'type' => 'ward',
            'location_id' => $order->address->ward_id,
            'partner_code' => 'ghn'
        ])->value('partner_id');

        Log::info('ĐỊA CHỈ GHN', [
            'district_id nội bộ' => $order->address->district_id,
            'ward_id nội bộ' => $order->address->ward_id,
            'mapped to_district_id' => $toDistrictId,
            'mapped to_ward_code' => $toWardCode,
        ]);
        $shop = ShopSetting::with(['province', 'district', 'ward'])->first();

        $data = [
            'from_name'           => $shop->shop_name,
            'from_phone'          => $shop->shop_phone,
            'from_address'        => $shop->address,
            'from_ward_name'      => optional($shop->ward)->name,
            'from_district_name'  => optional($shop->district)->name,
            'from_province_name'  => optional($shop->province)->name,
            'payment_type_id'     => 1,
            'note'                => 'Giao hàng cho khách',
            'required_note'       => 'KHONGCHOXEMHANG',
            'to_name'             => $order->address->full_name,
            'to_phone'            => $order->address->phone,
            'to_address'          => $order->address->address,
            'to_district_id'      => $toDistrictId,
            'to_ward_code'        => (string)$toWardCode,
            'weight'              => $totalWeight ?: 100,
            'length'              => $maxLength ?: 10,
            'width'               => $maxWidth ?: 10,
            'height'              => $totalHeight ?: 10,
            'service_id'          => 53320,
            'items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->productVariant->product->name,
                    'quantity' => $item->quantity,
                    'code' => $item->productVariant->sku,
                    'image' => asset('storage/' . $item->productVariant->product->image),
                    'weight' => $item->productVariant->weight,
                ];
            })->toArray(),


        ];

        Log::info('GHN Request', $data);

        $ghnOrderCode = $service->createShippingOrder($data);

        if ($ghnOrderCode) {
            $order->update([
                'status' => 'confirmed',
                'ghn_order_code' => $ghnOrderCode
            ]);

            return redirect()->back()->with('success', '✅ Đã gửi đơn hàng sang GHN!');
        }

        return redirect()->back()->with('error', '❌ Gửi đơn hàng đến GHN thất bại.');
    }
}
