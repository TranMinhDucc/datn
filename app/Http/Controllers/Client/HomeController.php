<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.home');
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
