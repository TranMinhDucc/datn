<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PartnerLocationCode;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ReturnRequestItem;
use App\Models\ShippingMethod;
use App\Models\ShippingOrder;
use App\Models\ShopSetting;
use App\Models\User;
use App\Services\GhnService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Notifications\OrderStatusNotification;
use Illuminate\Support\Facades\DB;


use App\Mail\{
    OrderConfirmedMail,
    OrderPaidMail,
    OrderShippingMail,
    OrderCompletedMail,
    OrderCancelledMail,
    OrderReturnedMail,
    OrderRefundedMail
};
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchOrders = Order::query()->with(['user', 'shippingOrder']);

        if ($request->filled('order_code')) {
            $searchOrders->where('order_code', 'like', '%' . $request->order_code . '%');
        }

        if ($request->filled('status')) {
            $searchOrders->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $searchOrders->where('user_id', $request->user_id);
        }

        if ($request->filled('user_name')) {
            $searchOrders->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->filled('status')) {
            $searchOrders->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $searchOrders->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('from_date')) {
            $searchOrders->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $searchOrders->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $searchOrders->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('variants')->where('is_active', 1)->get();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $shippingMethods = ShippingMethod::all();

        // Chuẩn bị dữ liệu biến thể theo sản phẩm
        $productVariants = [];
        foreach ($products as $product) {
            $productVariants[$product->id] = $product->variants->map(function ($variant) {
                $attributes = $variant->options->map(function ($option) {
                    $attrName = optional($option->attribute)->name;
                    $value = optional($option->value)->value;
                    return $attrName . ': ' . $value;
                })->toArray();

                return [
                    'id' => $variant->id,
                    'variant_name' => implode(', ', $attributes) ?: 'Không có thuộc tính',
                    'price' => $variant->price,
                ];
            })->all();
        }

        return view('admin.orders.create', compact('products', 'users', 'paymentMethods', 'shippingMethods', 'productVariants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_method' => 'required|string',
            'address_id' => 'required|exists:shipping_addresses,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'address_id' => $validated['address_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'shipping_method' => $validated['shipping_method'],
                'subtotal' => 0,
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $subtotal = 0;
            // Thêm các mục đơn hàng
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $variant = $item['variant_id'] ? ProductVariant::findOrFail($item['variant_id']) : null;

                // Kiểm tra tồn kho
                $availableStock = $variant ? $variant->stock : $product->stock;
                if ($availableStock < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ tồn kho.");
                }

                $price = $variant ? $variant->price : $product->sale_price;
                $totalPrice = $price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'sku' => $variant?->sku ?? $product->sku,
                    'image_url' => $product->image,
                    'variant_values' => $variant ? json_encode($variant->options->pluck('value_id')->toArray()) : null,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'total_price' => $totalPrice,
                ]);

                $subtotal += $totalPrice;

                // Cập nhật tồn kho
                if ($variant) {
                    $variant->decrement('stock', $item['quantity']);
                } else {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Cập nhật tổng tiền đơn hàng
            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal + ($order->shipping_fee ?? 0),
            ]);

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $order = Order::with([
            'user',
            'shippingLogs',
            'orderItems.product',
            'paymentMethod',
            'shippingAddress.province',
            'shippingAddress.district',
            'shippingAddress.ward',
            // Load các yêu cầu đổi/trả hàng và item liên quan
            'returnRequests.items.orderItem.product',
            'returnRequests.items.orderItem.productVariant',
        ])->findOrFail($id);

        // Lấy tất cả yêu cầu đổi/trả (nếu có)
        $returnRequests = $order->returnRequests ?? collect();

        // Lấy danh sách tất cả sản phẩm (để hiển thị/thêm đơn mới)
        $products = Product::where('is_active', 1)
            ->with('variants')
            ->get();

        return view('admin.orders.show', [
            'order' => $order,
            'returnRequests' => $returnRequests,
            'products' => $products,
        ]);
    }



    public function updateStatus(Request $request, Order $order)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,confirmed,shipping,completed,cancelled'
    ]);

    $order->status = $validated['status'];
    $order->is_paid = $request->is_paid ?? $order->is_paid;
    $order->payment_status = $request->payment_status ?? $order->payment_status;

    // Cập nhật thời gian giao hàng nếu cần
    if ($validated['status'] === 'completed' && !$order->delivered_at) {
        $order->delivered_at = now();
    }

    $order->save();

    // ✅ Gửi email theo trạng thái
    $email = $order->user->email;

    switch ($order->status) {
        case 'confirmed':
            Mail::to($email)->send(new OrderConfirmedMail($order));
            if ($order->is_paid) {
                Mail::to($email)->send(new OrderPaidMail($order));
            }
            break;

        case 'shipping':
            Mail::to($email)->send(new OrderShippingMail($order));
            break;

        case 'completed':
            Mail::to($email)->send(new OrderCompletedMail($order));
            break;

        case 'cancelled':
            Mail::to($email)->send(new OrderCancelledMail($order));
            break;

        case 'returning':
        case 'returned':
            Mail::to($email)->send(new OrderReturnedMail($order));
            break;
    }

    if ($order->payment_status === 'refunded') {
        Mail::to($email)->send(new OrderRefundedMail($order));
    }

    // ✅ Gửi notification realtime tới user
    $order->user->notify(new OrderStatusNotification(
        $order->id,
        $order->status,
        $order,
        $request->cancel_reason,
        $request->image
    ));

    return back()->with('success', 'Cập nhật trạng thái & gửi email + notification thành công!');
}



    public function cancel()
    {
        $orders = Order::where('cancel_request', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.orders.cancel', compact('orders'));
    }
    public function retryShipping($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->status === 'cancelled') {
            return back()->with('error', '❌ Đơn hàng đã bị huỷ, không thể thao tác.');
        }

        Log::info('📦 retryShipping called with order id: ' . $orderId);

        // Tìm đơn GHN trong bảng shipping_orders
        $shippingOrder = ShippingOrder::where('order_id', $orderId)
            ->where('shipping_partner', 'ghn')
            ->latest()
            ->first();

        if (!$shippingOrder || !$shippingOrder->shipping_code) {
            return back()->with('error', '❌ Không tìm thấy mã GHN cho đơn hàng hoặc bạn chưa tạo vận đơn.');
        }

        // Gọi API GHN để lấy trạng thái hiện tại
        $statusResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => config('services.ghn.token'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
            'order_code' => $shippingOrder->shipping_code,
        ]);

        $currentStatus = $statusResponse->json('data.status') ?? 'unknown';
        Log::info("📦 Trạng thái GHN hiện tại của {$shippingOrder->shipping_code} là: $currentStatus");

        // ✅ Chỉ cho phép retry nếu trạng thái là waiting_to_return hoặc delivery_fail
        $allowedStatuses = ['waiting_to_return', 'delivery_fail'];
        if (!in_array($currentStatus, $allowedStatuses)) {
            $viStatus = $this->mapGhnStatus($currentStatus);
            return back()->with('error', "⚠️ Không thể giao lại đơn hàng vì trạng thái hiện tại là $viStatus.");
        }

        // Gọi API GHN để chuyển trạng thái đơn hàng sang "storing"
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => config('services.ghn.token'),
            'ShopId' => config('services.ghn.shop_id'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/switch-status/storing', [
            'order_codes' => [$shippingOrder->shipping_code]
        ]);

        $responseData = $response->json();
        Log::info('🔁 GHN Retry Shipping response', $responseData);

        if ($response->successful() && $responseData['code'] == 200) {
            $result = $responseData['data'][0]['result'] ?? false;
            $ghnMessage = $responseData['data'][0]['message'] ?? 'Không rõ thông báo';

            if ($result === true) {
                Log::info('✅ Giao lại đơn GHN thành công', [
                    'order_id' => $orderId,
                    'shipping_code' => $shippingOrder->shipping_code,
                ]);
                return back()->with('success', '✅ Đã gửi yêu cầu giao lại đơn hàng thành công.');
            }

            Log::warning("⚠️ GHN từ chối giao lại đơn (mã: {$shippingOrder->shipping_code}) vì: $ghnMessage");
            return back()->with('error', "⚠️ GHN từ chối giao lại đơn: $ghnMessage. Trạng thái hiện tại: $currentStatus");
        }

        Log::error('❌ Lỗi khi gửi lại đơn GHN', [
            'order_id' => $orderId,
            'shipping_code' => $shippingOrder->shipping_code,
            'response' => $response->body(),
        ]);

        return back()->with('error', '❌ Giao lại đơn hàng thất bại: ' . ($responseData['message'] ?? 'Không rõ lỗi'));
    }
    public function cancelShippingOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->status === 'cancelled') {
            return back()->with('error', '❌ Đơn hàng đã bị huỷ, không thể thao tác.');
        }

        Log::info('🛑 Bắt đầu huỷ đơn GHN cho order_id: ' . $orderId);

        $shippingOrder = ShippingOrder::where('order_id', $orderId)
            ->where('shipping_partner', 'ghn')
            ->latest()
            ->first();

        if (!$shippingOrder || !$shippingOrder->shipping_code) {
            return back()->with('error', '❌ Không tìm thấy mã GHN hoặc bạn chưa tạo vận đơn.');
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => config('services.ghn.token'),
            'ShopId' => config('services.ghn.shop_id'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/switch-status/cancel', [
            'order_codes' => [$shippingOrder->shipping_code]
        ]);

        $data = $response->json('data')[0] ?? [];
        $result = $data['result'] ?? false;
        $message = $data['message'] ?? 'Không rõ lý do';

        Log::info('🛑 GHN Cancel response', $response->json());

        if ($result === true) {
            // ✅ Cập nhật status trong bảng orders
            Order::where('id', $orderId)->update(['status' => 'cancelled']);

            Log::info('✅ Huỷ đơn GHN thành công & cập nhật DB', [
                'order_id' => $orderId,
                'shipping_code' => $shippingOrder->shipping_code,
            ]);

            return back()->with('success', '✅ Huỷ đơn hàng thành công.');
        } else {
            Log::warning('⚠️ GHN từ chối huỷ đơn', [
                'order_id' => $orderId,
                'shipping_code' => $shippingOrder->shipping_code,
                'ghn_message' => $message,
            ]);

            return back()->with('error', '⚠️ GHN từ chối huỷ đơn: ' . $message);
        }
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
            'body' => $response->body(),
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
        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', '❌ Đơn hàng này đã bị huỷ, không thể thao tác.');
        }

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
            $product = $variant?->product ?? $item->product;

            if (!$variant && !$product) {
                Log::error("❌ Không tìm thấy biến thể và sản phẩm cho OrderItem ID: {$item->id}, Order ID: {$order->id}");
                continue;
            }

            $weight = $variant?->weight ?? $product?->weight ?? 100;
            $length = $variant?->length ?? $product?->length ?? 10;
            $width = $variant?->width ?? $product?->width ?? 10;
            $height = $variant?->height ?? $product?->height ?? 10;

            $totalWeight += $weight * $item->quantity;

            if ($length > $maxLength)
                $maxLength = $length;
            if ($width > $maxWidth)
                $maxWidth = $width;

            $totalHeight += $height * $item->quantity;
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
        $availableServices = Http::withHeaders([
            'Token' => config('services.ghn.token'),
            'Content-Type' => 'application/json',
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
            'shop_id' => (int) config('services.ghn.shop_id'),
            'from_district' => $shop->district->ghn_district_id ?? 3440, // bạn có thể map riêng nếu cần
            'to_district' => (int) $toDistrictId,
        ]);

        $serviceId = data_get($availableServices->json(), 'data.0.service_id');

        if (!$serviceId) {
            Log::error('❌ Không lấy được service_id từ GHN', $availableServices->json());
            return redirect()->back()->with('error', 'GHN không trả về service_id hợp lệ.');
        }
        $data = [
            'from_name' => $shop->shop_name,
            'from_phone' => $shop->shop_phone,
            'from_address' => $shop->address,
            'from_ward_name' => optional($shop->ward)->name,
            'from_district_name' => optional($shop->district)->name,
            'from_province_name' => optional($shop->province)->name,
            'payment_type_id' => 1,
            'note' => 'Giao hàng cho khách',
            'required_note' => 'KHONGCHOXEMHANG',
            'to_name' => $order->address->full_name,
            'to_phone' => $order->address->phone,
            'to_address' => $order->address->address,
            'to_district_id' => $toDistrictId,
            'to_ward_code' => (string) $toWardCode,
            'weight' => $totalWeight ?: 100,
            'length' => $maxLength ?: 10,
            'width' => $maxWidth ?: 10,
            'height' => $totalHeight ?: 10,
            'service_id' => $serviceId,
            'items' => $order->items->map(function ($item) {
                $variant = $item->productVariant;
                $product = $variant?->product ?? $item->product;

                return [
                    'name' => $product->name ?? 'Không rõ',
                    'quantity' => $item->quantity,
                    'code' => $variant?->sku ?? $product->sku ?? 'UNKNOWN',
                    'image' => asset('storage/' . ($product->image ?? 'default.png')),
                    'weight' => $variant?->weight ?? $product?->weight ?? 100,
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
            ShippingOrder::create([
                'order_id' => $order->id,
                'shipping_partner' => 'ghn',
                'shipping_code' => $ghnOrderCode,
                'status' => 'ready_to_pick',
                'note' => 'Đơn hàng gửi GHN thành công',
                'request_payload' => json_encode($data),
                'response_payload' => json_encode(['order_code' => $ghnOrderCode]),
            ]);

            return redirect()->back()->with('success', 'Đã gửi đơn hàng sang GHN!');
        }

        return redirect()->back()->with('error', '❌ Gửi đơn hàng đến GHN thất bại.');
    }
    private function mapGhnStatus($status)
    {
        return [
            'ready_to_pick' => 'Mới tạo đơn hàng',
            'picking' => 'Nhân viên đang lấy hàng',
            'cancel' => 'Đã hủy đơn hàng',
            'money_collect_picking' => 'Đang thu tiền người gửi',
            'picked' => 'Nhân viên đã lấy hàng',
            'storing' => 'Hàng đang nằm ở kho',
            'transporting' => 'Đang luân chuyển hàng',
            'sorting' => 'Đang phân loại hàng hóa',
            'delivering' => 'Nhân viên đang giao cho người nhận',
            'money_collect_delivering' => 'Nhân viên đang thu tiền người nhận',
            'delivered' => 'Nhân viên đã giao hàng thành công',
            'delivery_fail' => 'Nhân viên giao hàng thất bại',
            'waiting_to_return' => 'Đang đợi trả hàng về cho người gửi',
            'return' => 'Trả hàng',
            'return_transporting' => 'Đang luân chuyển hàng trả',
            'return_sorting' => 'Đang phân loại hàng trả',
            'returning' => 'Nhân viên đang đi trả hàng',
            'return_fail' => 'Nhân viên trả hàng thất bại',
            'returned' => 'Nhân viên trả hàng thành công',
            'exception' => 'Đơn hàng ngoại lệ không nằm trong quy trình',
            'damage' => 'Hàng bị hư hỏng',
            'lost' => 'Hàng bị mất',
        ][$status] ?? $status; // fallback nếu không khớp trạng thái
    }
    public function exchangeOrder(Request $request, Order $order)
    {
        // Validate input
        $validated = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'variant_id.*' => 'nullable|exists:product_variants,id',
            'quantity.*' => 'required|integer|min:1',
        ]);

        // Tạo đơn hàng mới
        $newOrder = Order::create([
            'user_id' => $order->user_id,
            'order_type' => 'exchange',
            'original_order_id' => $order->id,
            'status' => 'pending',
            'total' => 0, // Sẽ tính lại sau
        ]);

        // Thêm sản phẩm mới vào đơn hàng mới
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $variantId = $request->input('variant_id.' . $index);
            $quantity = $request->input('quantity.' . $index);

            $variant = null;
            $variantValues = [];

            if ($variantId) {
                $variant = ProductVariant::findOrFail($variantId);
                $variantValues = $variant->options->pluck('value_id')->toArray();
            }

            OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'product_name' => $product->name,
                'sku' => $variant?->sku ?? $product->sku,
                'image_url' => $product->image,
                'variant_values' => json_encode($variantValues),
                'price' => $variant?->price ?? $product->sale_price,
                'quantity' => $quantity,
                'total_price' => ($variant?->price ?? $product->sale_price) * $quantity,
            ]);
        }

        // Cập nhật trạng thái đơn hàng cũ
        $order->update(['status' => 'exchanged']);

        // Gọi API GHN để tạo vận đơn mới
        $this->createGHNOrder($newOrder);

        return redirect()->route('orders.show', $newOrder->id)->with('success', 'Đơn hàng mới đã được tạo.');
    }

    // Phương thức tạo vận đơn GHN cho đơn mới
    private function createGHNOrder(Order $order)
    {
        $data = [
            'to_name' => $order->shipping_address->full_name,
            'to_phone' => $order->shipping_address->phone,
            'to_address' => $order->shipping_address->address,
            'to_ward_code' => $order->shipping_address->ward_code,
            'to_district_id' => $order->shipping_address->district_id,
            'cod_amount' => $order->total, // Nếu có thu COD
            'content' => 'Đơn đổi hàng - Đơn gốc #' . $order->original_order_id,
            'weight' => 500, // gram
            'length' => 20,
            'width' => 15,
            'height' => 10,
            'service_type_id' => 2, // Giao hàng tiết kiệm
        ];

        $response = Http::withToken(config('services.ghn.token'))
            ->post('https://online-gateway.ghn.vn/shiip/public-api/v1/shipping-order/create', $data);

        if ($response->successful()) {
            $order->shipping_info = $response->json();
            $order->save();
        } else {
            // Xử lý lỗi
            throw new \Exception('Lỗi tạo đơn GHN: ' . $response->body());
        }
    }
}
