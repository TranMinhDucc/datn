<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Lấy toàn bộ địa chỉ của người dùng
        $addresses = $user->shippingAddresses;

        // Lấy địa chỉ mặc định
        $defaultAddress = $addresses->firstWhere('is_default', 1);

        // ✅ Lấy danh sách phương thức thanh toán đang hoạt động
        $paymentMethods = \App\Models\PaymentMethod::where('active', 1)->get();

        return view('client.checkout.index', compact('addresses', 'defaultAddress', 'paymentMethods'));
    }


public function placeOrder(Request $request)
{
    try {
        Log::info('📥 Dữ liệu nhận:', $request->all());

        if (!$request->payment_method_id || !$request->shipping_address_id) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin bắt buộc.'
            ]);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập.'], 401);
        }

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

        if ($couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            if (!$coupon) {
                return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.'], 400);
            }

            $userUsed = DB::table('coupon_user')
                ->where('coupon_id', $couponId)
                ->where('user_id', $user->id)
                ->count();

            if ($coupon->per_user_limit > 0 && $userUsed >= $coupon->per_user_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã sử dụng mã giảm giá này đủ số lần cho phép.'
                ], 400);
            }
        }

        // ✅ Tạo đơn hàng
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
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo đơn hàng.',
            ], 500);
        }

        Log::info('✅ Đơn hàng đã tạo:', ['order_id' => $order->id]);

        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'product_id'         => $item['id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'product_name'       => $item['name'],
                'sku'                => $item['sku'] ?? '',
                'image_url'          => $item['image'] ?? '',
                'variant_values'     => json_encode($item['attributes'] ?? []),
                'price'              => $item['price'],
                'quantity'           => $item['quantity'],
                'total_price'        => $item['price'] * $item['quantity'],
            ]);
        }

        // ✅ Ghi nhận mã giảm giá đã sử dụng
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

        return response()->json([
            'success'  => true,
            'message'  => 'Đặt hàng thành công!',
            'order_id' => $order->id,
        ]);

    } catch (\Throwable $e) {
        Log::error('❌ Lỗi đặt hàng: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Lỗi hệ thống khi xử lý đơn hàng.',
            'error'   => $e->getMessage()
        ], 500);
    }
}



    private function calculateDiscount($couponId, $userId, $cartSubtotal)
    {
        if (!$couponId) return 0;

        $coupon = \App\Models\Coupon::find($couponId);
        $user = \App\Models\User::find($userId);

        if (!$coupon || !$coupon->active) return 0;

        // ❌ Hết hạn
        if (now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) return 0;

        // ❌ Vượt giới hạn tổng
        if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) return 0;

        // ❌ Vượt giới hạn theo từng người
        if ($coupon->per_user_limit > 0) {
            $usedByUser = DB::table('coupon_user')
                ->where('coupon_id', $couponId)
                ->where('user_id', $userId)
                ->count();

            if ($usedByUser >= $coupon->per_user_limit) return 0;
        }

        // ❌ Kiểm tra người dùng mới (nếu có)
        if ($coupon->only_for_new_users && !$user->is_new_user) return 0;

        // ❌ Kiểm tra vai trò được áp dụng
        if ($coupon->eligible_user_roles) {
            $allowedRoles = json_decode($coupon->eligible_user_roles, true);
            if (!in_array($user->role, $allowedRoles)) return 0;
        }

        // ❌ Đơn hàng không đủ tối thiểu
        if ($cartSubtotal < $coupon->min_order_amount) return 0;

        // ✅ Tính giảm giá
        if ($coupon->value_type === 'percentage') {
            $discount = $cartSubtotal * ($coupon->discount_value / 100);
            return $coupon->max_discount_amount
                ? min($discount, $coupon->max_discount_amount)
                : $discount;
        }

        if ($coupon->value_type === 'fixed') {
            return $coupon->discount_value;
        }

        return 0;
    }
}
