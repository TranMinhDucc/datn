<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Hiển thị danh sách sản phẩm yêu thích của người dùng
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->where('is_active', 1)
            ->latest()
            ->get();

        return view('client.wishlist.index', compact('wishlists'));
    }

    // Thêm sản phẩm vào wishlist
    public function add($productId)
    {
        $userId = Auth::id();

        // Kiểm tra sản phẩm có tồn tại trong wishlist chưa
        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        if ($exists) {
            return response()->json([
                'status' => 'exists',
                'message' => 'Sản phẩm đã có trong danh sách yêu thích.'
            ]);
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'is_active' => 1,
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Đã thêm vào danh sách yêu thích.'
        ]);
    }

    // Xoá sản phẩm khỏi wishlist
    public function remove($productId)
    {
        $userId = Auth::id();

        Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        // return response()->json(['success' => true, 'message' => 'Đã xoá khỏi danh sách yêu thích.']);

        return back()->with('success', 'Đã xoá khỏi danh sách yêu thích!');
    }
}
