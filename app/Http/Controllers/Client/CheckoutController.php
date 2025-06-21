<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Lấy toàn bộ địa chỉ của người dùng
        $addresses = $user->shippingAddresses;

        // Lấy địa chỉ mặc định
        $defaultAddress = $addresses->firstWhere('is_default', 1);

        return view('client.checkout.index', compact('addresses', 'defaultAddress'));
    }
}
