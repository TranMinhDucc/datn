<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariant;
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

        $reason = $request->cancel_reason === 'Khác'
            ? $request->cancel_reason_other
            : $request->cancel_reason;

        if ($order->status === 'pending') {
            $order->status = 'cancelled';
            $order->cancelled_at = now();
            $order->cancel_reason = $reason;
            $order->save();

            // ✅ Hoàn lại số lượng vào kho
            $this->restoreStock($order);

            return back()->with('success', 'Đơn hàng đã được hủy và sản phẩm đã hoàn kho.');
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
    protected function restoreStock(Order $order)
    {
        $order->load('orderItems');

        foreach ($order->orderItems as $item) {
            if ($item->product_variant_id && $item->quantity) {
                ProductVariant::where('id', $item->product_variant_id)
                    ->increment('quantity', $item->quantity);
            }
        }
    }

    public function reorderData(Order $order)
    {
        $items = $order->orderItems->map(function ($item) {
            return [
                'id' => $item->product_id,
                'variant_id' => $item->product_variant_id,
                'name' => $item->product_name,
                'sku' => $item->sku ?? '',
                'brand' => $item->product->brand->name ?? 'Unknown', // Thêm brand từ product
                'image' => $item->image_url ?? '',
                'price' => floatval($item->price),
                'quantity' => intval($item->quantity),
                'attributes' => json_decode($item->variant_values ?? '{}', true),
            ];
        });

        return response()->json(['success' => true, 'items' => $items]);
    }
    public function showReturnForm(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->status !== 'completed' || !$order->delivered_at || now()->diffInDays($order->delivered_at) > 3) {
            return redirect()->route('client.account.dashboard')->with('error', 'Không thể yêu cầu đổi trả đơn hàng này.');
        }

        return view('client.account.return-form', compact('order')); // ✅ đúng đường dẫn Blade
    }

    

}
