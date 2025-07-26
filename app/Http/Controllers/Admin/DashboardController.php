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
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();

        if ($request->filled('filterDatetime')) {
            $startOfMonth = Carbon::createFromFormat('m-Y', $request->input('filterDatetime'))->startOfMonth();
            $endOfMonth =  Carbon::createFromFormat('m-Y', $request->input('filterDatetime'))->endOfMonth();
        } else {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
        }

        // Thành viên
        $usersAll = User::count();
        // $usersMonth = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $usersMonth = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $usersWeek = User::whereBetween('created_at', [$startOfWeek, now()])->count();
        $usersToday = User::whereDate('created_at', $today)->count();

        // Đơn hàng đã bán
        $ordersAll = Order::count();
        // $ordersMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $ordersMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $ordersWeek = Order::whereBetween('created_at', [$startOfWeek, now()])->count();
        $ordersToday = Order::whereDate('created_at', $today)->count();

        // Doanh thu
        $revenueAll = Order::sum('subtotal');
        // $revenueMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('subtotal');
        $revenueMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('subtotal');
        $revenueWeek = Order::whereBetween('created_at', [$startOfWeek, now()])->sum('subtotal');
        $revenueToday = Order::whereDate('created_at', $today)->sum('subtotal');

        // Đơn đã thanh toán (thay cho "lợi nhuận")
        $paidAll = Order::where('is_paid', true)->count();
        // $paidMonth = Order::where('is_paid', true)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $paidMonth = Order::where('is_paid', true)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $paidWeek = Order::where('is_paid', true)->whereBetween('created_at', [$startOfWeek, now()])->count();
        $paidToday = Order::where('is_paid', true)->whereDate('created_at', $today)->count();

        // Thêm dữ liệu biểu đồ (orders revenue)
        $selectedMonth = request('monthOrderRevenueChart', now()->month);
        $selectedYear = now()->year;

        $startOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        $ordersThisMonth = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(subtotal) as revenue'),
            DB::raw('SUM(tax_amount) as tax') // bạn có thể thay bằng shipping_fee nếu cần
        )
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Dữ liệu biểu đồ
        $chartLabels = [];
        $chartRevenue = [];
        $chartTax = [];

        foreach ($ordersThisMonth as $day) {
            $chartLabels[] = Carbon::parse($day->date)->format('d/m');
            $chartRevenue[] = $day->revenue;
            $chartTax[] = $day->tax;
        }

        // thống kê theo trạng thái đơn hàng (orders status)
        $selectedMonth = request('monthOrderStatusChart', now()->month);
        $selectedYear = now()->year;

        $startOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        $orderStatuses = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Danh sách trạng thái đầy đủ (để hiển thị đủ dù không có đơn nào)
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Đã hủy',
            'returning' => 'Đang hoàn',
            'returned' => 'Đã hoàn',
        ];

        // Ghép dữ liệu đảm bảo không thiếu trạng thái nào
        $chartStatusLabels = [];
        $chartStatusCounts = [];

        foreach ($statusLabels as $key => $label) {
            $chartStatusLabels[] = $label;
            $chartStatusCounts[] = $orderStatuses[$key] ?? 0;
        }

        return view('admin.dashboard', compact(
            'usersAll',
            'usersMonth',
            'usersWeek',
            'usersToday',
            'ordersAll',
            'ordersMonth',
            'ordersWeek',
            'ordersToday',
            'revenueAll',
            'revenueMonth',
            'revenueWeek',
            'revenueToday',
            'paidAll',
            'paidMonth',
            'paidWeek',
            'paidToday',
            // dữ liệu biểu đồ
            'chartLabels',
            'chartRevenue',
            'chartTax',
            'chartStatusLabels',
            'chartStatusCounts'
        ));

    }
}
