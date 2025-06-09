<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, có thể dùng paginate

        return view('client.blog.index');
    }
    public function show()
    {
        // Lấy danh sách sản phẩm, có thể dùng paginate

        return view('client.blog.show');
    }
}
