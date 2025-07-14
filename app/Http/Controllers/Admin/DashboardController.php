<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total_user = User::count();
        $total_order = Order::count();
        $total_active_products = Product::where('status', 1)->count();
      
        $totalReviews = Review::count(); // Tổng số lượt đánh giá
$totalComments = Review::whereNotNull('comment')->where('comment', '!=', '')->count(); // Tổng bình luận
$averageRating = round(Review::avg('rating'), 1); // Đánh giá trung bình (VD: 4.3 sao)

        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $ordersThisMonth = Order::whereBetween('created_at', [$startOfMonth, now()])->count();
        $ordersThisYear = Order::whereBetween('created_at', [$startOfYear, now()])->count();

        $revenueToday = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('subtotal');

        $revenueThisMonth = Order::whereBetween('created_at', [$startOfMonth, now()])
            ->where('payment_status', 'paid')
            ->sum('subtotal');

        $revenueThisYear = Order::whereBetween('created_at', [$startOfYear, now()])
            ->where('payment_status', 'paid')
            ->sum('subtotal');

        // chưa thanh toán
        $unpaidOrders = Order::where('payment_status', 'unpaid')->count();
        // hoàn tiền
        $refundedOrders = Order::where('payment_status', 'refunded')->count();
        // đã hủy
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $bestSellingProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.*', OrderItem::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id', 'products.id', 'products.name')
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

    // --- Tìm kiếm nâng cao ---
$searchOrders = Order::query()->with('user');

if ($request->filled('order_code')) {
    $searchOrders->where('order_code', 'like', '%' . $request->order_code . '%');
}

if ($request->filled('payment_status')) {
    $searchOrders->where('payment_status', $request->payment_status);
}

if ($request->filled('user_id')) {
    $searchOrders->where('user_id', $request->user_id); // <-- tìm theo ID người dùng
}

// Nếu bạn vẫn muốn giữ lại tìm theo tên người dùng (nếu có cột name trong bảng users)
if ($request->filled('user_name')) {
    $searchOrders->whereHas('user', function ($query) use ($request) {
        $query->where('name', 'like', '%' . $request->user_name . '%');
    });
}

if ($request->filled('from_date') && $request->filled('to_date')) {
    $searchOrders->whereBetween('created_at', [
        $request->from_date . ' 00:00:00',
        $request->to_date . ' 23:59:59'
    ]);
} elseif ($request->filled('from_date')) {
    $searchOrders->whereDate('created_at', '>=', $request->from_date);
} elseif ($request->filled('to_date')) {
    $searchOrders->whereDate('created_at', '<=', $request->to_date);
}

$searchResults = $searchOrders->latest()->paginate(7);


        return view('admin.dashboard', compact(
            'total_user',
            'total_order',
            'total_active_products',
            'ordersToday',
            'ordersThisMonth',
            'ordersThisYear',
            'revenueToday',
            'revenueThisMonth',
            'revenueThisYear',
            'unpaidOrders',
            'refundedOrders',
            'cancelledOrders',
            'totalReviews',
            'totalComments',
            'averageRating',
            'bestSellingProducts',
            'bestSellingCategories',
            'searchResults'
        ));
    }
}
