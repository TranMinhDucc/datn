<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class CategoryController extends Controller
{
    //
    public function show($id)
    {
        $category = Category::findOrFail($id);

        // Phân trang sản phẩm theo category_id
        $products = Product::where('category_id', $id)
            ->with('label') // 👈 load label luôn
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        // Wishlist ID
        $wishlistProductIds = auth()->check()
            ? Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray()
            : [];

        return view('client.categories.show', compact('category', 'products', 'wishlistProductIds'));
    }



    public function index()
    {
        return view('client.categories.index');
    }
}
