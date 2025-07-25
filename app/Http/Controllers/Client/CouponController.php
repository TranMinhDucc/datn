<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    // ✅ Áp dụng mã giảm giá (tạm thời - chỉ lưu session)
public function applyCoupon(Request $request)
{
    $code = $request->input('code');
    $subtotal = $request->input('subtotal');
    $user = auth()->user();

    $coupon = Coupon::where('code', $code)
        ->where('active', 1)
        ->first();

    if (!$coupon) {
        return response()->json(['error' => 'Mã không tồn tại hoặc bị khóa'], 400);
    }

    $now = now();

    // ✅ Check thời gian hợp lệ
    if ($coupon->start_date && $now->lt($coupon->start_date)) {
        return response()->json(['error' => 'Mã chưa bắt đầu'], 400);
    }
    if ($coupon->end_date && $now->gt($coupon->end_date)) {
        return response()->json(['error' => 'Mã đã hết hạn'], 400);
    }

    // ✅ Check số lượt sử dụng (nếu có giới hạn)
    if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
        return response()->json(['error' => 'Mã đã hết lượt sử dụng'], 400);
    }

    // ✅ Check per user limit (nếu có)
    if ($coupon->per_user_limit) {
        $userCount = DB::table('coupon_user')
            ->where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->count();
        if ($userCount >= $coupon->per_user_limit) {
            return response()->json(['error' => 'Bạn đã dùng mã này rồi'], 400);
        }
    }

    // ✅ Check đơn hàng tối thiểu
    if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
        return response()->json(['error' => 'Chưa đạt giá trị đơn hàng tối thiểu'], 400);
    }

    // ✅ Check chỉ dành cho người dùng mới
    if ($coupon->only_for_new_users) {
        $accountAgeDays = $user->created_at->diffInDays($now);
        if ($accountAgeDays > 7) {
            return response()->json(['error' => 'Chỉ áp dụng cho người dùng mới'], 400);
        }
    }

    // ✅ Check vai trò người dùng (nếu có)
    if ($coupon->eligible_user_roles && is_array(json_decode($coupon->eligible_user_roles))) {
        $roles = json_decode($coupon->eligible_user_roles);
        if (!in_array($user->role, $roles)) {
            return response()->json(['error' => 'Mã không áp dụng cho vai trò của bạn'], 400);
        }
    }
    

    // ✅ Thành công
    return response()->json(['success' => true, 'coupon' => $coupon]);
}


    // ✅ Huỷ mã (xoá khỏi session)
    public function remove()
    {
        Session::forget('coupon');
        return redirect()->back()->with('success', '🚫 Đã huỷ mã giảm giá.');
    }

    // ✅ Gọi ở bước thanh toán (ghi nhận sử dụng mã)
    public function finalizeCouponUsage()
    {
        if (!auth()->check()) return;

        if (Session::has('coupon')) {
            $couponData = Session::get('coupon');
            $coupon = Coupon::find($couponData['id']);

            if ($coupon) {
                DB::table('coupon_user')->insert([
                    'user_id' => auth()->id(),
                    'coupon_id' => $coupon->id,
                    'used_at' => now(),
                ]);

                $coupon->increment('used_count');
                Session::forget('coupon');
            }
        }
    }
}
