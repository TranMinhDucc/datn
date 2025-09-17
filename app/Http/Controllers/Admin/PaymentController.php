<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $r, Order $order)
    {
        $data = $r->validate([
            'kind'   => 'required|in:payment,refund',
            'method' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:500',
        ]);

        // Chốt ở trạng thái completed, có thể đổi tuỳ quy trình của bạn
        $order->payments()->create($data + [
            'status'     => 'completed',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Đã ghi nhận giao dịch.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Đã xoá giao dịch.');
    }
}
