<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function index()
{
    $products = Product::with('brand')->get();

    $now  = now();
    $user = auth()->user();

    $availableCoupons = Coupon::query()
        ->where('active', 1)
        ->where(fn($q)=>$q->whereNull('start_date')->orWhere('start_date','<=',$now))
        ->where(fn($q)=>$q->whereNull('end_date')->orWhere('end_date','>=',$now))
        ->where(fn($q)=>$q->where('usage_limit',0)->orWhereColumn('used_count','<','usage_limit'))

        ->when($user, function ($q) use ($user, $now) {
            // only_for_new_users
            $q->where(fn($qq)=>$qq->where('only_for_new_users',0)
                ->orWhere(fn($qqq)=>$qqq->where('only_for_new_users',1)
                    ->whereRaw('TIMESTAMPDIFF(DAY, ?, ?) <= 7', [$user->created_at, $now])));

            // per_user_limit
            $q->where(fn($qq)=>$qq->where('per_user_limit',0)
                ->orWhereRaw('(SELECT COUNT(*) FROM coupon_user cu
                               WHERE cu.coupon_id = coupons.id AND cu.user_id = ?) < per_user_limit', [$user->id]));
        })

        // Chỉ thêm điều kiện vai trò NẾU bảng có cột eligible_user_roles
        ->when($user && Schema::hasColumn('coupons','eligible_user_roles'), function ($q) use ($user) {
            $q->where(fn($qq)=>$qq->whereNull('eligible_user_roles')
                ->orWhereRaw('JSON_CONTAINS(eligible_user_roles, JSON_QUOTE(?))', [$user->role]));
        })

        // Nếu chưa đăng nhập, đừng đụng tới eligible_user_roles (tránh lỗi)
        ->when(!$user, fn($q)=>$q->where('only_for_new_users',0))

        ->get();

    return view('client.cart.index', compact('products', 'availableCoupons'));
}

    public function checkStock(Request $request)
    {
        $cartItems = $request->input('cartItems', []);
        $invalidItems = [];

        foreach ($cartItems as $item) {
            $variantId = $item['variant_id'] ?? null;
            $productId = $item['id'];
            $qty = $item['quantity'];

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant || $variant->quantity < $qty) {
                    $invalidItems[] = [
                        'id' => $productId,
                        'variant_id' => $variantId,
                        'message' => !$variant
                            ? 'Không tìm thấy biến thể'
                            : "Chỉ còn {$variant->quantity} sản phẩm"
                    ];
                }
            } else {
                $product = Product::find($productId);
                if (!$product || $product->stock_quantity < $qty) {
                    $invalidItems[] = [
                        'id' => $productId,
                        'variant_id' => null,
                        'message' => !$product
                            ? 'Không tìm thấy sản phẩm'
                            : "Chỉ còn {$product->stock_quantity} sản phẩm"
                    ];
                }
            }
        }

        if (count($invalidItems)) {
            return response()->json(['invalidItems' => $invalidItems], 422);
        }

        return response()->json(['message' => 'Tất cả sản phẩm còn hàng']);
    }
}
