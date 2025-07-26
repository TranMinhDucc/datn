<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $ghnService;

    public function __construct(GhnService $ghnService)
    {
        $this->ghnService = $ghnService;
    }
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->shippingAddresses;
        $defaultAddress = $addresses->firstWhere('is_default', 1);
        $paymentMethods = PaymentMethod::where('active', 1)->get();
        $provinces = Province::all();

        $shippingFee = ['success' => false, 'data' => ['total' => 0]];

        // Tính phí ship cho địa chỉ mặc định ngay khi tải trang
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
                    'weight' => 1000, // Giả sử trọng lượng mặc định
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
            $totalHeight += $height * $quantity;
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
    // ========== THANH TOÁN ONLINE HEHE =============
    public function initiatePayment(Request $request)
    {
        $paymentMethod = \App\Models\PaymentMethod::find($request->payment_method_id);
        if (!$paymentMethod) {
            return response()->json(['success' => false, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);
        }

        if ($paymentMethod->code === 'momo') {
            $payUrl = $this->initMomoPayment($request);
            if ($payUrl) {
                return response()->json(['url' => $payUrl]);
            } else {
                return response()->json(['success' => false, 'message' => 'Lỗi khởi tạo thanh toán MoMo.'], 500);
            }
        } elseif ($paymentMethod->code === 'vnpay') {
            $payUrl = $this->initVnpayPayment($request);
            if ($payUrl) {
                return response()->json(['url' => $payUrl]);
            } else {
                return response()->json(['success' => false, 'message' => 'Lỗi khởi tạo thanh toán VNPAY.'], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'Phương thức không hỗ trợ.'], 400);
    }

    private function initMomoPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
        $accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
        $secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
        $orderInfo = "Thanh toán đơn hàng tại website";
        $orderId = 'ORDER' . time() . rand(1000, 9999);
        $redirectUrl = route('client.checkout.payment-callback');
        $ipnUrl = route('client.checkout.payment-callback');
        $amount = (int) ($request->total_amount ?? $request->shipping_fee + $request->tax_amount + $request->discount_amount); // hoặc tính lại từ cart
        $requestId = time() . "";
        $requestType = "payWithATM";
        $extraData = "";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (!isset($jsonResult['payUrl'])) {
            return null;
        }

        session()->put("pending_order_$orderId", $request->all());

        return $jsonResult['payUrl'];
    }

    private function initVnpayPayment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('client.checkout.payment-callback');
        $vnp_TmnCode = env('VNPAY_TMNCODE', 'CW3MWMKN');
        $vnp_HashSecret = env('VNPAY_HASHSECRET', '2EQ9DCNFBR3H0GRQ4RCVHYTO1VZYXFLZ');
        $vnp_Locale = 'vn';
        $vnp_TxnRef = 'ORDER' . time() . rand(1000, 9999);
        $vnp_Amount = (int) ($request->total_amount ?? $request->shipping_fee + $request->tax_amount + $request->discount_amount) * 100;
        $vnp_IpAddr = request()->ip();
        $vnp_OrderInfo = "Thanh toán đơn hàng tại website";
        $vnp_OrderType = "billpayment";

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];

        ksort($inputData);
        $query = "";
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        session()->put("pending_order_$vnp_TxnRef", $request->all());

        return $vnp_Url;
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function paymentCallback(Request $request)
    {
        $orderId = $request->query('orderId') ?: $request->query('vnp_TxnRef');
        if (!$orderId) {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $orderData = session()->pull("pending_order_$orderId");
        if (!$orderData) {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }

        $isSuccess = false;
        if ($request->has('resultCode')) { // MoMo
            $isSuccess = $request->query('resultCode') == 0;
        } elseif ($request->has('vnp_TransactionStatus')) { // VNPAY
            $isSuccess = $request->query('vnp_TransactionStatus') == '00';
        }

        if ($isSuccess) {
            $orderRequest = new Request($orderData);
            $orderRequest->merge(['is_paid' => 1, 'payment_status' => 'paid']);
            $this->placeOrder($orderRequest);
            return redirect()->route('client.client.checkout.success');
        } else {
            return redirect()->route('client.home')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
        }
    }

    public function placeOrder(Request $request)
    {

        try {
            DB::beginTransaction(); // 🟢 BẮT ĐẦU TRANSACTION
            Log::info('📥 Dữ liệu nhận:', $request->all());

            if (!$request->payment_method_id || !$request->shipping_address_id) {
                return response()->json(['success' => false, 'message' => 'Thiếu thông tin bắt buộc.']);
            }

            $user = auth()->user();
            if (!$user)
                return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập.'], 401);

            $cartItems = $request->cartItems;
            $shippingAddressId = $request->shipping_address_id;
            $paymentMethodId = $request->payment_method_id;
            $couponId = $request->coupon_id;
            $shippingCouponId = $request->shipping_coupon_id;
            $discountAmount = floatval($request->discount_amount);
            $shippingFee = floatval($request->shipping_fee);
            $taxAmount = floatval($request->tax_amount);

            $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
            $totalAmount = max(0, $subtotal + $shippingFee - $discountAmount + $taxAmount);

            $paymentMethod = \App\Models\PaymentMethod::find($paymentMethodId);
            if (!$paymentMethod)
                return response()->json(['success' => false, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);

            $isPaid = 0;
            $paymentStatus = 'unpaid';
            if ($paymentMethod->code === 'wallet') {
                if ($user->balance < $totalAmount)
                    return response()->json(['success' => false, 'message' => 'Số dư ví không đủ để thanh toán.'], 400);
                $user->decrement('balance', $totalAmount);
                $isPaid = 1;
                $paymentStatus = 'paid';
            }

            if ($paymentMethod->code == 'momo' || $paymentMethod->code == 'vnpay') {

                $paymentStatus = 'paid';
            }


            if ($couponId) {
                $coupon = \App\Models\Coupon::find($couponId);
                if (!$coupon)
                    return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.'], 400);

                $userUsed = DB::table('coupon_user')->where('coupon_id', $couponId)->where('user_id', $user->id)->count();
                if ($coupon->per_user_limit > 0 && $userUsed >= $coupon->per_user_limit) {
                    return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này đủ số lần cho phép.'], 400);
                }
            }

            $order = Order::create([
                'order_code' => 'ORD' . now()->timestamp,
                'user_id' => $user->id,
                'address_id' => $shippingAddressId,
                'payment_method_id' => $paymentMethodId,
                'coupon_code' => $couponId ? optional($coupon)->code : null,
                'coupon_id' => $couponId,
                'shipping_coupon_id' => $shippingCouponId,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'shipping_fee' => $shippingFee,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'is_paid' => $isPaid,
                'payment_status' => $paymentStatus,
                'status' => 'pending',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            if (!$order || !$order->id) {
                Log::error('❌ Order::create() trả về null.');
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Không thể tạo đơn hàng.'], 500);
            }

            Log::info('✅ Đơn hàng đã tạo:', ['order_id' => $order->id]);

            $totalWeight = 0;
            $maxLength = 0;
            $maxWidth = 0;
            $totalHeight = 0;

            foreach ($cartItems as $item) {
                $variant = null;
                $product = null;

                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::where('id', $item['variant_id'])->lockForUpdate()->first(); // 🔒 LOCK VARIANT

                    if ($variant) {
                        Log::info('✅ Đã tìm thấy Variant:', $variant->toArray());
                        $product = $variant->product;
                    } else {
                        Log::warning('⚠️ Không tìm thấy Variant với ID: ' . $item['variant_id']);
                        $product = Product::find($item['id']);
                    }
                } else {
                    $product = Product::where('id', $item['id'])->lockForUpdate()->first(); // 🔒 LOCK PRODUCT
                    Log::info('ℹ️ Không có variant_id trong item, dùng sản phẩm gốc.');
                }

                if (!$product) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm.'], 400);
                }

                // ✅ KIỂM TRA VÀ TRỪ TỒN KHO
                $stock = $variant?->stock_quantity ?? $product->stock_quantity;
                if ($stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Sản phẩm "' . $product->name . '" không đủ tồn kho.'], 400);
                }

                if ($variant) {
                    $variant->decrement('quantity', $item['quantity']);
                } else {
                    $product->decrement('stock_quantity', $item['quantity']);
                }

                // ✅ THÔNG TIN ĐƠN HÀNG CHI TIẾT
                $weight = $variant?->weight ?? $product?->weight ?? 0;
                $length = $variant?->length ?? $product?->length ?? 0;
                $width = $variant?->width ?? $product?->width ?? 0;
                $height = $variant?->height ?? $product?->height ?? 0;

                $totalWeight += $weight * $item['quantity'];
                $maxLength = max($maxLength, $length);
                $maxWidth = max($maxWidth, $width);
                $totalHeight += $height * $item['quantity'];

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $item['name'],
                    'sku' => $item['sku'] ?? '',
                    'image_url' => $item['image'] ?? '',
                    'variant_values' => json_encode($item['attributes'] ?? []),
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'weight' => $weight,
                    'length' => $length,
                    'width' => $width,
                    'height' => $height,
                ]);
            }

            $order->update([
                'total_weight' => $totalWeight,
                'max_length' => $maxLength,
                'max_width' => $maxWidth,
                'total_height' => $totalHeight,
            ]);

            $now = now();

            if ($couponId) {
                DB::table('coupon_user')->insertOrIgnore([
                    'coupon_id' => $couponId,
                    'user_id' => $user->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                DB::table('coupons')->where('id', $couponId)->increment('used_count');
            }

            if ($shippingCouponId) {
                DB::table('coupon_user')->insertOrIgnore([
                    'coupon_id' => $shippingCouponId,
                    'user_id' => $user->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                DB::table('coupons')->where('id', $shippingCouponId)->increment('used_count');
            }

            session()->put('order_id', $order->id);
            DB::commit(); // ✅ KẾT THÚC TRANSACTION

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công!',
                'order_id' => $order->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack(); // ❌ LỖI THÌ ROLLBACK
            Log::error('❌ Lỗi đặt hàng: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống khi xử lý đơn hàng.', 'error' => $e->getMessage()], 500);
        }
    }


    public function success()
    {
        $orderId = session()->pull('order_id'); // Kéo ra 1 lần rồi xoá luôn

        if (!$orderId) {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $order = \App\Models\Order::with(['orderItems', 'address'])->find($orderId);

        if (!$order) {
            return redirect()->route('client.home')->with('error', 'Đơn hàng không tồn tại.');
        }

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
