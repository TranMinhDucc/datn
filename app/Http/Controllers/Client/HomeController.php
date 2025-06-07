<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::with('buttons')
            ->where('status', 1)
            ->orderBy('thu_tu', 'asc') // 🔥 ĐẢM BẢO thứ tự luôn đúng
            ->get();


        return view('client.home', compact('banners'));
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
        return view('client.auth.login');
    }
    public function reset_password()
    {
        return view('client.auth.reset-password');
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
