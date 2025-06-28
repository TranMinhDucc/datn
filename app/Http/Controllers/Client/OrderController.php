<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Lấy các đơn hàng của người dùng
        $orders = Order::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('client.account.dashboard', compact('orders'));
    }
}
