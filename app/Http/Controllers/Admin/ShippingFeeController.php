<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\ShippingFee;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingFeeController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        $methods = ShippingMethod::all();
        $fees = ShippingFee::with(['province', 'district', 'ward', 'method'])->get();
        return view('admin.shipping_fees.index', compact('provinces', 'methods', 'fees'));
    }
    public function create()
    {
        $provinces = Province::all();
        $methods = ShippingMethod::all();
        $fees = ShippingFee::with(['province', 'district', 'ward', 'method'])->get();
        return view('admin.shipping_fees.create', compact('provinces', 'methods', 'fees'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->merge([
            'district_id' => $request->input('district_id') ?: null,
            'ward_id' => $request->input('ward_id') ?: null,
        ]);

        $validated = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'price' => 'required|numeric|min:0',
            'free_shipping_minimum' => 'nullable|numeric|min:0',
        ]);

        $fee =  ShippingFee::updateOrCreate(
            [
                'province_id' => $validated['province_id'],
                'district_id' => $validated['district_id'] ?? null,
                'ward_id' => $validated['ward_id'] ?? null,
            ],
            [
                'price' => $validated['price'],
                'free_shipping_minimum' => $validated['free_shipping_minimum'] ?? null,
            ]
        );
        // dd($fee->toArray());

        return redirect()->route('admin.shipping-fees.index')
            ->with('success', 'Phí vận chuyển đã được cập nhật hoặc thêm mới!');
    }


    public function destroy($id)
    {
        $fee = ShippingFee::findOrFail($id);
        $fee->delete();
        return redirect()->back()->with('success', 'Xóa phí vận chuyển thành công!');
    }
}
