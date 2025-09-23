<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\CouponUser;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()
            ->orderByDesc('created_at')   // mới nhất lên đầu
            ->paginate(10)
            ->withQueryString();          // giữ tham số filter khi chuyển trang

        return view('admin.coupons.index', compact('coupons'));
    }


    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        // Ép code IN HOA & trim
        $request->merge(['code' => strtoupper(trim($request->code))]);

        $rules = [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code'),
                'regex:/^[A-Z0-9]+$/',
            ],
            'type'        => 'required|in:product_discount,shipping_discount,order_discount',
            'value_type'  => 'required|in:fixed,percentage',

            // Khi là %, không được > 100
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                function ($attr, $val, $fail) use ($request) {
                    if ($request->value_type === 'percentage' && $val > 100) {
                        $fail('Giá trị giảm (%) không được lớn hơn 100%.');
                    }
                },
            ],

            // Trần tiền khi giảm %, KHÔNG check 100
            'max_discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'prohibited_unless:value_type,percentage',
            ],

            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:0',
            'per_user_limit'   => 'nullable|integer|min:0',
            'start_date'       => ['required', 'date'],
            'end_date'         => ['required', 'date', 'after:start_date'],
            'only_for_new_users'      => 'nullable|boolean',
            'is_exclusive'            => 'nullable|boolean',
            'active'                  => 'nullable|boolean',
            'applicable_product_ids'  => 'nullable|string',
            'applicable_category_ids' => 'nullable|string',
            'apply_all_products'      => 'nullable|boolean',
        ];

        $messages = [
            'code.regex'          => 'Mã giảm giá chỉ được chứa chữ in hoa (A-Z) và số (0-9).',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu.',
            'end_date.required'   => 'Vui lòng nhập ngày kết thúc.',
            'end_date.after'      => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
        ];

        $validated = $request->validate($rules, $messages);

        // Gán các field phụ
        $validated['applicable_product_ids'] = $request->boolean('apply_all_products')
            ? null
            : $this->toIntArrayOrNull($request->applicable_product_ids);

        $validated['applicable_category_ids'] = $this->toIntArrayOrNull($request->applicable_category_ids);
        $validated['only_for_new_users'] = $request->has('only_for_new_users');
        $validated['is_exclusive']       = $request->has('is_exclusive');
        $validated['active']             = $request->has('active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã giảm giá thành công!');
    }


    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }



    public function update(Request $request, Coupon $coupon)
    {
        // Ép code IN HOA & trim
        $request->merge(['code' => strtoupper(trim($request->code))]);

        $rules = [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($coupon->id),
                'regex:/^[A-Z0-9]+$/',
            ],
            'type'        => 'required|in:product_discount,shipping_discount,order_discount',
            'value_type'  => 'required|in:fixed,percentage',

            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                function ($attr, $val, $fail) use ($request) {
                    if ($request->value_type === 'percentage' && $val > 100) {
                        $fail('Giá trị giảm (%) không được lớn hơn 100%.');
                    }
                },
            ],

            // Trần tiền khi giảm %, KHÔNG check 100
            'max_discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'prohibited_unless:value_type,percentage',
            ],

            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:0',
            'per_user_limit'   => 'nullable|integer|min:0',
            'start_date'       => ['required', 'date'],
            'end_date'         => ['required', 'date', 'after:start_date'],
            'only_for_new_users'      => 'nullable|boolean',
            'is_exclusive'            => 'nullable|boolean',
            'active'                  => 'nullable|boolean',
            'applicable_product_ids'  => 'nullable|string',
            'applicable_category_ids' => 'nullable|string',
            'apply_all_products'      => 'nullable|boolean',
        ];

        $messages = [
            'code.regex'          => 'Mã giảm giá chỉ được chứa chữ in hoa (A-Z) và số (0-9).',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu.',
            'end_date.required'   => 'Vui lòng nhập ngày kết thúc.',
            'end_date.after'      => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
        ];

        // Validator để thêm các check bổ sung
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($v) use ($request, $coupon) {
            // 1) usage_limit không nhỏ hơn số đã dùng
            $used = $coupon->used_count
                ?? CouponUser::where('coupon_id', $coupon->id)->count();

            if ($request->filled('usage_limit')) {
                $newLimit = (int) $request->usage_limit;
                if ($newLimit < $used) {
                    $v->errors()->add('usage_limit', "Đã có {$used} lượt dùng, không thể đặt giới hạn nhỏ hơn.");
                }
            }

            // 2) Nếu mã đã hết hạn mà không gia hạn end_date
            if (
                now()->gte($coupon->end_date) &&
                (! $request->filled('end_date') || now()->gte($request->end_date))
            ) {
                $v->errors()->add('end_date', 'Mã đã hết hạn, muốn dùng tiếp vui lòng gia hạn ngày kết thúc.');
            }
        });

        $validated = $validator->validate();

        // Gán các field phụ
        $validated['applicable_product_ids'] = $request->boolean('apply_all_products')
            ? null
            : $this->toIntArrayOrNull($request->applicable_product_ids);

        $validated['applicable_category_ids'] = $this->toIntArrayOrNull($request->applicable_category_ids);
        $validated['only_for_new_users'] = $request->has('only_for_new_users');
        $validated['is_exclusive']       = $request->has('is_exclusive');
        $validated['active']             = $request->has('active');

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
