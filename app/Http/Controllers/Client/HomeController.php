<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 1)->get()->map(function ($b) {
            return [
                'subtitle'    => $b->subtitle ?? '',
                'title'       => $b->title ?? '',
                'description' => $b->description ?? '',
                'main_image'       => $b->main_image
                    ? asset('storage/' . $b->main_image)
                    : asset('assets/client/images/layout-4/1.png'),
                'button_link' => route('client.category.index'),
                'button_text' => 'Shop Now',
            ];
        });


        $latestProducts = Product::where('is_active', 1)
            ->with('labels')
            ->latest('created_at')
            ->take(8)
            ->get();

        $bestSellerProducts = Product::where('is_active', 1)
            ->with('labels')
            ->orderByDesc('sold_quantity')
            ->take(8)
            ->get();

        $categories = Category::whereNull('deleted_at')->get();

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
        $products = Product::where('is_active', 1)
            ->with(['label'])
            ->withAvg(['reviews' => function ($q) {
                $q->where('approved', true);
            }], 'rating')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $latestProducts = Product::where('is_active', 1)
            ->withAvg(['reviews' => function ($q) {
                $q->where('approved', true);
            }], 'rating')
            ->latest('created_at')
            ->take(8)
            ->get();

        $bestSellerProducts = Product::where('is_active', 1)
            ->withAvg(['reviews' => function ($q) {
                $q->where('approved', true);
            }], 'rating')
            ->orderByDesc('sold_quantity')
            ->take(8)
            ->get();

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
        // Lấy settings dạng mảng: ['hotline' => '...', 'email' => '...', ...]
        $settings = Setting::pluck('value', 'name')->toArray();

        return view('client.contact', compact('settings'));
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
