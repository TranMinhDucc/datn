<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Review;
use App\Models\Setting;
use App\Models\TrafficLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Psy\debug;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy khoảng ngày lọc
        $dateRange = $request->get('daterange');
        if ($dateRange) {
            [$start, $end] = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
            $endDate   = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();
        } else {
            $startDate = now()->subDays(30)->startOfDay();
            $endDate   = now()->endOfDay();
        }

        // 2. Dữ liệu traffic_logs (Referral, Direct, Social)
        $trafficData = TrafficLog::selectRaw('DATE(created_at) as date, source, COUNT(DISTINCT session_id) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'source')
            ->orderBy('date')
            ->get()
            ->groupBy('source');

        $labels = collect();
        $referral = collect();
        $direct = collect();
        $social = collect();

        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $labels->push($day);

            // Direct
            $direct->push(optional($trafficData->get('direct', collect())->firstWhere('date', $day))->total ?? 0);

            // Referral
            $referral->push(optional($trafficData->get('referral', collect())->firstWhere('date', $day))->total ?? 0);

            // Social: gom facebook, zalo, tiktok, social
            $socialCount = 0;
            foreach (['facebook', 'zalo', 'tiktok', 'social'] as $socialSource) {
                if (!empty($trafficData[$socialSource])) {
                    $socialCount += optional($trafficData[$socialSource]->firstWhere('date', $day))->total ?? 0;
                }
            }
            $social->push($socialCount);
        }

        // 3. Dữ liệu hoạt động người dùng
        $active = collect();
        $inactive = collect();

        foreach ($period as $date) {
            $day = $date->format('Y-m-d');

            // User active: login đúng ngày đó
            $activeCount = User::whereDate('last_login_at', $day)->count();

            // User inactive: đã tồn tại trước ngày đó nhưng không login ngày đó
            $inactiveCount = User::whereDate('created_at', '<=', $day)
                ->where(function ($q) use ($day) {
                    $q->whereNull('last_login_at')
                        ->orWhereDate('last_login_at', '<', $day);
                })
                ->count();

            $active->push($activeCount);
            $inactive->push($inactiveCount);
        }

        // 4. Thống kê người dùng theo ngày đăng ký
        $onlineData = User::selectRaw('DATE(last_login_at) as date, COUNT(*) as total')
            ->whereNotNull('last_login_at')
            ->whereBetween('last_login_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $userLabels = $onlineData->pluck('date');
        $userCounts = $onlineData->pluck('total');

        // Người dùng hiện tại (online trong 5 phút gần nhất)
        $currentUsers = User::where('last_login_at', '>=', now()->subMinutes(5))->count();

        // 5. Khách hàng mới trong khoảng thời gian chọn
        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // 6. Thống kê khách hàng mới theo ngày
        $newCustomerData = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $newCustomerLabels = $newCustomerData->pluck('date');
        $newCustomerCounts = $newCustomerData->pluck('total');
        // 5. Thống kê tổng khách hàng tích lũy theo ngày
        $totalCustomersData = [];
        $totalCustomerLabels = [];
        $totalCustomerCounts = [];

        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());

        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $count = User::whereDate('created_at', '<=', $day)->count(); // tổng khách hàng đến ngày đó
            $totalCustomerLabels[] = $day;
            $totalCustomerCounts[] = $count;
        }
        // 7. Thống kê đơn hàng theo trạng thái
        $orderStatusData = Order::select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        // Tổng người dùng toàn hệ thống (không phụ thuộc filter)
        $totalUsersAll = User::count();

        // Tổng đơn hàng toàn hệ thống
        $totalOrdersAll = Order::count();

        // Danh sách trạng thái và nhãn hiển thị
        $orderStatusGroups = [
            'Chờ xử lý' => ['pending', 'confirmed', 'processing', 'ready_for_dispatch'],
            'Đang vận chuyển' => ['shipping'],
            'Đã giao / Hoàn tất' => ['delivered', 'completed'],
            'Đã hủy' => ['cancelled'],
            'Trả hàng / Hoàn tiền' => [
                'return_requested',
                'returning',
                'returned',
                'refund_processing',
                'refunded'
            ],
            'Đổi hàng' => ['exchange_requested', 'exchange_in_progress', 'exchanged'],
            'Đóng / Kết hợp' => ['exchange_and_refund_processing', 'exchanged_and_refunded', 'closed'],
        ];
        $orderLabels = [];
        $orderCounts = [];

        foreach ($orderStatusGroups as $label => $statuses) {
            $count = collect($statuses)->sum(fn($st) => $orderStatusData[$st] ?? 0);
            $orderLabels[] = $label;
            $orderCounts[] = $count;
        }

        // // Tạo labels và counts tương ứng
        // $orderLabels = [];
        // $orderCounts = [];
        // foreach ($orderStatuses as $key => $label) {
        //     $orderLabels[] = $label;
        //     $orderCounts[] = $orderStatusData[$key] ?? 0;
        // }
        // 8. Doanh thu
        $revenue = Order::whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount'); // giả sử cột lưu tổng tiền là total_amount

        // Doanh thu trong khoảng ngày lọc
        $revenueInRange = Order::whereIn('status', ['delivered', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        // 8. Đơn hàng mới nhất (kèm sản phẩm)
        $recentOrders = Order::with(['items' => function ($q) {
            $q->select('id', 'order_id', 'product_name', 'price', 'quantity', 'total_price', 'image_url');
        }])
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();
        // 9. Top sản phẩm (best seller theo số lượng bán)
        $topProducts = OrderItem::select(
            'product_name',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('MAX(image_url) as image_url') // lấy 1 ảnh đại diện
        )
            ->groupBy('product_name')
            ->orderByDesc('total_sold') // best seller theo số lượng
            ->take(5)
            ->get();

        // 10. Biến thể tồn kho thấp
        $lowStockAlert = Setting::where('name', 'low_stock_alert')->value('value') ?? 10;

        $lowStockVariants = ProductVariant::with('product')
            ->where('quantity', '<', $lowStockAlert)
            ->orderBy('quantity', 'asc')
            ->paginate(5, ['*'], 'low_stock_page');


        return view('admin.dashboard', [
            'labels'       => $labels,
            'referral'     => $referral,
            'direct'       => $direct,
            'social'       => $social,
            'active'       => $active,
            'inactive'     => $inactive,
            'userLabels'   => $userLabels,
            'userCounts'   => $userCounts,
            'currentUsers' => $currentUsers,
            'totalCustomerLabels' => $totalCustomerLabels,
            'totalCustomerCounts' => $totalCustomerCounts,
            'newCustomers'       => $newCustomers,
            'newCustomerLabels'  => $newCustomerLabels,
            'newCustomerCounts'  => $newCustomerCounts,
            'startDate'    => $startDate,
            'orderLabels' => $orderLabels,
            'orderCounts' => $orderCounts,
            'endDate'      => $endDate,
            'revenue'         => $revenue,
            'revenueInRange'  => $revenueInRange,
            'recentOrders' => $recentOrders,
            'topProducts'      => $topProducts,
            'lowStockVariants' => $lowStockVariants,
            'totalUsersAll'   => $totalUsersAll,
            'totalOrdersAll'  => $totalOrdersAll,
        ]);
    }


    public function salesReport(Request $request)
    {
        // Gom nhóm theo ngày và nguồn truy cập
        $start = $request->get('start', now()->subDays(30)->toDateString());
        $end = $request->get('end', now()->toDateString());
        $data = TrafficLog::select(
            DB::raw('DATE(visited_at) as date'),
            DB::raw('source'),
            DB::raw('COUNT(DISTINCT session_id) as total')
        )
            ->whereBetween('visited_at', [$start, $end])
            ->groupBy('date', 'source')
            ->orderBy('date')
            ->get()
            ->groupBy('date');
        return response()->json($data);
    }
}
