<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 1)->get(); // bỏ with('buttons') và orderBy('thu_tu')

        $products = Product::where('is_active', 1)
            ->with('label') // 👈 hasOne: trả về 1 object
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        // Latest products
        $latestProducts = Product::where('is_active', 1)
            ->latest('created_at')
            ->take(8)
            ->get();
        // Best seller products
        $bestSellerProducts = Product::where('is_active', 1)
            ->orderByDesc('sold_quantity')
            ->take(8)
            ->get();
        $categories = Category::whereNull('parent_id')->get(); // ← thêm dòng này
        $latestBlogs = Blog::with(['author']) // eager load author
            ->published()
            ->latest('published_at')
            ->take(3) // hoặc số lượng bạn muốn
            ->get();
        return view('client.home', compact('banners', 'categories', 'products', 'latestBlogs', 'latestProducts', 'bestSellerProducts'));
    }
    public function policy()
    {
        return view('client.policy');
    }

    public function contact()
    {
        return view('client.contact');
    }

    public function faq()
    {
        return view('client.faq');
    }

    public function login()
    {
        if (Auth::check()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
        }

        return view('client.auth.login');
    }
    public function reset_password()
    {
        return view('client.auth.request-reset-password');
    }
    public function register()
    {
        return view('client.auth.register');
    }
    public function blogs()
    {
        return view('client.pages.blogs');
    }
    public function wallet()
    {
        return view('client.pages.wallet');
    }
    public function productDetail()
    {
        return view('client.product_detail');
    }
}
