<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Lấy các đơn hàng của người dùng
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.account.dashboard', compact('orders'));
    }
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Lấy lý do hủy (bao gồm trường hợp chọn "Khác")
        $reason = $request->cancel_reason === 'Khác'
            ? $request->cancel_reason_other
            : $request->cancel_reason;

        if ($order->status === 'pending') {
            $order->status = 'cancelled';
            $order->cancelled_at = now();
            $order->cancel_reason = $reason;
            $order->save();

            return back()->with('success', 'Đơn hàng đã được hủy.');
        }

        if ($order->status === 'confirmed' && !$order->cancel_request) {
            $order->cancel_request = true;
            $order->cancel_reason = $reason;
            $order->save();

            return back()->with('success', 'Yêu cầu hủy đơn đã được gửi. Vui lòng chờ duyệt.');
        }

        return back()->with('error', 'Không thể hủy hoặc gửi yêu cầu hủy đơn.');
    }

    public function show(Order $order)
    {
        // Đảm bảo người dùng chỉ xem đơn của chính họ
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này');
        }

        // Load thêm các liên kết nếu cần
        $order->load(['orderItems.product', 'address', 'address.province', 'address.district', 'address.ward']);

        return view('client.account.tracking', compact('order'));
    }
}
