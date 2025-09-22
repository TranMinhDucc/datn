<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAdjustment;
use Illuminate\Http\Request;

class OrderAdjustmentController extends Controller
{
    public function store(Request $r, Order $order)
    {
        $hasGhn = \App\Models\ShippingOrder::where('order_id', $order->id)
            ->whereNotNull('shipping_code')
            ->exists();

        if ($hasGhn) {
            return back()->with('error', '❌ Đơn đã gửi sang GHN, không thể thêm điều chỉnh.');
        }
        $data = $r->validate([
            'label' => 'required|string|max:255',
            'code'  => 'nullable|string|max:50',
            'type'  => 'required|in:charge,discount',
            'amount' => 'required|numeric|min:0.01',
            'taxable' => 'nullable|boolean',
        ]);
        $order->adjustments()->create($data + [
            'created_by' => auth()->id(),
        ]);
        return back()->with('success', 'Đã thêm điều chỉnh.');
    }

    public function destroy(OrderAdjustment $adjustment)
    {
        $orderId = $adjustment->order_id;

        // Nếu không có order_id thì báo lỗi luôn
        if (!$orderId) {
            return back()->with('error', '❌ Điều chỉnh không gắn với đơn hàng nào.');
        }

        // Kiểm tra có vận đơn GHN chưa
        $hasGhn = \App\Models\ShippingOrder::where('order_id', $orderId)
            ->whereNotNull('shipping_code')
            ->exists();

        if ($hasGhn) {
            return back()->with('error', '❌ Đơn đã gửi sang GHN, không thể xóa điều chỉnh.');
        }

        $adjustment->delete();
        return back()->with('success', '✅ Đã xoá điều chỉnh.');
    }
}
