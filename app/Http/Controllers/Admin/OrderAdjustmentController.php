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

    public function destroy(OrderAdjustment $adj)
    {
        $adj->delete();
        return back()->with('success', 'Đã xoá điều chỉnh.');
    }
}
