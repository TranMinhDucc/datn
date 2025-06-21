<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $products = Product::with('brand')->get(); // 👈 Gán vào biến
        return view('client.cart.index', compact('products')); // 👈 Truyền vào view
    }
}
