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
        $banners = Banner::where('status', 1)->get();

        $products = Product::where('is_active', 1)
            ->with(['label'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $latestProducts = Product::where('is_active', 1)
            ->latest('created_at')
            ->take(8)
            ->get();

        $bestSellerProducts = Product::where('is_active', 1)
            ->orderByDesc('sold_quantity')
            ->take(8)
            ->get();

        $categories = Category::whereNull('parent_id')->get();

        $latestBlogs = Blog::with(['author'])
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $unreadNotifications = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $unreadNotifications = $user->unreadNotifications;

            // ✅ Đánh dấu tất cả là đã đọc để không thông báo lại
            $user->unreadNotifications->markAsRead();
        }

        return view('client.home', compact(
            'banners',
            'categories',
            'products',
            'latestBlogs',
            'latestProducts',
            'bestSellerProducts',
            'unreadNotifications'
        ));
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
