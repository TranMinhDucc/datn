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
use Illuminate\Support\Facades\Schema;

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

        // === TRAFFIC DATA CẢI TIẾN ===
        $trafficData = TrafficLog::selectRaw('DATE(created_at) as date, source, COUNT(DISTINCT session_id) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('source')           // Lọc source null
            ->where('source', '!=', '')        // Lọc source empty  
            ->whereNotNull('session_id')       // Lọc session null
            ->where('session_id', '!=', '')    // Lọc session empty
            ->groupBy('date', 'source')
            ->orderBy('date')
            ->get();

        // Chuẩn hóa source names
        $normalizedData = $trafficData->map(function ($item) {
            $item->source = $this->normalizeTrafficSource($item->source);
            return $item;
        })->groupBy('source');

        $labels = collect();
        $referral = collect();
        $direct = collect();
        $social = collect();

        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $labels->push($day);

            // Direct Traffic - cải tiến
            $directCount = $this->getTrafficCountForDay($normalizedData, ['direct', 'none', 'bookmark'], $day);
            $direct->push($directCount);

            // Referral Traffic - cải tiến  
            $referralCount = $this->getTrafficCountForDay($normalizedData, ['referral', 'website', 'blog'], $day);
            $referral->push($referralCount);

            // Social Media - cải tiến, không trùng lặp
            $socialCount = $this->getTrafficCountForDay($normalizedData, [
                'facebook',
                'instagram',
                'tiktok',
                'youtube',
                'zalo',
                'telegram'
            ], $day);
            $social->push($socialCount);
        }

        // User statistics
        $active = collect();
        $inactive = collect();
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $activeCount = User::whereDate('last_login_at', $day)->count();
            $inactiveCount = User::whereDate('created_at', '<=', $day)
                ->where(function ($q) use ($day) {
                    $q->whereNull('last_login_at')->orWhereDate('last_login_at', '<', $day);
                })->count();
            $active->push($activeCount);
            $inactive->push($inactiveCount);
        }

        $onlineData = User::selectRaw('DATE(last_login_at) as date, COUNT(*) as total')
            ->whereNotNull('last_login_at')->whereBetween('last_login_at', [$startDate, $endDate])
            ->groupBy('date')->orderBy('date')->get();
        $userLabels = $onlineData->pluck('date');
        $userCounts = $onlineData->pluck('total');
        $currentUsers = User::where('last_login_at', '>=', now()->subMinutes(5))->count();
        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $newCustomerData = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])->groupBy('date')->orderBy('date')->get();
        $newCustomerLabels = $newCustomerData->pluck('date');
        $newCustomerCounts = $newCustomerData->pluck('total');

        $totalCustomerLabels = [];
        $totalCustomerCounts = [];
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $count = User::whereDate('created_at', '<=', $day)->count();
            $totalCustomerLabels[] = $day;
            $totalCustomerCounts[] = $count;
        }

        $orderStatusData = Order::select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])->groupBy('status')
            ->pluck('total', 'status')->toArray();
        $totalUsersAll = User::count();
        $totalOrdersAll = Order::count();

        $orderStatusGroups = [
            'Chờ xử lý' => ['pending', 'confirmed', 'processing', 'ready_for_dispatch'],
            'Đang vận chuyển' => ['shipping'],
            'Đã giao / Hoàn tất' => ['delivered', 'completed'],
            'Đã hủy' => ['cancelled'],
            'Trả hàng / Hoàn tiền' => ['return_requested', 'returning', 'returned', 'refund_processing', 'refunded'],
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

        // === DOANH THU NÂNG CAO VỚI PARTIAL HANDLING ===
        $revenueData = $this->calculateAdvancedRevenueWithPartials($startDate, $endDate);
        $revenue = $revenueData['net_revenue'];
        $revenueBreakdown = $revenueData['breakdown'];
        $dailyRevenueData = $this->getDailyRevenueAdvanced($startDate, $endDate);

        $recentOrders = Order::with(['items' => function ($q) {
            $q->select('id', 'order_id', 'product_name', 'price', 'quantity', 'total_price', 'image_url');
        }])->orderBy('created_at', 'desc')->take(7)->get();

        $topProducts = OrderItem::select(
            'product_name',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('MAX(image_url) as image_url')
        )
            ->groupBy('product_name')->orderByDesc('total_sold')->take(5)->get();

        $lowStockAlert = Setting::where('name', 'low_stock_alert')->value('value') ?? 10;
        $lowStockVariants = ProductVariant::with('product')->where('quantity', '<', $lowStockAlert)
            ->orderBy('quantity', 'asc')->paginate(5, ['*'], 'low_stock_page');

        $paymentMethods = Order::select('payment_method', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_method', ['momo', 'cod'])
            ->groupBy('payment_method')->pluck('total', 'payment_method')->toArray();
        $paymentLabels = ['momo' => 'Ví MoMo', 'cod' => 'Thanh toán khi nhận hàng'];
        $paymentCounts = [$paymentMethods['momo'] ?? 0, $paymentMethods['cod'] ?? 0];

        return view('admin.dashboard', [
            'labels' => $labels,
            'referral' => $referral,
            'direct' => $direct,
            'social' => $social,
            'active' => $active,
            'inactive' => $inactive,
            'userLabels' => $userLabels,
            'userCounts' => $userCounts,
            'currentUsers' => $currentUsers,
            'totalCustomerLabels' => $totalCustomerLabels,
            'totalCustomerCounts' => $totalCustomerCounts,
            'newCustomers' => $newCustomers,
            'newCustomerLabels' => $newCustomerLabels,
            'newCustomerCounts' => $newCustomerCounts,
            'startDate' => $startDate,
            'orderLabels' => $orderLabels,
            'orderCounts' => $orderCounts,
            'endDate' => $endDate,
            'revenue' => $revenue,
            'revenueInRange' => $revenue,
            'revenueBreakdown' => $revenueBreakdown,
            'dailyRevenueData' => $dailyRevenueData,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'lowStockVariants' => $lowStockVariants,
            'totalUsersAll' => $totalUsersAll,
            'totalOrdersAll' => $totalOrdersAll,
            'paymentLabels' => $paymentLabels,
            'paymentCounts' => $paymentCounts,
        ]);
    }

    /**
     * TÍNH DOANH THU NÂNG CAO VỚI XỬ LÝ PARTIAL RETURN/EXCHANGE
     * 
     * Công thức chính:
     * DOANH THU = (Đơn gốc hoàn thành) - (Partial Refunds) + (Exchange Adjustments với phí)
     */
    private function calculateAdvancedRevenueWithPartials($startDate, $endDate)
    {
        $orders = Order::with(['items'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $breakdown = [
            'gross_sales' => 0,              // Tổng bán hàng gốc
            'completed_orders' => 0,          // Đơn hoàn thành
            'partial_refunds' => 0,           // Hoàn tiền 1 phần
            'full_refunds' => 0,              // Hoàn tiền toàn bộ
            'partial_exchanges' => 0,         // Đổi hàng 1 phần 
            'exchange_fees' => 0,             // Phụ phí exchange
            'exchange_item_diff' => 0,        // Chênh lệch giá trị hàng
            'cancelled_amount' => 0,          // Đơn hủy
            'pending_amount' => 0,            // Đơn chưa hoàn tất
            'net_revenue' => 0                // Doanh thu thực
        ];

        $totalRevenue = 0;

        foreach ($orders as $order) {
            $orderAnalysis = $this->analyzeOrderWithPartials($order);

            $totalRevenue += $orderAnalysis['net_amount'];

            // Tổng hợp breakdown
            foreach ($orderAnalysis as $key => $value) {
                if (isset($breakdown[$key])) {
                    $breakdown[$key] += $value;
                }
            }
        }

        $breakdown['net_revenue'] = $totalRevenue;

        return [
            'net_revenue' => $totalRevenue,
            'breakdown' => $breakdown
        ];
    }

    /**
     * PHÂN TÍCH CHI TIẾT ORDER VỚI PARTIAL OPERATIONS
     * 
     * Ví dụ: Order #1001 - Tổng: 1,000,000 VND
     * ├─ Áo thun: 200,000 VND (HOÀN TIỀN)
     * ├─ Quần jean: 300,000 VND (GIỮ NGUYÊN)  
     * ├─ Giày sneaker: 400,000 VND (ĐỔI → 500,000 VND + phí 60,000)
     * 
     * Kết quả: 1,000,000 - 200,000 + (500,000 - 400,000) + 60,000 = 960,000 VND
     */
    private function analyzeOrderWithPartials($order)
    {
        $result = [
            'gross_sales' => 0,
            'completed_orders' => 0,
            'partial_refunds' => 0,
            'full_refunds' => 0,
            'partial_exchanges' => 0,
            'exchange_fees' => 0,
            'exchange_item_diff' => 0,
            'cancelled_amount' => 0,
            'pending_amount' => 0,
            'net_amount' => 0
        ];

        // Xử lý dựa trên trạng thái order chính
        switch ($order->status) {
            case 'delivered':
            case 'completed':
                $result = $this->handleCompletedOrderWithPartials($order, $result);
                break;

            case 'refunded':
                $result['gross_sales'] = $order->total_amount;
                $result['full_refunds'] = $order->total_amount;
                $result['net_amount'] = 0;
                break;

            case 'exchanged':
                $result = $this->handleExchangedOrderWithPartials($order, $result);
                break;

            case 'cancelled':
                $result['cancelled_amount'] = $order->total_amount;
                $result['net_amount'] = 0;
                break;

            case 'pending':
            case 'confirmed':
            case 'processing':
            case 'ready_for_dispatch':
            case 'shipping':
                $result['pending_amount'] = $order->total_amount;
                $result['net_amount'] = 0;
                break;

            default:
                $result['net_amount'] = 0;
        }

        return $result;
    }

    /**
     * XỬ LÝ ĐỚN HOÀN THÀNH VỚI CÁC PARTIAL OPERATIONS
     * 
     * Logic:
     * 1. Bắt đầu với tổng tiền đơn hàng
     * 2. Trừ đi các items được hoàn tiền (partial refund)
     * 3. Cộng chênh lệch exchange (new_item_value - old_item_value + fees)
     */
    private function handleCompletedOrderWithPartials($order, $result)
    {
        $result['gross_sales'] = $order->total_amount;
        $result['completed_orders'] = $order->total_amount;
        $netAmount = $order->total_amount;

        // Tìm các return/exchange requests của đơn hàng này
        $partialOperations = $this->getPartialOperations($order);

        foreach ($partialOperations as $operation) {
            switch ($operation['type']) {
                case 'partial_refund':
                    $refundAmount = $operation['amount'];
                    $result['partial_refunds'] += $refundAmount;
                    $netAmount -= $refundAmount;
                    break;

                case 'partial_exchange':
                    $exchangeData = $operation['exchange_data'];
                    $itemDiff = $exchangeData['new_value'] - $exchangeData['old_value'];
                    $fees = $exchangeData['fees'];

                    $result['exchange_item_diff'] += $itemDiff;
                    $result['exchange_fees'] += $fees;
                    $result['partial_exchanges'] += ($itemDiff + $fees);
                    $netAmount += ($itemDiff + $fees);
                    break;

                case 'full_refund':
                    $result['full_refunds'] += $order->total_amount;
                    $netAmount = 0;
                    break;
            }
        }

        $result['net_amount'] = max($netAmount, 0);
        return $result;
    }

    /**
     * LẤY THÔNG TIN CÁC PARTIAL OPERATIONS
     * 
     * Phương pháp 1: Từ bảng return_requests (nếu có)
     * Phương pháp 2: Từ order items metadata  
     * Phương pháp 3: Từ trường note hoặc các trường khác
     */
    private function getPartialOperations($order)
    {
        $operations = [];

        // Phương pháp 1: Sử dụng bảng return_requests (giả định có)
        if ($this->hasReturnRequestsTable()) {
            $operations = $this->getOperationsFromReturnRequests($order);
        }

        // Phương pháp 2: Sử dụng metadata trong order
        if (empty($operations) && !empty($order->note)) {
            $operations = $this->getOperationsFromOrderNote($order);
        }

        // Phương pháp 3: Suy luận từ các đơn exchange liên quan
        if (empty($operations)) {
            $operations = $this->inferOperationsFromExchangeOrders($order);
        }

        return $operations;
    }

    /**
     * LẤY OPERATIONS TỪ BẢNG RETURN_REQUESTS
     * 
     * Giả định cấu trúc:
     * - return_requests: id, order_id, type, status, total_amount
     * - return_request_items: return_request_id, order_item_id, quantity, amount
     */
    private function getOperationsFromReturnRequests($order)
    {
        $operations = [];

        if (!Schema::hasTable('return_requests')) {
            return $operations;
        }

        $returnRequests = DB::table('return_requests')
            ->where('order_id', $order->id)
            ->whereIn('status', ['completed', 'refunded', 'exchanged'])
            ->get();

        foreach ($returnRequests as $request) {
            if ($request->type === 'refund') {
                $operations[] = [
                    'type' => $this->isFullRefund($request, $order) ? 'full_refund' : 'partial_refund',
                    'amount' => $request->total_amount ?? $this->calculateRefundAmount($request),
                    'request_id' => $request->id
                ];
            } elseif ($request->type === 'exchange') {
                $exchangeData = $this->getExchangeDataFromRequest($request, $order);
                $operations[] = [
                    'type' => 'partial_exchange',
                    'exchange_data' => $exchangeData,
                    'request_id' => $request->id
                ];
            }
        }

        return $operations;
    }

    /**
     * TÍNH TOÁN DỮ LIỆU EXCHANGE TỪ REQUEST
     */
    private function getExchangeDataFromRequest($request, $originalOrder)
    {
        // Tìm đơn exchange mới được tạo từ request này
        $exchangeOrder = Order::where('exchange_of_return_request_id', $request->id)
            ->whereIn('status', ['delivered', 'completed', 'exchanged'])
            ->first();

        if (!$exchangeOrder) {
            return ['old_value' => 0, 'new_value' => 0, 'fees' => 0];
        }

        // Lấy giá trị items gốc được exchange
        $oldValue = $this->getOriginalItemsValueFromRequest($request, $originalOrder);

        // Phân tích đơn exchange để tách items vs fees
        $exchangeBreakdown = $this->analyzeExchangeOrderBreakdown($exchangeOrder);

        return [
            'old_value' => $oldValue,
            'new_value' => $exchangeBreakdown['items_value'],
            'fees' => $exchangeBreakdown['fees']
        ];
    }

    /**
     * PHÂN TÍCH BREAKDOWN CỦA EXCHANGE ORDER
     * Phân biệt giá trị hàng hóa thực vs phụ phí
     */
    private function analyzeExchangeOrderBreakdown($exchangeOrder)
    {
        // Phương pháp 1: Sử dụng metadata JSON
        if (!empty($exchangeOrder->note)) {
            $noteData = $this->parseExchangeNote($exchangeOrder->note);
            if ($noteData) {
                return $noteData;
            }
        }

        // Phương pháp 2: Sử dụng các trường phí riêng (nếu có)
        $fees = ($exchangeOrder->shipping_fee ?? 0);

        // Ước tính phí xử lý (2-5% tổng đơn)
        $processingFee = $exchangeOrder->total_amount * 0.03;
        $fees += $processingFee;

        $itemsValue = $exchangeOrder->total_amount - $fees;

        return [
            'items_value' => max($itemsValue, $exchangeOrder->total_amount * 0.8),
            'fees' => min($fees, $exchangeOrder->total_amount * 0.2)
        ];
    }

    /**
     * PARSE THÔNG TIN EXCHANGE TỪ ORDER NOTE
     * 
     * Ví dụ note format:
     * "Exchange: Giày 400k -> 500k, Phí: 60k"
     * "Refund: Áo 200k" 
     */
    private function parseExchangeNote($note)
    {
        // Regex để tìm thông tin exchange
        if (preg_match('/items?:\s*(\d+)k?.*fees?:\s*(\d+)k?/i', $note, $matches)) {
            return [
                'items_value' => (float)$matches[1] * 1000,
                'fees' => (float)$matches[2] * 1000
            ];
        }

        return null;
    }

    /**
     * LẤY OPERATIONS TỪ ORDER NOTE
     */
    private function getOperationsFromOrderNote($order)
    {
        $operations = [];
        $note = $order->note;

        // Parse refund info từ note
        if (preg_match_all('/refund:?\s*(\d+(?:,\d{3})*)/i', $note, $matches)) {
            foreach ($matches[1] as $amount) {
                $refundAmount = (float)str_replace(',', '', $amount);
                $operations[] = [
                    'type' => ($refundAmount >= $order->total_amount) ? 'full_refund' : 'partial_refund',
                    'amount' => $refundAmount
                ];
            }
        }

        // Parse exchange info từ note  
        if (preg_match_all('/exchange:?\s*(\d+(?:,\d{3})*)\s*->\s*(\d+(?:,\d{3})*)/i', $note, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $oldValue = (float)str_replace(',', '', $matches[1][$i]);
                $newValue = (float)str_replace(',', '', $matches[2][$i]);

                $operations[] = [
                    'type' => 'partial_exchange',
                    'exchange_data' => [
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'fees' => 0 // Không tách được phí từ note
                    ]
                ];
            }
        }

        return $operations;
    }

    /**
     * SUY LUẬN OPERATIONS TỪ CÁC ĐƠN EXCHANGE LIÊN QUAN
     */
    private function inferOperationsFromExchangeOrders($order)
    {
        $operations = [];

        // Tìm các đơn exchange có exchange_of_return_request_id
        $exchangeOrders = Order::where('exchange_of_return_request_id', '!=', null)
            ->whereIn('status', ['delivered', 'completed', 'exchanged'])
            ->get();

        foreach ($exchangeOrders as $exchangeOrder) {
            // Giả định có cách liên kết với đơn gốc (có thể qua user_id, timing, etc.)
            if ($this->isExchangeRelatedToOrder($exchangeOrder, $order)) {
                $operations[] = [
                    'type' => 'partial_exchange',
                    'exchange_data' => [
                        'old_value' => $order->total_amount * 0.4, // Ước tính 40% đơn gốc
                        'new_value' => $exchangeOrder->total_amount * 0.8, // 80% đơn exchange là hàng
                        'fees' => $exchangeOrder->total_amount * 0.2 // 20% là phí
                    ]
                ];
            }
        }

        return $operations;
    }

    /**
     * KIỂM TRA ĐƠN EXCHANGE CÓ LIÊN QUAN ĐẾN ĐƠN GỐC KHÔNG
     */
    private function isExchangeRelatedToOrder($exchangeOrder, $originalOrder)
    {
        // Cùng user và trong vòng 30 ngày
        if ($exchangeOrder->user_id === $originalOrder->user_id) {
            $daysDiff = Carbon::parse($exchangeOrder->created_at)
                ->diffInDays(Carbon::parse($originalOrder->created_at));
            return $daysDiff <= 30;
        }

        return false;
    }

    // === HELPER METHODS ===

    private function hasReturnRequestsTable()
    {
        try {
            return Schema::hasTable('return_requests');
        } catch (\Exception $e) {
            return false;
        }
    }

    private function isFullRefund($request, $order)
    {
        $requestAmount = $request->total_amount ?? 0;
        return $requestAmount >= ($order->total_amount * 0.95); // 95% = full refund
    }

    private function calculateRefundAmount($request)
    {
        // Logic tính amount từ return_request_items nếu có
        if (Schema::hasTable('return_request_items')) {
            return DB::table('return_request_items')
                ->join('order_items', 'return_request_items.order_item_id', '=', 'order_items.id')
                ->where('return_request_items.return_request_id', $request->id)
                ->sum(DB::raw('return_request_items.quantity * order_items.price'));
        }

        return $request->amount ?? 0;
    }

    private function getOriginalItemsValueFromRequest($request, $originalOrder)
    {
        // Tính từ return_request_items
        if (Schema::hasTable('return_request_items')) {
            return DB::table('return_request_items')
                ->join('order_items', 'return_request_items.order_item_id', '=', 'order_items.id')
                ->where('return_request_items.return_request_id', $request->id)
                ->sum('order_items.total_price');
        }

        // Fallback: ước tính
        return $originalOrder->total_amount * 0.4;
    }

    private function handleExchangedOrderWithPartials($order, $result)
    {
        if ($order->exchange_of_return_request_id !== null) {
            // Đây là đơn exchange mới
            $breakdown = $this->analyzeExchangeOrderBreakdown($order);
            $result['gross_sales'] = $order->total_amount;
            $result['partial_exchanges'] = $breakdown['items_value'];
            $result['exchange_fees'] = $breakdown['fees'];
            $result['net_amount'] = $order->total_amount;
        } else {
            // Đây là đơn gốc đã exchange - tìm đơn exchange mới
            $result['net_amount'] = 0; // Sẽ được tính ở đơn exchange mới
        }

        return $result;
    }

    /**
     * TÍNH DOANH THU THEO NGÀY VỚI PARTIAL HANDLING
     */
    private function getDailyRevenueAdvanced($startDate, $endDate)
    {
        $dailyRevenue = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dayStart = $currentDate->copy()->startOfDay();
            $dayEnd = $currentDate->copy()->endOfDay();

            $dayRevenueData = $this->calculateAdvancedRevenueWithPartials($dayStart, $dayEnd);

            $dailyRevenue[] = [
                'date' => $currentDate->format('Y-m-d'),
                'revenue' => $dayRevenueData['net_revenue'],
                'gross_sales' => $dayRevenueData['breakdown']['gross_sales'],
                'partial_refunds' => $dayRevenueData['breakdown']['partial_refunds'],
                'exchange_fees' => $dayRevenueData['breakdown']['exchange_fees'],
                'net_revenue' => $dayRevenueData['breakdown']['net_revenue']
            ];

            $currentDate->addDay();
        }

        return $dailyRevenue;
    }

    public function salesReport(Request $request)
    {
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

    /**
     * API endpoint để debug revenue calculation
     */
    public function debugRevenue(Request $request)
    {
        $orderId = $request->get('order_id');
        if (!$orderId) {
            return response()->json(['error' => 'Missing order_id parameter'], 400);
        }

        $order = Order::with(['items'])->find($orderId);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $analysis = $this->analyzeOrderWithPartials($order);
        $partialOperations = $this->getPartialOperations($order);

        return response()->json([
            'order' => [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'exchange_of_return_request_id' => $order->exchange_of_return_request_id
            ],
            'analysis' => $analysis,
            'partial_operations' => $partialOperations,
            'explanation' => $this->explainRevenueCalculation($order, $analysis, $partialOperations)
        ]);
    }

    /**
     * Giải thích chi tiết cách tính doanh thu cho 1 đơn hàng
     */
    private function explainRevenueCalculation($order, $analysis, $partialOperations)
    {
        $steps = [];

        $steps[] = "Bước 1: Đơn hàng gốc #{$order->order_code} - Tổng: " . number_format($order->total_amount) . " VND";

        $currentRevenue = $order->total_amount;

        if (in_array($order->status, ['delivered', 'completed'])) {
            $steps[] = "Bước 2: Đơn đã hoàn thành, bắt đầu với doanh thu = " . number_format($currentRevenue) . " VND";

            foreach ($partialOperations as $operation) {
                switch ($operation['type']) {
                    case 'partial_refund':
                        $amount = $operation['amount'];
                        $currentRevenue -= $amount;
                        $steps[] = "Bước 3: Trừ partial refund: " . number_format($amount) . " VND → Còn: " . number_format($currentRevenue) . " VND";
                        break;

                    case 'partial_exchange':
                        $data = $operation['exchange_data'];
                        $itemDiff = $data['new_value'] - $data['old_value'];
                        $fees = $data['fees'];
                        $total = $itemDiff + $fees;
                        $currentRevenue += $total;
                        $steps[] = "Bước 4: Cộng exchange adjustment:";
                        $steps[] = "  - Chênh lệch hàng: " . number_format($data['new_value']) . " - " . number_format($data['old_value']) . " = " . number_format($itemDiff) . " VND";
                        $steps[] = "  - Phụ phí: " . number_format($fees) . " VND";
                        $steps[] = "  - Tổng điều chỉnh: " . number_format($total) . " VND → Thành: " . number_format($currentRevenue) . " VND";
                        break;
                }
            }
        } else {
            $steps[] = "Bước 2: Đơn có status '{$order->status}' → Doanh thu = 0 VND";
            $currentRevenue = 0;
        }

        $steps[] = "Kết quả cuối: " . number_format($analysis['net_amount']) . " VND";

        return $steps;
    }
    private function normalizeTrafficSource($source)
    {
        $source = strtolower(trim($source));

        // Mapping các variants về tên chuẩn
        $mappings = [
            'direct' => ['direct', 'direct_traffic', 'none', '(direct)', 'bookmark'],
            'facebook' => ['facebook', 'facebook.com', 'fb', 'fb.com', 'm.facebook.com'],
            'instagram' => ['instagram', 'instagram.com', 'ig', 'instagr.am'],
            'tiktok' => ['tiktok', 'tiktok.com', 'tik_tok', 'tt'],
            'zalo' => ['zalo', 'zalo.me', 'chat.zalo.me'],
            'youtube' => ['youtube', 'youtube.com', 'youtu.be', 'yt'],
            'referral' => ['referral', 'ref', 'reference', 'website', 'blog'],
            'google' => ['google', 'google.com', 'google.com.vn', 'www.google.com'],
        ];

        foreach ($mappings as $canonical => $variants) {
            if (in_array($source, $variants)) {
                return $canonical;
            }
        }

        return $source;
    }

    /**
     * Lấy số lượng traffic cho ngày cụ thể
     */
    private function getTrafficCountForDay($normalizedData, $sourceTypes, $day)
    {
        $total = 0;

        foreach ($sourceTypes as $sourceType) {
            if (isset($normalizedData[$sourceType])) {
                $dayData = $normalizedData[$sourceType]->firstWhere('date', $day);
                $total += $dayData->total ?? 0;
            }
        }

        return $total;
    }
}
