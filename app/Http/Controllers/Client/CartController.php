<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $products = Product::with('brand')->get();

        $availableCoupons = Coupon::where('active', 1)
            ->where(function ($q) {
                $q->where('usage_limit', 0)
                    ->orWhereRaw('used_count < usage_limit');
            })
            ->get()
            ->filter(function ($coupon) {
                // Nếu có per_user_limit thì kiểm tra user đã dùng bao nhiêu lần
                if (!is_null($coupon->per_user_limit)) {
                    $userUsageCount = $coupon->users()
                        ->where('user_id', auth()->id())
                        ->count();

                    return $userUsageCount < $coupon->per_user_limit;
                }

                return true; // nếu không giới hạn thì cho qua
            });

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
                        'message' => !$variant ? 'Không tìm thấy biến thể' : "Chỉ còn {$variant->quantity} sản phẩm"
                    ];
                }
            } else {
                $product = Product::find($productId);
                if (!$product || $product->stock_quantity < $qty) {
                    $invalidItems[] = [
                        'id' => $productId,
                        'variant_id' => null,
                        'message' => !$product ? 'Không tìm thấy sản phẩm' : "Chỉ còn {$product->stock_quantity} sản phẩm"
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
