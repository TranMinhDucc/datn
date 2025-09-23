<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\Category; // ⬅️ thêm
use Illuminate\Support\Facades\Schema;
use Gloudemans\Shoppingcart\Facades\Cart; // ⬅️ nếu bạn dùng package Shoppingcart, giữ dòng này. Nếu không dùng thì xoá.

class CartController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('brand')->get();

        $now  = now();
        $user = auth()->user();

        // ===== Giữ logic lọc coupon ở nhánh HEAD, có chỉnh nhẹ per_user_limit để chỉ tính đơn đã trả tiền =====
        $availableCoupons = Coupon::query()
            ->where('active', 1)
            ->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', $now))
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $now))
            ->where(fn($q) => $q->where('usage_limit', 0)->orWhereColumn('used_count', '<', 'usage_limit'))
            ->when($user, function ($q) use ($user, $now) {
                // only_for_new_users: tính "mới" theo ngày tạo tài khoản
                $q->where(fn($qq) => $qq->where('only_for_new_users', 0)
                    ->orWhere(fn($qqq) => $qqq->where('only_for_new_users', 1)
                        ->whereRaw('TIMESTAMPDIFF(DAY, ?, ?) <= 7', [$user->created_at, $now])));

                // per_user_limit: chỉ tính đơn đã thanh toán
                $q->where(fn($qq) => $qq->where('per_user_limit', 0)
                    ->orWhereRaw('(SELECT COUNT(*)
                                   FROM coupon_user cu
                                   JOIN orders o ON o.id = cu.order_id AND o.payment_status = "paid"
                                   WHERE cu.coupon_id = coupons.id AND cu.user_id = ?) < per_user_limit', [$user->id]));
            })
            // Vai trò (nếu có cột)
            ->when($user && Schema::hasColumn('coupons', 'eligible_user_roles'), function ($q) use ($user) {
                $q->where(fn($qq) => $qq->whereNull('eligible_user_roles')
                    ->orWhereRaw('JSON_CONTAINS(eligible_user_roles, JSON_QUOTE(?))', [$user->role]));
            })
            // Khách vãng lai: không hiển thị mã chỉ dành cho user mới
            ->when(!$user, fn($q) => $q->where('only_for_new_users', 0))
            ->get();

        // ===== Ghép thêm phần GỢI Ý SẢN PHẨM từ nhánh kia =====
        // Lấy danh mục từ giỏ hàng
        try {
            // Nếu bạn dùng package Shoppingcart:
            $cartContent = Cart::content()->toArray();
            // Mỗi item cần có category_id (bạn có thể đính kèm vào 'options' khi add)
            $cartCategoryIds = collect($cartContent)
                ->map(fn($row) => data_get($row, 'options.category_id'))
                ->filter()
                ->unique();
        } catch (\Throwable $e) {
            // Nếu không dùng Shoppingcart, fallback về session 'cart' tự quản lý ({id, category_id,...})
            $cartItems = session()->get('cart', []);
            $cartCategoryIds = collect($cartItems)->pluck('category_id')->filter()->unique();
        }

        if ($cartCategoryIds->count() <= 1) {
            $suggestCategoryIds = Category::whereNotIn('id', $cartCategoryIds)->pluck('id');
        } else {
            $suggestCategoryIds = Category::pluck('id');
        }

        $suggestions = Product::whereIn('category_id', $suggestCategoryIds)
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('client.cart.index', compact('products', 'availableCoupons', 'suggestions'));
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

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Nếu bạn dùng Gloudemans/Shoppingcart, có thể đính kèm category_id vào options để dùng cho gợi ý:
        Cart::add(
            $product->id,
            $product->name,
            1,
            $product->price,
            ['category_id' => $product->category_id] // ⬅️ để phần gợi ý đọc được
        );

        return response()->json([
            'cart_count' => Cart::count(),
            'cart_html'  => view('partials.cart_items', ['cart' => Cart::content()])->render()
        ]);
    }
}
