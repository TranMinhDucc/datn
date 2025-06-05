<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.home');
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
