<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, có thể dùng paginate
        
        return view('client.products.index');
    }

    public function show($id)
    {
         return view('client.products.show');
    }
}
