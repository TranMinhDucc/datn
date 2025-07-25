<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
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
                // Loại bỏ nếu user đã dùng
                return $coupon->users()
                    ->where('user_id', auth()->id())
                    ->count() === 0;
            });

        return view('client.cart.index', compact('products', 'availableCoupons'));
    }
}
