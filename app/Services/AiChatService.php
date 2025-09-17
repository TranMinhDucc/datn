<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AiChatService
{
    protected array $rules;

    public function __construct()
    {
        $this->rules = [
            // ===================== REVENUE & ORDERS =====================
            [
                'keywords' => ['doanh thu hôm nay', 'doanh số hôm nay', 'bán được hôm nay', 'thu nhập hôm nay'],
                'action' => function () {
                    $total = Order::whereDate('created_at', today())->sum('total_amount');
                    $orders = Order::whereDate('created_at', today())->count();
                    $avgOrder = $orders > 0 ? $total / $orders : 0;

                    return "📊 **Doanh thu hôm nay**:\n" .
                        "💰 Tổng: " . number_format($total) . " VND\n" .
                        "🛒 Số đơn: {$orders}\n" .
                        "📈 Trung bình/đơn: " . number_format($avgOrder) . " VND";
                }
            ],

            [
                'keywords' => ['so sánh doanh thu tuần', 'tăng trưởng tuần', 'tuần này vs tuần trước'],
                'action' => function () {
                    $thisWeek = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
                    $lastWeek = Order::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->sum('total_amount');
                    $growth = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek) * 100 : 0;

                    return "📊 **So sánh tuần**:\n" .
                        "📅 Tuần này: " . number_format($thisWeek) . " VND\n" .
                        "📅 Tuần trước: " . number_format($lastWeek) . " VND\n" .
                        "📈 Tăng trưởng: " . ($growth > 0 ? '+' : '') . number_format($growth, 1) . "%";
                }
            ],

            // ===================== PRODUCT ANALYTICS =====================
            [
                'keywords' => ['sản phẩm bán chạy theo mùa', 'xu hướng theo mùa', 'sản phẩm hot mùa'],
                'action' => function () {
                    $season = $this->getCurrentSeason();
                    $products = OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
                        ->whereHas('order', function ($q) {
                            $q->whereDate('created_at', '>=', now()->subMonth());
                        })
                        ->groupBy('product_id')
                        ->orderByDesc('total_sold')
                        ->with('product')
                        ->limit(5)
                        ->get();

                    $list = $products->map(function ($item, $index) {
                        return ($index + 1) . ". {$item->product->name} ({$item->total_sold} sản phẩm)";
                    })->implode("\n");

                    return "🌟 **Top sản phẩm hot {$season}**:\n" . $list;
                }
            ],

            [
                'keywords' => ['size bán chạy nhất', 'size phổ biến', 'thống kê size'],
                'action' => function () {
                    $sizes = OrderItem::whereHas('productVariant')
                        ->with('productVariant')
                        ->get()
                        ->groupBy(function ($item) {
                            return $item->productVariant->size ?? 'N/A';
                        })
                        ->map(function ($group) {
                            return $group->sum('quantity');
                        })
                        ->sortDesc()
                        ->take(5);

                    $list = $sizes->map(function ($quantity, $size) {
                        return "Size {$size}: {$quantity} sản phẩm";
                    })->implode("\n");

                    return "👕 **Thống kê size bán chạy**:\n" . $list;
                }
            ],

            [
                'keywords' => ['màu sắc bán chạy', 'màu phổ biến', 'xu hướng màu'],
                'action' => function () {
                    $colors = OrderItem::whereHas('productVariant')
                        ->with('productVariant')
                        ->get()
                        ->groupBy(function ($item) {
                            return $item->productVariant->color ?? 'N/A';
                        })
                        ->map(function ($group) {
                            return $group->sum('quantity');
                        })
                        ->sortDesc()
                        ->take(5);

                    $list = $colors->map(function ($quantity, $color) {
                        return "🎨 {$color}: {$quantity} sản phẩm";
                    })->implode("\n");

                    return "🌈 **Xu hướng màu sắc**:\n" . $list;
                }
            ],

            // ===================== INVENTORY MANAGEMENT =====================
            [
                'keywords' => ['sản phẩm cần nhập', 'hết hàng', 'tồn kho thấp', 'báo động kho'],
                'action' => function () {
                    $lowStock = Product::where('stock_quantity', '<=', 10)
                        ->orderBy('stock_quantity')
                        ->get();

                    if ($lowStock->isEmpty()) {
                        return "✅ **Kho hàng ổn định** - Không có sản phẩm nào cần nhập gấp";
                    }

                    $urgent = $lowStock->where('stock_quantity', '<=', 5);
                    $warning = $lowStock->where('stock_quantity', '>', 5);

                    $result = "⚠️ **Báo cáo tồn kho**:\n";

                    if ($urgent->count() > 0) {
                        $result .= "🔴 **CẦN NHẬP GẤP** (≤5 sản phẩm):\n";
                        $result .= $urgent->take(5)->map(function ($p, $i) {
                            return ($i + 1) . ". {$p->name} (còn {$p->stock_quantity})";
                        })->implode("\n") . "\n";
                    }

                    if ($warning->count() > 0) {
                        $result .= "🟡 **Cảnh báo** (6-10 sản phẩm):\n";
                        $result .= $warning->take(3)->map(function ($p, $i) {
                            return ($i + 1) . ". {$p->name} (còn {$p->stock_quantity})";
                        })->implode("\n");
                    }

                    return $result;
                }
            ],

            [
                'keywords' => ['dự báo nhu cầu', 'sẽ hết hàng', 'xu hướng tiêu thụ'],
                'action' => function () {
                    $products = Product::withCount(['orderItems as sold_30_days' => function ($q) {
                        $q->whereHas('order', function ($subQ) {
                            $subQ->whereDate('created_at', '>=', now()->subDays(30));
                        });
                    }])->having('sold_30_days', '>', 0)->get();

                    $predictions = $products->map(function ($product) {
                        $avgDaily = $product->sold_30_days / 30;
                        $daysLeft = $avgDaily > 0 ? ceil($product->stock_quantity / $avgDaily) : 999;
                        return [
                            'name' => $product->name,
                            'current_stock' => $product->stock_quantity,
                            'days_left' => $daysLeft,
                            'avg_daily' => round($avgDaily, 1)
                        ];
                    })->sortBy('days_left')->take(5);

                    $result = "🔮 **Dự báo hết hàng (30 ngày tới)**:\n";
                    foreach ($predictions as $i => $pred) {
                        $status = $pred['days_left'] <= 7 ? "🔴" : ($pred['days_left'] <= 15 ? "🟡" : "🟢");
                        $result .= ($i + 1) . ". {$status} {$pred['name']}: {$pred['days_left']} ngày (bán {$pred['avg_daily']}/ngày)\n";
                    }

                    return $result;
                }
            ],

            // ===================== CUSTOMER INSIGHTS =====================
            [
                'keywords' => ['khách hàng mới', 'tăng trưởng khách hàng', 'xu hướng khách'],
                'action' => function () {
                    $newToday = User::whereDate('created_at', today())->count();
                    $newThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
                    $newThisMonth = User::whereMonth('created_at', now()->month)->count();
                    $totalActive = User::whereHas('orders', function ($q) {
                        $q->whereDate('created_at', '>=', now()->subDays(30));
                    })->count();

                    return "👥 **Thống kê khách hàng**:\n" .
                        "🆕 Hôm nay: {$newToday}\n" .
                        "📅 Tuần này: {$newThisWeek}\n" .
                        "📊 Tháng này: {$newThisMonth}\n" .
                        "⚡ Hoạt động (30 ngày): {$totalActive}";
                }
            ],

            [
                'keywords' => ['khách hàng theo độ tuổi', 'phân khúc tuổi', 'demographics'],
                'action' => function () {
                    // Giả sử có trường age hoặc birth_date
                    $ageGroups = User::selectRaw('
                        CASE 
                            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 26 AND 35 THEN "26-35" 
                            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 36 AND 45 THEN "36-45"
                            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) > 45 THEN "45+"
                            ELSE "Không xác định"
                        END as age_group,
                        COUNT(*) as count
                    ')
                        ->whereNotNull('birth_date')
                        ->groupBy('age_group')
                        ->orderByDesc('count')
                        ->get();

                    $result = "👨‍👩‍👧‍👦 **Phân khúc khách hàng theo tuổi**:\n";
                    foreach ($ageGroups as $group) {
                        $result .= "• {$group->age_group} tuổi: {$group->count} khách hàng\n";
                    }

                    return $result;
                }
            ],

            // ===================== GEOGRAPHICAL INSIGHTS =====================
            [
                'keywords' => ['bản đồ bán hàng', 'khu vực tiềm năng', 'thị trường địa lý'],
                'action' => function () {
                    $topCities = Order::join('shipping_addresses', 'orders.address_id', '=', 'shipping_addresses.id')
                        ->selectRaw('shipping_addresses.city, COUNT(*) as order_count, SUM(total_amount) as revenue')
                        ->groupBy('shipping_addresses.city')
                        ->orderByDesc('revenue')
                        ->limit(10)
                        ->get();

                    $result = "🗺️ **Top thị trường theo doanh thu**:\n";
                    foreach ($topCities as $i => $city) {
                        $result .= ($i + 1) . ". {$city->city}: " . number_format($city->revenue) . " VND ({$city->order_count} đơn)\n";
                    }

                    return $result;
                }
            ],

            // ===================== MARKETING INSIGHTS =====================
            [
                'keywords' => ['hiệu quả mã giảm giá', 'coupon analytics', 'khuyến mãi'],
                'action' => function () {
                    $coupons = Coupon::withCount('orders')
                        ->withSum('orders', 'total_amount')
                        ->having('orders_count', '>', 0)
                        ->orderByDesc('orders_sum_total_amount')
                        ->limit(5)
                        ->get();

                    $result = "🎟️ **Top mã giảm giá hiệu quả**:\n";
                    foreach ($coupons as $i => $coupon) {
                        $result .= ($i + 1) . ". {$coupon->code}: {$coupon->orders_count} lần sử dụng - " .
                            number_format($coupon->orders_sum_total_amount) . " VND\n";
                    }

                    return $result ?: "⚠️ Chưa có dữ liệu mã giảm giá";
                }
            ],

            [
                'keywords' => ['giỏ hàng bỏ dở', 'cart abandonment', 'khách bỏ giỏ hàng'],
                'action' => function () {
                    // Logic để tính tỷ lệ giỏ hàng bỏ dở
                    $totalCarts = DB::table('carts')->count();
                    $completedOrders = Order::whereDate('created_at', '>=', now()->subDays(7))->count();
                    $abandonmentRate = $totalCarts > 0 ? (($totalCarts - $completedOrders) / $totalCarts) * 100 : 0;

                    return "🛒 **Phân tích giỏ hàng**:\n" .
                        "📊 Tổng giỏ hàng: {$totalCarts}\n" .
                        "✅ Hoàn thành: {$completedOrders}\n" .
                        "❌ Tỷ lệ bỏ dở: " . number_format($abandonmentRate, 1) . "%\n" .
                        "💡 " . ($abandonmentRate > 70 ? "Cần cải thiện UX checkout" : "Tỷ lệ chấp nhận được");
                }
            ],

            // ===================== ADVANCED ANALYTICS =====================
            [
                'keywords' => ['rfm analysis', 'phân tích rfm', 'khách vip', 'khách rời bỏ'],
                'action' => function () {
                    $customers = User::with(['orders' => function ($q) {
                        $q->where('status', 'completed');
                    }])->get()->map(function ($user) {
                        if ($user->orders->isEmpty()) return null;

                        return [
                            'name' => $user->fullname ?? $user->username,
                            'recency' => $user->orders->max('created_at')->diffInDays(now()),
                            'frequency' => $user->orders->count(),
                            'monetary' => $user->orders->sum('total_amount')
                        ];
                    })->filter();

                    $vip = $customers->where('frequency', '>=', 5)->where('monetary', '>=', 1000000)->take(5);
                    $atRisk = $customers->where('recency', '>', 60)->where('frequency', '>=', 2)->take(5);

                    // === Giải thích RFM ===
                    $result = "📊 **Phân tích RFM** (Recency - Frequency - Monetary):\n";
                    $result .= "- **Recency (R)**: số ngày kể từ lần mua gần nhất\n";
                    $result .= "- **Frequency (F)**: số lần mua hàng\n";
                    $result .= "- **Monetary (M)**: tổng tiền đã chi tiêu\n\n";

                    // === VIP ===
                    if ($vip->count() > 0) {
                        $result .= "💎 **Khách hàng VIP** (≥5 đơn, ≥1M VND):\n";
                        foreach ($vip as $c) {
                            $result .= "• {$c['name']}: {$c['frequency']} đơn, " .
                                number_format($c['monetary']) . " VND\n";
                        }
                    } else {
                        $result .= "💎 Không có khách hàng VIP nào.\n";
                    }

                    // === At Risk ===
                    if ($atRisk->count() > 0) {
                        $result .= "\n⚠️ **Khách có nguy cơ rời bỏ** (>60 ngày không mua):\n";
                        foreach ($atRisk as $c) {
                            $result .= "• {$c['name']}: lần cuối {$c['recency']} ngày trước\n";
                        }
                    } else {
                        $result .= "\n⚠️ Chưa phát hiện khách nào có nguy cơ rời bỏ.";
                    }

                    return $result;
                }
            ],


            [
                'keywords' => ['dự báo doanh thu', 'forecast', 'xu hướng tương lai'],
                'action' => function () {
                    // Tính toán doanh thu 7 ngày gần nhất để dự báo
                    $last7Days = collect();
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i)->format('Y-m-d');
                        $revenue = Order::whereDate('created_at', $date)->sum('total_amount');
                        $last7Days->push($revenue);
                    }

                    $avgDaily = $last7Days->avg();
                    $trend = 0;
                    if ($last7Days->count() > 1 && $last7Days->first() > 0) {
                        $trend = (($last7Days->last() - $last7Days->first()) / $last7Days->first()) * 100;
                    }

                    $forecastWeek = $avgDaily * 7;
                    $forecastMonth = $avgDaily * 30;

                    return "🔮 **Dự báo doanh thu**:\n" .
                        "📈 Trung bình ngày: " . number_format($avgDaily) . " VND\n" .
                        "📊 Xu hướng 7 ngày: " . ($trend > 0 ? '+' : '') . number_format($trend, 1) . "%\n" .
                        "🎯 Dự báo tuần tới: " . number_format($forecastWeek) . " VND\n" .
                        "🎯 Dự báo tháng tới: " . number_format($forecastMonth) . " VND";
                }
            ],

            // ===================== OPERATIONAL INSIGHTS =====================
            // [
            //     'keywords' => ['thời gian giao hàng', 'delivery performance', 'hiệu suất vận chuyển'],
            //     'action' => function () {
            //         $avgDeliveryTime = Order::whereNotNull('delivered_at')
            //             ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, delivered_at)) as avg_days')
            //             ->first()->avg_days;

            //         $onTimeDelivery = Order::whereNotNull('delivered_at')
            //             ->whereRaw('TIMESTAMPDIFF(DAY, created_at, delivered_at) <= expected_delivery_days')
            //             ->count();

            //         $totalDelivered = Order::whereNotNull('delivered_at')->count();
            //         $onTimeRate = $totalDelivered > 0 ? ($onTimeDelivery / $totalDelivered) * 100 : 0;

            //         return "🚚 **Hiệu suất giao hàng**:\n" .
            //             "⏱️ Thời gian trung bình: " . number_format($avgDeliveryTime, 1) . " ngày\n" .
            //             "✅ Tỷ lệ đúng hẹn: " . number_format($onTimeRate, 1) . "%\n" .
            //             "📦 Tổng đơn đã giao: {$totalDelivered}";
            //     }
            // ],

            [
                'keywords' => ['review analysis', 'đánh giá sản phẩm', 'feedback khách hàng'],
                'action' => function () {
                    $avgRating = Review::avg('rating');
                    $totalReviews = Review::count();
                    $ratingDistribution = Review::selectRaw('rating, COUNT(*) as count')
                        ->groupBy('rating')
                        ->orderByDesc('rating')
                        ->get();

                    $result = "⭐ **Phân tích đánh giá**:\n";
                    $result .= "📊 Điểm trung bình: " . number_format($avgRating, 1) . "/5 ({$totalReviews} đánh giá)\n\n";

                    foreach ($ratingDistribution as $dist) {
                        $percentage = ($dist->count / $totalReviews) * 100;
                        $stars = str_repeat('⭐', $dist->rating);
                        $result .= "{$stars} {$dist->rating}: {$dist->count} ({$percentage}%)\n";
                    }

                    return $result;
                }
            ],

            // Thêm các rule cũ đã có...
            [
                'keywords' => ['doanh thu hôm qua', 'doanh số hôm qua'],
                'action' => function () {
                    $total = Order::whereDate('created_at', today()->subDay())->sum('total_amount');
                    return "📊 Doanh thu hôm qua: " . number_format($total) . " VND";
                }
            ],
        ];
    }

    private function getCurrentSeason(): string
    {
        $month = now()->month;

        if ($month >= 3 && $month <= 5) return "mùa xuân";
        if ($month >= 6 && $month <= 8) return "mùa hè";
        if ($month >= 9 && $month <= 11) return "mùa thu";
        return "mùa đông";
    }

    public function process(string $prompt, GeminiService $gemini): string
    {
        $prompt = mb_strtolower($prompt);

        // Kiểm tra context và đưa ra gợi ý thông minh
        if (str_contains($prompt, 'gợi ý') || str_contains($prompt, 'tư vấn') || str_contains($prompt, 'nên làm gì')) {
            return $this->generateSmartSuggestions();
        }

        foreach ($this->rules as $rule) {
            foreach ($rule['keywords'] as $keyword) {
                if (str_contains($prompt, $keyword)) {
                    Log::info("✅ Match rule: {$keyword}");
                    return $rule['action']();
                }
            }
        }

        // Enhanced context cho Gemini
        $context = $this->buildContextForGemini();
        $enhancedPrompt = "Bạn là AI assistant cho website bán quần áo. Context hiện tại: {$context}\n\nCâu hỏi: {$prompt}";

        Log::info("👉 Không khớp rule, gọi Gemini với context");
        return $gemini->ask($enhancedPrompt);
    }

    private function buildContextForGemini(): string
    {
        $todayRevenue = Order::whereDate('created_at', today())->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', today())->count();
        $totalProducts = Product::count();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();

        return "Doanh thu hôm nay: " . number_format($todayRevenue) . " VND, " .
            "{$todayOrders} đơn hàng, {$totalProducts} sản phẩm, {$lowStockCount} sản phẩm sắp hết hàng.";
    }

    private function generateSmartSuggestions(): string
    {
        $suggestions = [
            "💡 **Gợi ý thông minh cho bạn:**",
            "",
            "📊 **Phân tích kinh doanh:**",
            "• 'so sánh doanh thu tuần' - Xem xu hướng tăng trưởng",
            "• 'dự báo doanh thu' - Lập kế hoạch tương lai",
            "• 'rfm analysis' - Tìm khách hàng VIP",
            "",
            "👕 **Quản lý sản phẩm:**",
            "• 'size bán chạy nhất' - Tối ưu kho hàng",
            "• 'màu sắc bán chạy' - Nắm bắt xu hướng",
            "• 'dự báo nhu cầu' - Tránh hết hàng",
            "",
            "🎯 **Marketing & Khách hàng:**",
            "• 'hiệu quả mã giảm giá' - Đo lường campaign",
            "• 'giỏ hàng bỏ dở' - Cải thiện conversion",
            "• 'khách hàng theo độ tuổi' - Phân khúc thị trường"
        ];

        return implode("\n", $suggestions);
    }
}
