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
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
         $banners = Banner::query()
        ->where('status', 1)
        ->with([
            'product1.category', 'product1.reviews',
            'product2.category', 'product2.reviews',
        ])
        ->latest()
        ->get()
        ->map(function ($b) {
            $imgUrl = function ($path) {
                if (!$path) return null;
                return Str::startsWith($path, ['http://','https://']) ? $path : asset('storage/'.$path);
            };

            $p1 = $b->product1 ? [
                'id'         => $b->product1->id,
                'slug'       => $b->product1->slug,
                'name'       => $b->product1->name,
                'image'      => $imgUrl($b->product1->image),
                'category'   => optional($b->product1->category)->name,
                'price'      => $b->product1->price,
                'sale_price' => $b->product1->sale_price,
                'avg_rating' => round(optional($b->product1->reviews)->avg('rating') ?? 0, 1),
                'url'        => route('client.products.show', $b->product1->slug),
            ] : null;

            $p2 = $b->product2 ? [
                'id'         => $b->product2->id,
                'slug'       => $b->product2->slug,
                'name'       => $b->product2->name,
                'image'      => $imgUrl($b->product2->image),
                'category'   => optional($b->product2->category)->name,
                'price'      => $b->product2->price,
                'sale_price' => $b->product2->sale_price,
                'avg_rating' => round(optional($b->product2->reviews)->avg('rating') ?? 0, 1),
                'url'        => route('client.products.show', $b->product2->slug),
            ] : null;

            return [
                'subtitle'     => $b->subtitle,
                'title'        => $b->title,
                'description'  => $b->description,
                'main_image'   => $imgUrl($b->main_image),
                'btn_link'  => $b->btn_link?: '#', // hoặc trường riêng của bạn
                'btn_title'  => $b->btn_title?: 'Mua ngay',                      // hoặc trường riêng
                'product1'     => $p1,
                'product2'     => $p2,
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

        $specialOfferProducts = Product::where('is_active', 1)
            ->where('is_special_offer', true)
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
            'unreadNotifications',
            'specialOfferProducts',
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
