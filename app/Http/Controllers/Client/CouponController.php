<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
public function apply(Request $request)
{
    $code = $request->input('code');
    $coupon = Coupon::where('code', $code)->first();

    if (!$coupon) {
        return redirect()->back()->withErrors(['coupon' => '❌ Mã giảm giá không tồn tại.']);
    }

    if (!$coupon->isValid()) {
        return redirect()->back()->withErrors(['coupon' => '❌ Mã đã hết hạn hoặc đã sử dụng hết.']);
    }

    if (auth()->check()) {
        $used = DB::table('coupon_user')
            ->where('user_id', auth()->id())
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($used) {
            return redirect()->back()->withErrors(['coupon' => '❌ Bạn đã sử dụng mã này rồi.']);
        }
    }

    // ✅ Chỉ lưu session, không insert DB ở đây
    Session::put('coupon', [
        'id'    => $coupon->id,
        'code'  => $coupon->code,
        'type'  => $coupon->discount_type,
        'value' => $coupon->discount_value,
    ]);

    return redirect()->route('client.cart.index')->with('success', '🎉 Áp mã giảm giá thành công!');
}


    // ✅ Xoá session mã giảm giá sau khi thanh toán


    
}
