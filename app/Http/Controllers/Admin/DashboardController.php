<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_user = User::count();
        $total_order = Order::count();

        $bestSellingProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.*', OrderItem::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id', 'products.id', 'products.name' /* thêm các cột cần select nếu bạn dùng PostgreSQL */)
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $bestSellingCategories = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories as c', 'products.category_id', '=', 'c.id')
            ->select('c.id', 'c.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('total_user', 'total_order', 'bestSellingProducts', 'bestSellingCategories'));
    }
}
