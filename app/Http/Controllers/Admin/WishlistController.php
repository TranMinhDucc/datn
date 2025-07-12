<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with(['user', 'product'])->latest()->paginate(10);
        return view('admin.wishlists.index', compact('wishlists'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.wishlists.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'is_active' => 'required|boolean',
            'note' => 'nullable|string',
        ]);

        Wishlist::create($request->only(['user_id', 'product_id', 'is_active', 'note']));
        return redirect()->route('admin.wishlists.index')->with('success', 'Đã thêm wishlist.');
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->route('admin.wishlists.index')->with('success', 'Đã xoá wishlist.');
    }
}
