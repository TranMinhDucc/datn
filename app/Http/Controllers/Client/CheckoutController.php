<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderSuccessMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PartnerLocationCode;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Province;
use App\Models\Setting;
use App\Models\ShippingFee;
use App\Models\User;
use App\Services\Shipping\GhnService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    protected $ghnService;
    protected $inventoryService;

    public function __construct(GhnService $ghnService, InventoryService $inventoryService)
    {
        $this->ghnService = $ghnService;
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $user = auth()->user();
        $addresses = $user->shippingAddresses;
        $defaultAddress = $addresses->firstWhere('is_default', 1);
        $paymentMethods = PaymentMethod::where('active', 1)->get();
        $provinces = Province::all();

        $shippingFee = ['success' => false, 'data' => ['total' => 0]];

        // Tính phí ship cho địa chỉ mặc định
        if ($defaultAddress && $defaultAddress->district_id && $defaultAddress->ward_id) {
            $districtCode = PartnerLocationCode::where([
                ['location_id', $defaultAddress->district_id],
                ['type', 'district'],
                ['partner_code', 'ghn']
            ])->value('partner_id');

            $wardCode = PartnerLocationCode::where([
                ['location_id', $defaultAddress->ward_id],
                ['type', 'ward'],
                ['partner_code', 'ghn']
            ])->value('partner_id');

            if ($districtCode && $wardCode) {
                $payload = [
                    'service_type_id' => 2,
                    'from_district_id' => config('services.ghn.from_district_id'),
                    'to_district_id' => (int) $districtCode,
                    'to_ward_code' => (string) $wardCode,
                    'weight' => 1000,
                    'length' => 10,
                    'width' => 15,
                    'height' => 10
                ];

                $shippingFee = $this->ghnService->calculateShippingFee($payload);
            }
        }

        return view('client.checkout.index', compact(
            'addresses',
            'defaultAddress',
            'paymentMethods',
            'shippingFee',
            'provinces'
        ));
    }

    // CheckoutController.php
    public function calculateShippingFee(Request $request)
    {
        Log::info('Bắt đầu tính phí vận chuyển', ['request' => $request->all()]);

        $addressId = $request->get('address_id');
        $cartItems = $request->get('cartItems', []);

        Log::debug('Danh sách sản phẩm trong giỏ:', $cartItems);

        // Kiểm tra địa chỉ
        $address = auth()->user()->shippingAddresses()->find($addressId);
        if (!$address) {
            Log::error('Không tìm thấy địa chỉ', ['address_id' => $addressId]);
            return response()->json(['success' => false, 'message' => 'Địa chỉ không tồn tại.']);
        }

        Log::debug('Thông tin địa chỉ:', $address->toArray());

        // Kiểm tra phí ship thủ công
        // 1. Ưu tiên kiểm tra theo province_id, district_id, ward_id
        $customShippingFee = \App\Models\ShippingFee::where('province_id', $address->province_id)
            ->where('district_id', $address->district_id)
            ->where('ward_id', $address->ward_id)
            ->whereNotNull('price')
            ->first();

        // 2. Nếu không có, kiểm tra theo province_id (áp dụng cho toàn tỉnh)
        if (!$customShippingFee) {
            $customShippingFee = \App\Models\ShippingFee::where('province_id', $address->province_id)
                ->whereNull('district_id')
                ->whereNull('ward_id')
                ->whereNotNull('price')
                ->first();
        }

        if ($customShippingFee) {
            Log::debug('Sử dụng phí ship thủ công', $customShippingFee->toArray());
            return response()->json([
                'success' => true,
                'data' => ['total' => $customShippingFee->price],
                'total' => $customShippingFee->price,
                'debug' => ['custom_shipping_fee' => $customShippingFee->price]
            ]);
        }

        // Nếu không có phí ship thủ công, tiếp tục tính phí qua GHN
        $districtCode = PartnerLocationCode::where([
            ['location_id', $address->district_id],
            ['type', 'district'],
            ['partner_code', 'ghn']
        ])->value('partner_id');

        $wardCode = PartnerLocationCode::where([
            ['location_id', $address->ward_id],
            ['type', 'ward'],
            ['partner_code', 'ghn']
        ])->value('partner_id');

        if (!$districtCode || !$wardCode) {
            Log::error('Không lấy được mã GHN', [
                'district_id' => $address->district_id,
                'ward_id' => $address->ward_id
            ]);
            return response()->json(['success' => false, 'message' => 'Không lấy được mã đối tác.']);
        }

        Log::debug('Mã GHN:', ['district' => $districtCode, 'ward' => $wardCode]);

        // Tính toán kích thước và trọng lượng
        $totalWeight = 0;
        $maxLength = 0;
        $maxWidth = 0;
        $totalHeight = 0;

        foreach ($cartItems as $index => $item) {
            Log::debug("Xử lý sản phẩm #$index", $item);

            $product = Product::find($item['id']);
            if (!$product) {
                Log::warning('Sản phẩm không tồn tại', ['product_id' => $item['id']]);
                continue;
            }

            $quantity = $item['quantity'] ?? 1;
            Log::debug("Số lượng: $quantity");

            // Xử lý biến thể
            if (!empty($item['variant_id'])) {
                Log::debug('Sản phẩm có biến thể', ['variant_id' => $item['variant_id']]);
                $variant = ProductVariant::find($item['variant_id']);

                if (!$variant) {
                    Log::warning('Không tìm thấy biến thể', ['variant_id' => $item['variant_id']]);
                }

                $weight = $variant->weight ?? $product->weight ?? 200;
                $length = $variant->length ?? $product->length ?? 10;
                $width = $variant->width ?? $product->width ?? 10;
                $height = $variant->height ?? $product->height ?? 5;
            } else {
                Log::debug('Sản phẩm không có biến thể');
                $weight = $product->weight ?? 200;
                $length = $product->length ?? 10;
                $width = $product->width ?? 10;
                $height = $product->height ?? 5;
            }

            Log::debug("Thông số sản phẩm #$index", [
                'weight' => $weight,
                'length' => $length,
                'width' => $width,
                'height' => $height
            ]);

            // Tính toán tổng
            $totalWeight += $weight * $quantity;
            $maxLength = max($maxLength, $length);
            $maxWidth = max($maxWidth, $width);
            $totalHeight = max($totalHeight, $height);
        }

        Log::debug('Tổng thông số đơn hàng', [
            'totalWeight' => $totalWeight,
            'maxLength' => $maxLength,
            'maxWidth' => $maxWidth,
            'totalHeight' => $totalHeight
        ]);

        // Đảm bảo giá trị tối thiểu
        $payload = [
            'service_type_id' => 2,
            'from_district_id' => config('services.ghn.from_district_id'),
            'to_district_id' => (int) $districtCode,
            'to_ward_code' => (string) $wardCode,
            'weight' => max($totalWeight, 200),
            'length' => max($maxLength, 10),
            'width' => max($maxWidth, 10),
            'height' => max($totalHeight, 5)
        ];

        Log::debug('Payload gửi đến GHN:', $payload);

        try {
            $shippingFee = $this->ghnService->calculateShippingFee($payload);
            Log::debug('Kết quả từ GHN:', $shippingFee);

            return response()->json([
                'success' => true,
                'data' => $shippingFee['data'] ?? [],
                'total' => $shippingFee['data']['total'] ?? 0,
                'debug' => ['payload' => $payload, 'cart_items' => $cartItems]
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tính phí vận chuyển', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính phí vận chuyển: ' . $e->getMessage(),
                'debug' => ['payload' => $payload, 'trace' => $e->getTraceAsString()]
            ]);
        }
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }


    public function initMomoPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = config('services.momo.partner_code');
        $accessKey   = config('services.momo.access_key');
        $secretKey   = config('services.momo.secret_key');

        $orderInfo = "Thanh toán đơn hàng tại website";
        $amount = (int) $request->total_amount;
        $orderId = $request->order_id;
        Log::debug('🚨 orderId phía request:', [
            'input_order_id' => $request->order_id,
            'used_order_id' => $orderId
        ]);
        $requestId = $orderId;
        $redirectUrl = route('client.checkout.payment-callback');
        $ipnUrl = route('client.checkout.payment-callback');
        $extraData = '';
        $requestType = 'captureWallet';

        $user = auth()->user();

        // ✅ Lưu đơn hàng tạm thời vào CACHE 30 phút
        $orderData = [
            'user_id' => $user ? $user->id : null,
            'shipping_address_id' => $request->shipping_address_id,
            'payment_method_id'   => $request->payment_method_id,
            'subtotal'            => $request->subtotal,
            'shipping_fee'        => $request->shipping_fee,
            'discount_amount'     => $request->discount_amount,
            'tax_amount'          => $request->tax_amount,
            'total_amount'        => $amount,
            'payment_method_code' => 'momo_qr',
            'coupon_id'           => $request->coupon_id,
            'shipping_coupon_id'  => $request->shipping_coupon_id,
            'cartItems'           => $request->cartItems ?? [],
        ];


        // ✅ CHỈ LƯU MẢNG THÔ
        Cache::store('file')->put("momo_pending_order_$orderId", $orderData, now()->addMinutes(30));

        Log::info("\u2705 Da luu cache voi key: momo_pending_order_$orderId", ['data' => $orderData]);
        Log::debug('\u2705 Xac nhan da luu cache', [
            'key' => "momo_pending_order_$orderId",
            'data' => Cache::get("momo_pending_order_$orderId")
        ]);

        // ✅ Tạo chữ ký
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $payload = [
            'partnerCode' => $partnerCode,
            'accessKey'   => $accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
            'lang'        => 'vi',
        ];

        Log::info('📤 Gửi request đến MoMo:', $payload);

        $response = Http::post($endpoint, $payload);
        Log::info('📥 Nhận response từ MoMo:', ['raw' => $response->body()]);

        $res = json_decode($response->body(), true);

        return isset($res['payUrl'])
            ? response()->json([
                'success'  => true,
                'payUrl'   => $res['payUrl'],
                'orderId'  => $orderId   // ✅ TRẢ VỀ orderId bạn đã lưu cache
            ])
            : response()->json([
                'success' => false,
                'message' => 'Không nhận được payUrl từ MoMo'
            ]);
    }




    public function handleMomoCallback(Request $request)
    {
        Log::info('📩 CALLBACK MoMo', [
            'method' => $request->method(),
            'orderId' => $request->orderId,
            'transId' => $request->transId,
            'resultCode' => $request->resultCode,
            'query' => $request->query(),
            'payload' => $request->all(),
        ]);

        $orderId = $request->orderId;
        // dd("🚨 orderId: $orderId");
        $cacheKey = "momo_pending_order_$orderId";

        $orderData = Cache::store('file')->get($cacheKey);

        if (!$orderData || !is_array($orderData) || !isset($orderData['total_amount'])) {
            Log::error('❌ Không tìm thấy dữ liệu đơn hàng hoặc thiếu total_amount.', [
                'key' => $cacheKey,
                'value_in_cache' => $orderData
            ]);
            return response()->json(['message' => 'Không tìm thấy thông tin thanh toán đơn hàng.'], 400);
        }

        Log::info('📦 Dữ liệu cache lấy ra:', [
            'key' => $cacheKey,
            'data' => $orderData,
        ]);

        if ($request->resultCode == 0) {
            // ✅ Thanh toán thành công → tạo đơn hàng
            try {
                $order = DB::transaction(function () use ($orderData, $request, $cacheKey) {
                    $order = Order::create([
                        'user_id'             => $orderData['user_id'],
                        'address_id'          => $orderData['shipping_address_id'],
                        'payment_method_id'   => $orderData['payment_method_id'] ?? 4,
                        // 'payment_method_code' => $orderData['payment_method_code'] ?? 'momo_qr',
                        'payment_method'      => 'momo',
                        'payment_reference'   => $request->transId,
                        'momo_trans_id'       => $request->transId,
                        'momo_order_id'       => $request->orderId,
                        'coupon_id'           => $orderData['coupon_id'],
                        'shipping_coupon_id'  => $orderData['shipping_coupon_id'],
                        'discount_amount'     => $orderData['discount_amount'],
                        'shipping_fee'        => $orderData['shipping_fee'],
                        'tax_amount'          => $orderData['tax_amount'],
                        'subtotal'            => $orderData['subtotal'],
                        'total_amount'        => $orderData['total_amount'],
                        'status'              => 'pending',
                        'payment_status'      => 'paid',
                        'is_paid'             => 1,
                        'paid_at'             => now(),
                        'order_code'          => 'ORD' . now()->timestamp,
                        'ip_address'          => $request->ip(),
                        'user_agent'          => $request->userAgent(),
                    ]);

                    foreach ($orderData['cartItems'] as $item) {
                        $order->orderItems()->create([
                            'order_id'           => $order->id,
                            'product_id'         => $item['id'],
                            'product_variant_id' => $item['variant_id'],
                            'product_name'       => $item['name'],
                            'price'              => $item['price'],
                            'quantity'           => $item['quantity'],
                            'variant_values'     => json_encode($item['attributes'] ?? []),
                            'sku'                => $item['sku'] ?? '',
                            'total_price'        => $item['price'] * $item['quantity'],
                            'image_url'          => $item['image'] ?? '',
                        ]);

                        // ✅ Trừ tồn kho
                        $variant = ProductVariant::find($item['variant_id']);
                        $product = Product::find($item['id']);

                        if ($variant) {
                            $variant->decrement('quantity', $item['quantity']);
                        } elseif ($product) {
                            $product->decrement('stock_quantity', $item['quantity']);
                        }
                    }

                    Cache::forget($cacheKey);

                    return $order;
                });


                session()->put('order_id', $order->id); // ✅ Thêm dòng này

                Mail::to(auth()->user()->email ?? $order->user->email)
                    ->later(now()->addSeconds(5), new OrderSuccessMail($order));
                return redirect()->route('client.checkout.success')->with('success', 'Thanh toán MoMo thành công!');
            } catch (\Throwable $e) {
                Log::error('❌ Lỗi tạo đơn hàng sau thanh toán MoMo: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->route('client.checkout.index')->with('error', 'Đã xảy ra lỗi khi xử lý đơn hàng.');
            }
        }

        // ❌ Thanh toán thất bại
        return redirect()->route('client.checkout.index')->with('error', 'Thanh toán thất bại!');
    }



    private function validateAndCalculateDiscount(?Coupon $coupon, User $user, $subtotal)
    {
        if (!$coupon || !$coupon->active) return 0;
        if (now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) return 0;

        // Tổng số lượt toàn hệ thống
        if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) return 0;

        // Giới hạn mỗi user
        if ($coupon->per_user_limit > 0) {
            $usedByUser = DB::table('coupon_user')
                ->where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->count();
            if ($usedByUser >= $coupon->per_user_limit) return 0;
        }

        if ($coupon->only_for_new_users && !$user->is_new_user) return 0;
        if ($subtotal < $coupon->min_order_amount) return 0;

        // Tính giảm giá
        if ($coupon->value_type === 'percentage') {
            $discount = $subtotal * ($coupon->discount_value / 100);
            return $coupon->max_discount_amount
                ? min($discount, $coupon->max_discount_amount)
                : $discount;
        }

        if ($coupon->value_type === 'fixed') {
            return $coupon->discount_value;
        }

        return 0;
    }



    public function placeOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập.'], 401);
            }

            $cartItems         = $request->cartItems;
            $shippingAddressId = $request->shipping_address_id;
            $paymentMethodId   = $request->payment_method_id;
            $couponId          = $request->coupon_id;
            $shippingCouponId  = $request->shipping_coupon_id;
            $shippingFee       = floatval($request->shipping_fee);
            $taxAmount         = floatval($request->tax_amount);

            $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);

            // ✅ Validate coupon và tính lại discount server-side
            $discountAmount = 0;
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                $discountAmount = $this->validateAndCalculateDiscount($coupon, $user, $subtotal);

                if ($discountAmount <= 0) {
                    return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ.'], 400);
                }
            }

            $totalAmount = max(0, $subtotal + $shippingFee - $discountAmount + $taxAmount);

            $paymentMethod = PaymentMethod::find($paymentMethodId);
            if (!$paymentMethod) {
                return response()->json(['success' => false, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);
            }

            $isPaid = 0;
            $paymentStatus = 'unpaid';

            if ($paymentMethod->code === 'wallet') {
                if ($user->balance < $totalAmount) {
                    return response()->json(['success' => false, 'message' => 'Số dư ví không đủ để thanh toán.'], 400);
                }
                $user->decrement('balance', $totalAmount);
                $isPaid = 1;
                $paymentStatus = 'paid';
            }

            // ✅ 1. Tạo đơn hàng trước
            $order = Order::create([
                'order_code'         => 'ORD' . now()->timestamp,
                'user_id'            => $user->id,
                'address_id'         => $shippingAddressId,
                'payment_method_id'  => $paymentMethodId,
                'coupon_id'          => $couponId,
                'shipping_coupon_id' => $shippingCouponId,
                'discount_amount'    => $discountAmount,
                'tax_amount'         => $taxAmount,
                'shipping_fee'       => $shippingFee,
                'subtotal'           => $subtotal,
                'total_amount'       => $totalAmount,
                'is_paid'            => $isPaid,
                'payment_status'     => $paymentStatus,
                'status'             => 'pending',
                'ip_address'         => request()->ip(),
                'user_agent'         => request()->userAgent(),
            ]);
            session()->put('order_id', $order->id);
            // ✅ 2. Thêm chi tiết đơn hàng + trừ kho
            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

                $order->orderItems()->create([
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name'       => $item['name'],
                    'price'              => $item['price'],
                    'quantity'           => $item['quantity'],
                    'total_price'        => $item['price'] * $item['quantity'],
                    'sku'                => $item['sku'] ?? '',
                    'image_url'          => $item['image'] ?? '',
                    'variant_values'     => json_encode($item['attributes'] ?? []),
                ]);

                if ($variant) {
                    $variant->decrement('quantity', $item['quantity']);
                } else {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            // ✅ 3. Lưu coupon_user SAU khi có order_id
            if ($couponId) {
                DB::table('coupon_user')->insert([
                    'coupon_id'  => $couponId,
                    'user_id'    => $user->id,
                    'order_id'   => $order->id,   // bây giờ chắc chắn có order_id
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('coupons')->where('id', $couponId)->increment('used_count');
            }

            if ($shippingCouponId) {
                DB::table('coupon_user')->insert([
                    'coupon_id'  => $shippingCouponId,
                    'user_id'    => $user->id,
                    'order_id'   => $order->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('coupons')->where('id', $shippingCouponId)->increment('used_count');
            }

            DB::commit();
            session()->put('order_id', $order->id);
            Mail::to($user->email)->queue(new OrderSuccessMail($order));

            return response()->json([
                'success'  => true,
                'message'  => 'Đặt hàng thành công!',
                'order_id' => $order->id
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('❌ Lỗi đặt hàng: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống khi xử lý đơn hàng.'], 500);
        }
    }



    public function success()
    {
        $orderId = session()->get('order_id'); // 👈 đổi từ pull() sang get()

        if (!$orderId) {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $order = Order::with(['orderItems', 'address'])->find($orderId);

        if (!$order) {
            return redirect()->route('client.home')->with('error', 'Đơn hàng không tồn tại.');
        }
        session()->flash('success', '🎉 Đặt hàng thành công!');

        return view('client.checkout.success', compact('order'))
            ->with('success', '🎉 Đặt hàng thành công!');
    }


    private function calculateDiscount($couponId, $userId, $cartSubtotal)
    {
        if (!$couponId) return 0;

        $coupon = Coupon::find($couponId);
        $user = User::find($userId);
        if (!$coupon || !$coupon->active) return 0;
        if (now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) return 0;
        if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) return 0;
        if ($coupon->per_user_limit > 0) {
            $usedByUser = DB::table('coupon_user')->where('coupon_id', $couponId)->where('user_id', $userId)->count();
            if ($usedByUser >= $coupon->per_user_limit) return 0;
        }
        if ($coupon->only_for_new_users && !$user->is_new_user) return 0;
        if ($coupon->eligible_user_roles) {
            $allowedRoles = json_decode($coupon->eligible_user_roles, true);
            if (!in_array($user->role, $allowedRoles)) return 0;
        }
        if ($cartSubtotal < $coupon->min_order_amount) return 0;

        if ($coupon->value_type === 'percentage') {
            $discount = $cartSubtotal * ($coupon->discount_value / 100);
            return $coupon->max_discount_amount ? min($discount, $coupon->max_discount_amount) : $discount;
        }

        if ($coupon->value_type === 'fixed') return $coupon->discount_value;
        return 0;
    }
}
