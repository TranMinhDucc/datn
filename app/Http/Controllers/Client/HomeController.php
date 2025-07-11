<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
            $banners = Banner::where('status', 1)->get(); // bỏ with('buttons') và orderBy('thu_tu')

        $products = Product::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        $categories = Category::whereNull('parent_id')->get(); // ← thêm dòng này

        return view('client.home', compact('banners', 'categories', 'products'));
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
