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
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\ShippingAddress;
use App\Models\ShippingLog;
use App\Models\ShippingMethod;
use App\Models\ShippingOrder;
use App\Models\ShopSetting;
use App\Models\User;
use App\Models\InventoryTransaction;
use App\Services\GhnService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Notifications\OrderStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    private function allStatuses(): array
    {
        return array_keys($this->statusLabels);
    }

    private function availableNext(string $current): array
    {
        return $this->allowedTransitions[$current] ?? [];
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingOrder']);

        // 🔍 Search chung
        if ($request->filled('search')) {
            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%$keyword%")
                    ->orWhereHas('user', function ($sub) use ($keyword) {
                        $sub->where('fullname', 'like', "%$keyword%");
                    })
                    ->orWhereHas('shippingOrder', function ($sub) use ($keyword) {
                        $sub->where('shipping_code', 'like', "%$keyword%");
                    });
            });
        }

        // ✅ giữ lại các filter khác nếu cần
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        $orders = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->all()); // giữ lại search khi phân trang

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
            'shipping_fee' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:2000',
            'note_shipper' => 'nullable|string|max:255',
            'required_note_shipper' => 'nullable|string|in:KHONGCHOXEMHANG,CHOXEMHANGKHONGTHU,CHOTHUHANG',
        ]);

        DB::beginTransaction();
        try {
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_code' => 'ORD' . strtoupper(uniqid()),
                'address_id' => $validated['address_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'shipping_method' => $validated['shipping_method'],

                'subtotal' => 0,
                'tax_amount' => $request->input('tax_amount', 0),
                'shipping_fee' => $request->input('shipping_fee', 0),
                'discount_amount' => $request->input('discount_amount', 0),

                'total_amount' => 0,
                'status' => 'pending',
                'is_paid' => false,
                'payment_status' => 'unpaid',

                'note' => $request->input('note'),
                'note_shipper' => $request->input('note_shipper'),
                'required_note_shipper' => $request->input('required_note_shipper', 'KHONGCHOXEMHANG'),
            ]);

            $subtotal = 0;

            // Thêm các mục đơn hàng
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $variant = $item['variant_id'] ? ProductVariant::findOrFail($item['variant_id']) : null;

                // Kiểm tra tồn kho thực tế + giữ chỗ
                $availableStock = $variant
                    ? $variant->quantity - $variant->reserved_quantity
                    : $product->quantity - $product->reserved_quantity;

                if ($availableStock < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ tồn kho khả dụng.");
                }

                $price = $variant ? $variant->price : $product->sale_price;
                $totalPrice = $price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'sku' => $variant?->sku ?? $product->sku,
                    'image_url' => $product->image
                        ? Storage::url($product->image)
                        : null,
                    'variant_values' => $variant ? json_encode($variant->options->pluck('value_id')->toArray()) : null,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'total_price' => $totalPrice,
                ]);

                $subtotal += $totalPrice;

                // ✅ Giữ chỗ (tăng reserved_quantity, không giảm quantity)
                if ($variant) {
                    $variant->increment('reserved_quantity', $item['quantity']);
                } else {
                    $product->increment('reserved_quantity', $item['quantity']);
                }
            }

            // Cập nhật tổng tiền đơn hàng
            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal
                    + $order->shipping_fee
                    + $order->tax_amount
                    - $order->discount_amount,
            ]);

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công (giữ chỗ kho).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi tạo đơn hàng: ' . $e->getMessage());
        }
    }



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
            'returnRequests.items.orderItem.product',
            'returnRequests.items.orderItem.productVariant',
        ])->findOrFail($id);

         $returnRequests = $order->returnRequests()
        ->with(['items', 'exchangeOrder'])
        ->orderByDesc('id')
        ->get();

        $products = Product::where('is_active', 1)->with('variants')->get();

        // chỉ các trạng thái hợp lệ kế tiếp
        $availableStatuses = $this->availableNext($order->status);

        // Lấy danh sách yêu cầu đổi hàng có đơn hàng đổi mới
        $exchangesByRR = $order->returnRequests()
            ->whereNotNull('exchange_order_id')
            ->with(['exchangeOrder' => function ($query) {
                $query->select('id', 'order_code', 'status', 'created_at');
            }])
            ->get(['id', 'exchange_order_id']);

        // Danh sách đơn hàng đổi mới (lấy từ exchange_order_id trong return_requests)
        $exchangeOrders = Order::whereIn(
            'id',
            $exchangesByRR->pluck('exchange_order_id')->toArray()
        )->get(['id', 'order_code', 'status', 'created_at']);
        // ====== TÍNH SẴN THÔNG TIN THANH TOÁN TRUYỀN RA VIEW ======
        $pmCodeRaw = $order->payment_method ?? ($order->paymentMethod->code ?? '');
        $pmCode    = strtolower(trim($pmCodeRaw));
        $payment = [
            'code'  => $pmCode,
            'label' => null,
            'icon'  => null, // class FA nếu không dùng ảnh
            'img'   => null, // đường dẫn ảnh nếu có
            'paid'  => ($order->payment_status === 'paid') || (int)($order->is_paid ?? 0) === 1,
        ];
        if (str_contains($pmCode, 'momo')) {
            $payment['label'] = 'MoMo';
            $payment['img']   = asset('assets/media/svg/card-logos/momo.svg'); // đặt file nếu bạn có
        } elseif ($pmCode === 'cod' || str_contains($pmCode, 'cod')) {
            $payment['label'] = 'COD';
            $payment['icon']  = 'fa-solid fa-hand-holding-dollar';
        } else {
            $payment['label'] = $order->paymentMethod->name
                ?? ($pmCode ? ucfirst($pmCode) : 'Không rõ');
        }
        $formatAddress = function ($a) {
            if (!$a) return '';
            $parts = [
                trim($a->address ?? ''),
                $a->ward?->name,
                $a->district?->name,
                $a->province?->name,
                $a->country ?: 'Việt Nam',
            ];
            return implode(', ', array_filter($parts));
        };

        $shipToFull = $formatAddress($order->shippingAddress);

        $userAddresses = collect();
        if ($order->user_id) {
            $userAddresses = ShippingAddress::with(['province', 'district', 'ward'])
                ->where('user_id', $order->user_id)
                ->orderByDesc('is_default')
                ->get();

            // gắn sẵn chuỗi full_address để Blade chỉ hiển thị
            $userAddresses->each(function ($a) use ($formatAddress) {
                $a->full_address = $formatAddress($a);
            });
        }
        $onlineBill = null;
        if (str_contains($pmCode, 'momo')) {
            // tuỳ DB của bạn đang dùng cột nào:
            $onlineBill = $order->payment_reference
                ?? null;
        }
        return view('admin.orders.show', [
            'order'             => $order,
            'returnRequests'    => $returnRequests,
            'products'          => $products,
            'statusLabels'      => $this->statusLabels,
            'availableStatuses' => $availableStatuses,
            'exchangesByRR'     => $exchangesByRR,
            'exchangeOrders'    => $exchangeOrders,
            'payment'           => $payment,
            'shipToFull'     => $shipToFull,
            'userAddresses'  => $userAddresses,
            'onlineBill'     => $onlineBill,
        ]);
    }




    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys($this->statusLabels))],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $to = $request->input('status'); // <-- lấy string thuần
        $allowed = $this->allowedTransitions[$order->status] ?? [];

        if (!in_array($to, $allowed, true)) {
            return back()->withErrors([
                'status' => "Không thể chuyển từ {$order->status} → {$to}. Cho phép: " . implode(', ', $allowed)
            ]);
        }

        $old = $order->status;

        if ($to === 'delivered' && !$order->delivered_at)  $order->delivered_at  = now();
        if ($to === 'completed' && !$order->completed_at)  $order->completed_at  = now();
        if ($to === 'cancelled' && !$order->cancelled_at)  $order->cancelled_at  = now();

        $order->status = $to;
        $order->save();
        $shippingOrder = ShippingOrder::where('order_id', $order->id)->latest()->first();
        ShippingLog::create([
            'order_id' => $order->id,
            'provider' => $shippingOrder->shipping_partner ?? 'manual',
            'tracking_code' => $shippingOrder->shipping_code ?? null,
            'status' => $to,
            'description' => $this->getManualStatusDescription($to), // ← dùng hàm mô tả theo status
            'created_at' => now(),
            'updated_at' => now(),
            'received_at' => now(),
        ]);
        $this->markOriginExchangedIfNeeded($order, $to);
        $order->user->notify(new OrderStatusNotification(
            $order->id,
            $order->status,
            $order,
            $request->input('reason')
        ));

        return back()->with('success', "Đã chuyển {$old} → {$to}.");
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
        if ($currentStatus !== 'waiting_to_return') {
            $viStatus = $this->mapGhnStatus($currentStatus); // ví dụ: 'Giao hàng thất bại'

            return back()->with('error', "⚠️ Đơn hàng đang ở trạng thái \"$viStatus\". Bạn cần chờ GHN chuyển sang trạng thái \"Đang đợi trả hàng\" (waiting_to_return) mới có thể giao lại.");
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

    public function createShippingOrder(array $data)
    {
        $token  = Setting::getValue('ghn_token');
        $shopId = Setting::getValue('ghn_shop_id');

        Log::info('GHN Token + ShopID', [
            'token'   => $token,
            'shop_id' => $shopId,
        ]);

        if (!$token || !$shopId) {
            Log::error('❌ GHN Token hoặc ShopID chưa được cấu hình trong bảng settings');
            return false;
        }

        $response = Http::withHeaders([
            'Token' => $token,
            'Content-Type' => 'application/json',
            'ShopId' => $shopId,
        ])->post(
            'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create',
            $data
        );

        Log::info('GHN Response Raw', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if ($response->successful() && isset($response['data']['order_code'])) {
            return $response['data']['order_code'];
        }

        Log::error('❌ GHN Order Error', [
            'request' => $data,
            'response_status' => $response->status(),
            'response_body'   => $response->body(),
        ]);

        return false;
    }

    public function confirmGHN($id, Request $request, GhnService $service)
    {
        $order = Order::with([
            'items.productVariant.product',
            'user',
            'address',
            'adjustments',
            'payments'
        ])->findOrFail($id);

        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', '❌ Đơn hàng này đã bị huỷ, không thể thao tác.');
        }
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Đơn hàng không thể gửi đi do trạng thái không hợp lệ.');
        }

        // ======= TÍNH SỐ DƯ (để gửi cod_amount) =======
        $gross = (float)($order->subtotal ?? 0)
            + (float)($order->tax_amount ?? 0)
            + (float)($order->shipping_fee ?? 0)
            - (float)($order->discount_amount ?? 0);

        // Điều chỉnh: charge = cộng, discount = trừ
        $adjTotal = (float)$order->adjustments->sum(function ($a) {
            return $a->type === 'charge' ? $a->amount : -$a->amount;
        });

        $net         = $gross + $adjTotal;
        $paidIn      = (float)$order->payments->where('kind', 'payment')->sum('amount');
        $refundedOut = (float)$order->payments->where('kind', 'refund')->sum('amount');

        // dương = KH còn thiếu, âm/0 = không thu COD
        $balance   = $net - $paidIn + $refundedOut;
        $codAmount = max(0, (int) round($balance)); // GHN cần số nguyên không âm

        Log::info('GHN COD breakdown', [
            'gross'        => $gross,
            'adjustments'  => $adjTotal,
            'net'          => $net,
            'paid_in'      => $paidIn,
            'refunded_out' => $refundedOut,
            'balance'      => $balance,
            'cod_amount'   => $codAmount,
        ]);

        // Ai trả phí ship: 1=Shop, 2=Người nhận
        $paymentTypeId = 1;

        // ======= TÍNH KHỐI LƯỢNG/KÍCH THƯỚC GỬI GHN =======
        $totalWeight = 0;
        $maxLength   = 0;
        $maxWidth    = 0;
        $totalHeight = 0;

        foreach ($order->items as $item) {
            $variant = $item->productVariant;
            $product = $variant?->product ?? $item->product;

            if (!$variant && !$product) {
                Log::error("❌ Không tìm thấy biến thể và sản phẩm cho OrderItem ID: {$item->id}, Order ID: {$order->id}");
                continue;
            }

            $weight = $variant?->weight ?? $product?->weight ?? 100;
            $length = $variant?->length ?? $product?->length ?? 10;
            $width  = $variant?->width  ?? $product?->width  ?? 10;
            $height = $variant?->height ?? $product?->height ?? 10;

            $totalWeight += $weight * $item->quantity;
            if ($length > $maxLength) $maxLength = $length;
            if ($width  > $maxWidth)  $maxWidth  = $width;
            $totalHeight += $height * $item->quantity;
        }

        // ======= MAP ĐỊA CHỈ GHN =======
        $toDistrictId = PartnerLocationCode::where([
            'type'         => 'district',
            'location_id'  => $order->address->district_id,
            'partner_code' => 'ghn'
        ])->value('partner_id');

        $toWardCode = PartnerLocationCode::where([
            'type'         => 'ward',
            'location_id'  => $order->address->ward_id,
            'partner_code' => 'ghn'
        ])->value('partner_id');

        Log::info('ĐỊA CHỈ GHN', [
            'district_id nội bộ' => $order->address->district_id,
            'ward_id nội bộ'     => $order->address->ward_id,
            'mapped to_district' => $toDistrictId,
            'mapped to_ward'     => $toWardCode,
        ]);

        $shop = ShopSetting::with(['province', 'district', 'ward'])->first();

        $availableServices = Http::withHeaders([
            'Token'       => config('services.ghn.token'),
            'Content-Type' => 'application/json',
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
            'shop_id'       => (int) config('services.ghn.shop_id'),
            'from_district' => $shop->district->ghn_district_id ?? 3440,
            'to_district'   => (int) $toDistrictId,
        ]);

        $serviceId = data_get($availableServices->json(), 'data.0.service_id');
        if (!$serviceId) {
            Log::error('❌ Không lấy được service_id từ GHN', $availableServices->json());
            return redirect()->back()->with('error', 'GHN không trả về service_id hợp lệ.');
        }

        // ======= PAYLOAD GHN =======
        $data = [
            'from_name'          => $shop->shop_name,
            'from_phone'         => $shop->shop_phone,
            'from_address'       => $shop->address,
            'from_ward_name'     => optional($shop->ward)->name,
            'from_district_name' => optional($shop->district)->name,
            'from_province_name' => optional($shop->province)->name,

            'payment_type_id' => $paymentTypeId,
            'note'            => $order->note_shipper ?? 'Giao hàng cho khách',
            'required_note'   => $order->required_note_shipper ?? 'KHONGCHOXEMHANG',

            'to_name'        => $order->address->full_name,
            'to_phone'       => $order->address->phone,
            'to_address'     => $order->address->address,
            'to_district_id' => $toDistrictId,
            'to_ward_code'   => (string) $toWardCode,

            'weight' => $totalWeight ?: 100,
            'length' => $maxLength  ?: 10,
            'width'  => $maxWidth   ?: 10,
            'height' => $totalHeight ?: 10,

            'service_id'  => $serviceId,

            // ⬇️ Quan trọng: số tiền GHN cần thu
            'cod_amount'  => $codAmount,
            'content'     => $codAmount > 0 ? "Thu COD {$codAmount}đ" : 'Hàng đã thanh toán/không thu COD',

            'items' => $order->items->map(function ($item) {
                $variant = $item->productVariant;
                $product = $variant?->product ?? $item->product;
                return [
                    'name'     => $product->name ?? 'Không rõ',
                    'quantity' => $item->quantity,
                    'code'     => $variant?->sku ?? $product->sku ?? 'UNKNOWN',
                    'image'    => asset('storage/' . ($product->image ?? 'default.png')),
                    'weight'   => $variant?->weight ?? $product?->weight ?? 100,
                ];
            })->toArray(),
        ];

        Log::info('GHN Request', $data);

        $ghnOrderCode = $service->createShippingOrder($data);

        if ($ghnOrderCode) {
            $order->update([
                'status'         => 'confirmed',
                'ghn_order_code' => $ghnOrderCode
            ]);

            ShippingOrder::create([
                'order_id'         => $order->id,
                'shipping_partner' => 'ghn',
                'shipping_code'    => $ghnOrderCode,
                'status'           => 'ready_to_pick',
                'note'             => $order->note_shipper ?? 'Giao hàng cho khách',
                'request_payload'  => json_encode($data),
                'response_payload' => json_encode(['order_code' => $ghnOrderCode]),
            ]);
            // ======= XUẤT KHO SAU KHI XÁC NHẬN =======
            foreach ($order->items as $item) {
                $variant = $item->productVariant;
                if ($variant) {
                    $beforeQty = $variant->quantity;
                    $afterQty  = max(0, $beforeQty - $item->quantity);

                    // Giảm tồn kho
                    $variant->update(['quantity' => $afterQty]);

                    // Ghi lịch sử xuất kho
                    InventoryTransaction::create([
                        'product_id'         => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'type'               => 'export',
                        'quantity'           => $item->quantity,
                        'before_quantity'    => $beforeQty,
                        'after_quantity'     => $afterQty,
                        'note'               => "Xuất kho khi gửi GHN - Order #{$order->order_code} - ID: $order->id",
                        'created_by'         => auth()->id(),
                    ]);
                }
            }
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


    private function markOriginExchangedIfNeeded(Order $order, string $to): void
    {
        // Chỉ xử lý khi đây là ĐƠN ĐỔI và trạng thái mới là delivered/completed
        if (!in_array($to, ['delivered', 'completed'], true)) return;
        if (!($order->is_exchange || $order->exchange_of_return_request_id)) return;

        $rrId = $order->exchange_of_return_request_id;
        if (!$rrId) return;

        $rr = ReturnRequest::with('order')->find($rrId);
        $origin = $rr?->order;
        if (!$origin) return;

        // Chỉ set khi đơn gốc đang ở exchange_requested (đã được set lúc tạo đơn đổi)
        if ($origin->status === 'exchange_requested') {
            $origin->status = 'exchanged';
            $origin->save();

            ShippingLog::create([
                'order_id'     => $origin->id,
                'provider'     => 'manual',
                'tracking_code' => null,
                'status'       => 'exchanged',
                'description'  => "Đơn đổi #{$order->order_code} đã {$to}. Đánh dấu đơn gốc là exchanged.",
                'created_at'   => now(),
                'updated_at'   => now(),
                'received_at'  => now(),
            ]);
        }
    }
    private array $statusLabels = [
        'pending'                     => '🕐 Chờ xác nhận',
        'confirmed'                   => '✅ Đã xác nhận',
        'processing'                  => '📦 Đang chuẩn bị hàng',
        'ready_for_dispatch'          => '📮 Chờ bàn giao vận chuyển',
        'shipping'                    => '🚚 Đang giao',
        'delivery_failed'             => '⚠️ Giao thất bại – chờ xử lý',
        'delivered'                   => '📬 Đã giao',
        'completed'                   => '🎉 Hoàn tất',
        'cancelled'                   => '❌ Đã hủy',

        // Return
        'return_requested'            => '↩️ Yêu cầu trả hàng',
        'returning'                   => '📦 Đang trả hàng về',
        'returned'                    => '✅ Đã nhận hàng trả',

        // Exchange
        'exchange_requested'          => '🔁 Yêu cầu đổi hàng',
        'exchange_in_progress'        => '🔄 Đang xử lý đổi hàng',
        'exchanged'                   => '✅ Đã đổi xong',

        // Refund
        'refund_processing'           => '💳 Đang hoàn tiền',
        'refunded'                    => '✅ Đã hoàn tiền',

        // Trạng thái kết hợp
        'exchange_and_refund_processing' => '🔄💳 Đang đổi & hoàn tiền',
        'exchanged_and_refunded'         => '✅ Đã đổi & hoàn tất hoàn tiền',

        // Final
        'closed'                     => '🔒 Đã đóng đơn',
    ];


    // 2) Ma trận chuyển trạng thái (tối thiểu, bạn có thể nới thêm)
    private array $allowedTransitions = [
        'pending'            => ['confirmed', 'cancelled'],
        'confirmed'          => ['processing', 'cancelled'],
        'processing'         => ['ready_for_dispatch', 'shipping', 'cancelled'],
        'ready_for_dispatch' => ['shipping'],
        'shipping'           => ['delivered', 'delivery_failed'],
        'delivery_failed'    => ['shipping', 'cancelled'],
        'delivered'          => ['completed', 'return_requested', 'exchange_requested'],
        'completed'          => ['return_requested', 'exchange_requested'], // cho phép hậu mãi sau hoàn tất
        'cancelled'          => [],

        // after-sale
        'return_requested'   => ['returning', 'refund_processing'],
        'returning'          => ['returned'],
        'returned'           => ['refund_processing'],    // sau khi nhận hàng trả, mới hoàn tiền
        'refund_processing'  => ['refunded'],
        'refunded'           => [],

        'exchange_requested' => ['exchanged'],
        'exchanged'          => [],
    ];
    private function getManualStatusDescription(string $status): string
    {
        return [
            'pending'            => 'Đơn hàng đang chờ xác nhận.',
            'confirmed'          => 'Đơn hàng đã được xác nhận.',
            'processing'         => 'Đơn hàng đang được chuẩn bị.',
            'ready_for_dispatch' => 'Đơn hàng đã sẵn sàng bàn giao cho đơn vị vận chuyển.',
            'shipping'           => 'Đơn hàng đang được giao cho khách.',
            'delivery_failed'    => 'Đơn hàng giao thất bại – đang chờ xử lý.',
            'delivered'          => 'Đơn hàng đã được giao thành công.',
            'completed'          => 'Đơn hàng đã hoàn tất.',
            'cancelled'          => 'Đơn hàng bị huỷ bởi admin.',
            'return_requested'   => 'Khách hàng yêu cầu trả hàng.',
            'returning'          => 'Đơn hàng đang được trả về.',
            'returned'           => 'Đã nhận được hàng trả từ khách hàng.',
            'refund_processing'  => 'Đơn hàng đang được xử lý hoàn tiền.',
            'refunded'           => 'Đơn hàng đã được hoàn tiền.',
            'exchange_requested' => 'Khách hàng yêu cầu đổi hàng.',
            'exchanged'          => 'Đã hoàn tất việc đổi hàng.',
        ][$status] ?? 'Cập nhật trạng thái thủ công.';
    }
    public function updateGhnNote(Request $request, $id)
    {
        $request->validate([
            'note_shipper' => 'nullable|string|max:255',
            'required_note_shipper' => 'required|string|in:KHONGCHOXEMHANG,CHOXEMHANGKHONGTHU,CHOTHUHANG',
        ]);

        $order = Order::findOrFail($id);

        $shippingOrder = ShippingOrder::where('order_id', $order->id)
            ->where('shipping_partner', 'ghn')
            ->latest()
            ->first();

        if (!$shippingOrder || !$shippingOrder->shipping_code) {
            return back()->with('error', '❌ Không tìm thấy mã vận đơn GHN cho đơn hàng này.');
        }

        // ✅ Danh sách trạng thái cho phép update
        $updatableStatuses = [
            'pending',
            'confirmed',
            'processing',
            'ready_for_dispatch',
            'shipping',
            'delivery_failed',
        ];

        if (!in_array($order->status, $updatableStatuses, true)) {
            return back()->with('error', '❌ Đơn hàng đã ở trạng thái "' . $order->status . '" nên không thể chỉnh sửa ghi chú nữa.');
        }

        $payload = [
            'order_code'    => $shippingOrder->shipping_code,
            'note'          => $request->note_shipper ?? $order->note_shipper,
            'required_note' => $request->required_note_shipper,
        ];

        $response = Http::withHeaders([
            'Token' => config('services.ghn.token'),
            'Content-Type' => 'application/json',
            'ShopId' => config('services.ghn.shop_id'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/update', $payload);

        if ($response->failed()) {
            return back()->with('error', '❌ GHN trả lỗi: ' . $response->body());
        }

        $order->update([
            'note_shipper'          => $payload['note'],
            'required_note_shipper' => $payload['required_note'],
        ]);

        $shippingOrder->update([
            'last_note_update' => now(),
            'note_payload'     => json_encode($payload),
        ]);

        return back()->with('success', '✅ Đã cập nhật ghi chú cho GHN!');
    }

    public function printShippingLabel($id)
    {
        $order = Order::findOrFail($id);

        $shippingOrder = \App\Models\ShippingOrder::where('order_id', $order->id)
            ->where('shipping_partner', 'ghn')
            ->latest()
            ->first();

        if (!$shippingOrder || !$shippingOrder->shipping_code) {
            return back()->with('error', '❌ Không tìm thấy mã GHN cho đơn này.');
        }

        // Gọi API GHN để lấy token
        $response = Http::withHeaders([
            'Token' => config('services.ghn.token'),
            'Content-Type' => 'application/json',
            'ShopId' => config('services.ghn.shop_id'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/a5/gen-token', [
            'order_codes' => [$shippingOrder->shipping_code],
        ]);

        if ($response->failed() || !isset($response['data']['token'])) {
            return back()->with('error', '❌ GHN không trả về token in vận đơn. ' . $response->body());
        }

        $token = $response['data']['token'];

        // Redirect sang link in PDF của GHN
        return redirect()->away("https://dev-online-gateway.ghn.vn/a5/public-api/printA5?token={$token}");
    }
    public function cancelExchangeOrder($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::with('items')->findOrFail($id);

            if (!$order->exchange_of_return_request_id || !$order->is_exchange) {
                return back()->with('error', 'Đơn này không phải đơn đổi.');
            }

            // Cập nhật trạng thái đơn đổi
            $order->status = 'cancelled';
            $order->cancelled_at = now();
            $order->cancel_reason_by_admin = $request->input('cancel_reason') ?? 'Admin huỷ đơn đổi';
            $order->save();

            // Rollback về ReturnRequest
            $rr = ReturnRequest::find($order->exchange_of_return_request_id);
            if ($rr) {
                $rr->exchange_order_id = null;   // xoá liên kết để có thể tạo lại
                $rr->status = 'approved';        // quay lại trạng thái có thể xử lý tiếp
                $rr->save();
            }

            // Rollback tồn kho (sản phẩm đã xuất ra để tạo đơn đổi → trả lại)
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)
                        ->increment('quantity', $item->quantity);
                } else {
                    Product::where('id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                }
            }

            DB::commit();
            return back()->with('success', 'Huỷ đơn đổi thành công, yêu cầu RMA đã được mở lại.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi huỷ đơn đổi: ' . $e->getMessage());
        }
    }
}
