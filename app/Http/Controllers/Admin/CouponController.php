<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:product_discount,shipping_discount,order_discount',
            'value_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'per_user_limit' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'only_for_new_users' => 'nullable|boolean',
            'is_exclusive' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'eligible_user_roles' => 'nullable|array',
            'applicable_product_ids' => 'nullable|string',
            'applicable_category_ids' => 'nullable|string',
            'apply_all_products' => 'nullable|boolean',
        ]);

        $validated['eligible_user_roles'] = $request->filled('eligible_user_roles') ? $request->eligible_user_roles : null;
        $validated['applicable_product_ids'] = $request->has('apply_all_products') ? null : $this->toIntArrayOrNull($request->applicable_product_ids);
        $validated['applicable_category_ids'] = $this->toIntArrayOrNull($request->applicable_category_ids);

        $validated['only_for_new_users'] = $request->has('only_for_new_users');
        $validated['is_exclusive'] = $request->has('is_exclusive');
        $validated['active'] = $request->has('active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:product_discount,shipping_discount,order_discount',
            'value_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'per_user_limit' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'only_for_new_users' => 'nullable|boolean',
            'is_exclusive' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'eligible_user_roles' => 'nullable|array',
            'applicable_product_ids' => 'nullable|string',
            'applicable_category_ids' => 'nullable|string',
            'apply_all_products' => 'nullable|boolean',
        ]);

        $validated['eligible_user_roles'] = $request->filled('eligible_user_roles') ? $request->eligible_user_roles : null;
        $validated['applicable_product_ids'] = $request->has('apply_all_products') ? null : $this->toIntArrayOrNull($request->applicable_product_ids);
        $validated['applicable_category_ids'] = $this->toIntArrayOrNull($request->applicable_category_ids);

        $validated['only_for_new_users'] = $request->has('only_for_new_users');
        $validated['is_exclusive'] = $request->has('is_exclusive');
        $validated['active'] = $request->has('active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Xóa mã giảm giá thành công.');
    }

    private function toIntArrayOrNull(?string $csv): ?array
    {
        return $csv ? array_map('intval', explode(',', $csv)) : null;
    }
}