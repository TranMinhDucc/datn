<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\ShippingFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->shippingAddresses;
        $defaultAddress = $addresses->firstWhere('is_default', 1);
        $paymentMethods = \App\Models\PaymentMethod::where('active', 1)->get();

        $shippingFee = 0;

        if ($defaultAddress) {
            $shippingFee = ShippingFee::where('province_id', $defaultAddress->province_id)
                ->where('district_id', $defaultAddress->district_id)
                ->where('ward_id', $defaultAddress->ward_id)
                ->value('price');

            if (is_null($shippingFee)) {
                $shippingFee = ShippingFee::where('province_id', $defaultAddress->province_id)
                    ->where('district_id', $defaultAddress->district_id)
                    ->whereNull('ward_id')
                    ->value('price');
            }

            if (is_null($shippingFee)) {
                $shippingFee = ShippingFee::where('province_id', $defaultAddress->province_id)
                    ->whereNull('district_id')
                    ->whereNull('ward_id')
                    ->value('price');
            }

            $shippingFee = $shippingFee ?? 0;
        }

        return view('client.checkout.index', compact('addresses', 'defaultAddress', 'paymentMethods', 'shippingFee'));
    }

    public function placeOrder(Request $request)
    {
        try {
            Log::info('📥 Dữ liệu nhận:', $request->all());

            if (!$request->payment_method_id || !$request->shipping_address_id) {
                return response()->json(['success' => false, 'message' => 'Thiếu thông tin bắt buộc.']);
            }

            $user = auth()->user();
            if (!$user) return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập.'], 401);

            $cartItems         = $request->cartItems;
            $shippingAddressId = $request->shipping_address_id;
            $paymentMethodId   = $request->payment_method_id;
            $couponId          = $request->coupon_id;
            $shippingCouponId  = $request->shipping_coupon_id;
            $discountAmount    = floatval($request->discount_amount);
            $shippingFee       = floatval($request->shipping_fee);

            $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
            $totalAmount = max(0, $subtotal + $shippingFee - $discountAmount);

            $paymentMethod = \App\Models\PaymentMethod::find($paymentMethodId);
            if (!$paymentMethod) return response()->json(['success' => false, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);

            $isPaid = 0;
            $paymentStatus = 'unpaid';
            if ($paymentMethod->code === 'wallet') {
                if ($user->balance < $totalAmount) return response()->json(['success' => false, 'message' => 'Số dư ví không đủ để thanh toán.'], 400);
                $user->decrement('balance', $totalAmount);
                $isPaid = 1;
                $paymentStatus = 'paid';
            }

            if ($couponId) {
                $coupon = \App\Models\Coupon::find($couponId);
                if (!$coupon) return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.'], 400);

                $userUsed = DB::table('coupon_user')->where('coupon_id', $couponId)->where('user_id', $user->id)->count();
                if ($coupon->per_user_limit > 0 && $userUsed >= $coupon->per_user_limit) {
                    return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này đủ số lần cho phép.'], 400);
                }
            }

            $order = Order::create([
                'order_code'         => 'ORD' . now()->timestamp,
                'user_id'            => $user->id,
                'address_id'         => $shippingAddressId,
                'payment_method_id'  => $paymentMethodId,
                'coupon_code'        => $couponId ? optional($coupon)->code : null,
                'coupon_id'          => $couponId,
                'shipping_coupon_id' => $shippingCouponId,
                'discount_amount'    => $discountAmount,
                'shipping_fee'       => $shippingFee,
                'subtotal'           => $subtotal,
                'total_amount'       => $totalAmount,
                'is_paid'            => $isPaid,
                'payment_status'     => $paymentStatus,
                'status'             => 'pending',
                'ip_address'         => request()->ip(),
                'user_agent'         => request()->userAgent(),
            ]);

            if (!$order || !$order->id) {
                Log::error('❌ Order::create() trả về null.');
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
                    $variant = ProductVariant::find($item['variant_id']);

                    if ($variant) {
                        Log::info('✅ Đã tìm thấy Variant:', $variant->toArray());
                        $product = $variant->product;
                    } else {
                        Log::warning('⚠️ Không tìm thấy Variant với ID: ' . $item['variant_id']);
                        $product = Product::find($item['id']);
                    }
                } else {
                    Log::info('ℹ️ Không có variant_id trong item, dùng sản phẩm gốc.');
                    $variant = null;
                    $product = Product::find($item['id']);
                }



                if (!$product) {
                    $product = Product::find($item['id']);
                }

                $weight = $variant?->weight ?? $product?->weight ?? 0;
                $length = $variant?->length ?? $product?->length ?? 0;
                $width  = $variant?->width  ?? $product?->width  ?? 0;
                $height = $variant?->height ?? $product?->height ?? 0;

                $totalWeight += $weight * $item['quantity'];
                $maxLength = max($maxLength, $length);
                $maxWidth  = max($maxWidth, $width);
                $totalHeight += $height * $item['quantity'];

                $order->orderItems()->create([
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name'       => $item['name'],
                    'sku'                => $item['sku'] ?? '',
                    'image_url'          => $item['image'] ?? '',
                    'variant_values'     => json_encode($item['attributes'] ?? []),
                    'price'              => $item['price'],
                    'quantity'           => $item['quantity'],
                    'total_price'        => $item['price'] * $item['quantity'],
                    'weight'             => $weight,
                    'length'             => $length,
                    'width'              => $width,
                    'height'             => $height,
                ]);
            }

            $order->update([
                'total_weight' => $totalWeight,
                'max_length'   => $maxLength,
                'max_width'    => $maxWidth,
                'total_height' => $totalHeight,
            ]);

            $now = now();

            if ($couponId) {
                DB::table('coupon_user')->insertOrIgnore([
                    'coupon_id'  => $couponId,
                    'user_id'    => $user->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                DB::table('coupons')->where('id', $couponId)->increment('used_count');
            }

            if ($shippingCouponId) {
                DB::table('coupon_user')->insertOrIgnore([
                    'coupon_id'  => $shippingCouponId,
                    'user_id'    => $user->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                DB::table('coupons')->where('id', $shippingCouponId)->increment('used_count');
            }

            return response()->json(['success' => true, 'message' => 'Đặt hàng thành công!', 'order_id' => $order->id]);
        } catch (\Throwable $e) {
            Log::error('❌ Lỗi đặt hàng: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống khi xử lý đơn hàng.', 'error' => $e->getMessage()], 500);
        }
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
