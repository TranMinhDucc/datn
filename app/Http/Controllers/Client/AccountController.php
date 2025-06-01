<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function profile()
    {
        return view('client.account.profile');
    }

    public function wallet()
    {
        return view('client.account.wallet');
    }

    public function orders()
    {
        return view('client.account.orders');
    }

    public function password()
    {
        return view('client.account.password');
    }
}
