<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, có thể dùng paginate

        return view('client.contact.index');
    }
}
