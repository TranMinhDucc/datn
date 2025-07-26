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

        // $newUsers = User::where('created_at', '>=', Carbon::now()->subMonth())
        //                 ->latest()
        //                 ->take(5)
        //                 ->get();

        $totalNewUsers = User::where('created_at', '>=', Carbon::now()->subMonth())->count();

        $total_order = Order::count();
        $total_active_products = Product::where('status', 1)->count();

        // dd($request);
        // $from_date = isset($request)

       
        $totalReviews = Review::approved()->count();

        $totalComments = Review::approved()->whereNotNull('comment')
        ->where('comment', '!=', '')->count();

        $averageRating = round(Review::approved()->avg('rating'), 1);

        // --- DOANH THU KHOẢNG THỜI GIAN TÌM KIẾM ---
        $revenueInRange = null;

        if ($request->filled('revenue_from') && $request->filled('revenue_to')) {
            $from = Carbon::parse($request->revenue_from)->startOfDay();
            $to = Carbon::parse($request->revenue_to)->endOfDay();

            $revenueInRange = Order::whereBetween('created_at', [$from, $to])
                ->where('status', 'completed')
                ->sum('subtotal');
        }

        // --- XỬ LÝ NGÀY THEO TIMEZONE VN ---
        $timezone = 'Asia/Ho_Chi_Minh';

        $startOfToday = Carbon::today($timezone);
        $endOfToday = Carbon::tomorrow($timezone);

        $startOfMonth = Carbon::now($timezone)->startOfMonth();
        $endOfMonth = Carbon::now($timezone)->endOfMonth();
        $startOfYear = Carbon::now($timezone)->startOfYear();
        $startOfWeek = Carbon::now($timezone)->startOfWeek();

        // --- ĐƠN HÀNG THEO NGÀY /TUẦN/ THÁNG / NĂM ---
        $ordersToday = Order::whereBetween('created_at', [$startOfToday, $endOfToday])->count();
        $ordersThisWeek = Order::whereBetween('created_at', [$startOfWeek, now($timezone)])->count();
        $ordersThisMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $ordersThisYear = Order::whereBetween('created_at', [$startOfYear, now($timezone)])->count();

        // --- DOANH THU THEO NGÀY / THÁNG / NĂM ---
        $revenueToday = Order::whereBetween('created_at', [$startOfToday, $endOfToday])
            ->where('status', 'completed')
            ->sum('subtotal');

        $revenueThisWeek = Order::whereBetween('created_at', [$startOfWeek->startOfDay(), now($timezone)->endOfDay()])
            ->where('status', 'completed')
            ->sum('subtotal');


        $revenueThisMonth = Order::whereBetween('created_at', [$startOfMonth, now($timezone)->endOfDay()])
            ->where('status', 'completed')
            ->sum('subtotal');

        $revenueThisYear = Order::whereBetween('created_at', [$startOfYear, now($timezone)->endOfDay()])
            ->where('status', 'completed')
            ->sum('subtotal');

        // --- THỐNG KÊ THANH TOÁN ---
        $unpaidOrders = Order::where('payment_status', 'unpaid')->count();
        $refundedOrders = Order::where('payment_status', 'refunded')->count();
        $paidOrders = Order::where('payment_status', 'paid')->count();

        // --- SẢN PHẨM BÁN CHẠY ---
        $bestSellingProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.*', OrderItem::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id', 'products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // --- DANH MỤC BÁN CHẠY ---
        $bestSellingCategories = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories as c', 'products.category_id', '=', 'c.id')
            ->select('c.id', 'c.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // --- THỐNG KÊ TRẠNG THÁI ĐƠN HÀNG ---
        $orderStatusChart = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // // --- TÌM KIẾM NÂNG CAO ---
        // $searchOrders = Order::query()->with('user');

        // if ($request->filled('order_code')) {
        //     $searchOrders->where('order_code', 'like', '%' . $request->order_code . '%');
        // }

        // if ($request->filled('payment_status')) {
        //     $searchOrders->where('payment_status', $request->payment_status);
        // }

        // if ($request->filled('user_id')) {
        //     $searchOrders->where('user_id', $request->user_id);
        // }

        // if ($request->filled('user_name')) {
        //     $searchOrders->whereHas('user', function ($query) use ($request) {
        //         $query->where('name', 'like', '%' . $request->user_name . '%');
        //     });
        // }

        // if ($request->filled('from_date') && $request->filled('to_date')) {
        //     $searchOrders->whereBetween('created_at', [
        //         $request->from_date . ' 00:00:00',
        //         $request->to_date . ' 23:59:59'
        //     ]);
        // } elseif ($request->filled('from_date')) {
        //     $searchOrders->whereDate('created_at', '>=', $request->from_date);
        // } elseif ($request->filled('to_date')) {
        //     $searchOrders->whereDate('created_at', '<=', $request->to_date);
        // }

        // $searchResults = $searchOrders->latest()->paginate(7);

        return view('admin.dashboard', compact(
            'total_user',
            'totalNewUsers',
            // 'newUsers',
            'total_order',
            'total_active_products',
            'ordersToday',
            'ordersThisMonth',
            'ordersThisYear',
            'ordersThisWeek',
            'revenueToday',
            'revenueThisWeek',
            'revenueThisMonth',
            'revenueThisYear',
            'unpaidOrders',
            'refundedOrders',
            'paidOrders',
            'totalReviews',
            'totalComments',
            'averageRating',
            'bestSellingProducts',
            'bestSellingCategories',
            // 'searchResults',
            'orderStatusChart',
            'revenueInRange',
        ));
    }
}
