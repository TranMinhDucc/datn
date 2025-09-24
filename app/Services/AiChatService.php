<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
                'keywords' => ['dashboard tổng quan', 'dashboard overview'],
                'action' => function () {
                    $revenue = Order::whereIn('status', ['completed', 'delivered'])->sum('total_amount');
                    $orders  = Order::count();
                    $customers = User::count();
                    $lowStock = ProductVariant::where('quantity', '<', 10)->count();

                    return "📊 **Dashboard Tổng quan**:\n"
                        . "💰 Doanh thu: " . number_format($revenue) . " VND\n"
                        . "🛒 Đơn hàng: {$orders}\n"
                        . "👥 Khách hàng: {$customers}\n"
                        . "⚠️ Biến thể sắp hết: {$lowStock}";
                }
            ],

            [
                'keywords' => ['lợi nhuận theo danh mục', 'profit by category'],
                'action' => function () {
                    $categories = OrderItem::join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                        ->join('products', 'product_variants.product_id', '=', 'products.id')
                        ->join('categories as c', 'products.category_id', '=', 'c.id')
                        ->leftJoin('categories as parent', 'c.parent_id', '=', 'parent.id')
                        ->selectRaw('
                COALESCE(parent.name, c.name) as category_name,
                SUM(order_items.quantity * (order_items.price - products.import_price)) as profit
            ')
                        ->groupBy('category_name')
                        ->orderByDesc('profit')
                        ->get();

                    $result = "💹 **Lợi nhuận theo danh mục (gộp cha-con)**:\n";
                    foreach ($categories as $cat) {
                        $result .= "- {$cat->category_name}: " . number_format($cat->profit) . " VND\n";
                    }
                    return $result;
                }
            ],


            [
                'keywords' => ['so sánh cùng kỳ', 'cùng kỳ năm trước'],
                'action' => function () {
                    $thisMonth = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                        ->sum('total_amount');
                    $lastYearSame = Order::whereBetween('created_at', [
                        now()->subYear()->startOfMonth(),
                        now()->subYear()->endOfMonth()
                    ])->sum('total_amount');
                    $growth = $lastYearSame > 0 ? (($thisMonth - $lastYearSame) / $lastYearSame) * 100 : 0;

                    return "📊 **So sánh cùng kỳ năm trước**:\n"
                        . "📅 Tháng này: " . number_format($thisMonth) . " VND\n"
                        . "📅 Cùng kỳ năm trước: " . number_format($lastYearSame) . " VND\n"
                        . "📈 Tăng trưởng: " . number_format($growth, 1) . "%";
                }
            ],

            [
                'keywords' => ['cash flow analysis', 'phân tích dòng tiền'],
                'action' => function () {
                    $revenue = Order::whereIn('status', ['completed', 'delivered'])->sum('total_amount');
                    $cogs = OrderItem::join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                        ->join('products', 'product_variants.product_id', '=', 'products.id')
                        ->sum(DB::raw('order_items.quantity * products.import_price'));
                    $profit = $revenue - $cogs;

                    return "💵 **Cash Flow Analysis**:\n"
                        . "📥 Doanh thu: " . number_format($revenue) . " VND\n"
                        . "📤 Chi phí hàng bán (COGS): " . number_format($cogs) . " VND\n"
                        . "💰 Lợi nhuận: " . number_format($profit) . " VND";
                }
            ],

            [
                'keywords' => ['slow moving items', 'sản phẩm bán chậm'],
                'action' => function () {
                    $items = Product::withCount(['orderItems as sold_30_days' => function ($q) {
                        $q->whereHas('order', fn($o) => $o->whereDate('created_at', '>=', now()->subDays(30)));
                    }])
                        ->where('stock_quantity', '>', 0)
                        ->orderBy('sold_30_days', 'asc')
                        ->take(5)
                        ->get();

                    $result = "🐢 **Sản phẩm bán chậm (30 ngày qua)**:\n";
                    foreach ($items as $p) {
                        $result .= "- {$p->name}: {$p->sold_30_days} bán ra, tồn kho {$p->stock_quantity}\n";
                    }
                    return $result;
                }
            ],

            [
                'keywords' => ['dead stock', 'dead stock analysis', 'hàng tồn lâu'],
                'action' => function () {
                    $dead = Product::where('stock_quantity', '>', 0)
                        ->whereDoesntHave('orderItems', fn($q) => $q->whereDate('created_at', '>=', now()->subDays(90)))
                        ->take(5)->get();

                    if ($dead->isEmpty()) return "✅ Không có hàng tồn kho lâu ngày";

                    $result = "☠️ **Dead Stock (90 ngày không bán được)**:\n";
                    foreach ($dead as $p) {
                        $result .= "- {$p->name} (tồn {$p->stock_quantity})\n";
                    }
                    return $result;
                }
            ],

            [
                'keywords' => ['customer lifetime value', 'clv'],
                'action' => function () {
                    $avgOrderValue = Order::avg('total_amount');
                    $purchaseFreq = Order::select('user_id')->distinct()->count() > 0
                        ? Order::count() / Order::select('user_id')->distinct()->count()
                        : 0;
                    $customerValue = $avgOrderValue * $purchaseFreq;

                    return "👥 **Customer Lifetime Value (ước tính)**:\n"
                        . "🛒 Giá trị đơn hàng TB: " . number_format($avgOrderValue) . " VND\n"
                        . "🔁 Tần suất mua TB: " . number_format($purchaseFreq, 2) . " lần/khách\n"
                        . "💎 CLV: " . number_format($customerValue) . " VND";
                }
            ],

            [
                'keywords' => ['inventory turnover', 'vòng quay hàng tồn kho'],
                'action' => function () {
                    // COGS (giá vốn) = tổng số lượng bán * import_price
                    $cogs = \App\Models\OrderItem::join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                        ->join('products', 'product_variants.product_id', '=', 'products.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->whereIn('orders.status', ['completed', 'delivered']) // chỉ đơn hoàn tất
                        ->whereDate('orders.created_at', '>=', now()->startOfMonth())
                        ->sum(DB::raw('order_items.quantity * products.import_price'));

                    // Giá trị tồn kho hiện tại
                    $inventoryValue = \App\Models\ProductVariant::join('products', 'product_variants.product_id', '=', 'products.id')
                        ->sum(DB::raw('product_variants.quantity * products.import_price'));

                    // Trung bình tồn kho (giả định tháng này ~ trung bình = hiện tại/2)
                    $avgInventory = $inventoryValue / 2;

                    $turnover = $avgInventory > 0 ? round($cogs / $avgInventory, 2) : 0;

                    return "📦 **Inventory Turnover (tháng này)**:\n"
                        . "• COGS: " . number_format($cogs) . " VND\n"
                        . "• Giá trị tồn kho hiện tại: " . number_format($inventoryValue) . " VND\n"
                        . "• Tồn kho trung bình (ước tính): " . number_format($avgInventory) . " VND\n"
                        . "➡️ Vòng quay hàng tồn kho: {$turnover} lần/tháng";
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
                'keywords' => ['rfm analysis', 'phân tích rfm', 'khách vip', 'khách rời bỏ', 'churn', 'churn risk'],
                'action' => function () {
                    $customers = User::with(['orders' => function ($q) {
                        $q->whereIn('status', ['completed', 'delivered']);
                    }])->get()->map(function ($user) {
                        if ($user->orders->isEmpty()) return null;

                        return [
                            'name'      => $user->fullname ?? $user->username,
                            'recency'   => $user->orders->max('created_at')->diffInDays(now()),
                            'frequency' => $user->orders->count(),
                            'monetary'  => $user->orders->sum('total_amount'),
                            'last_order' => $user->orders->max('created_at')->format('Y-m-d')
                        ];
                    })->filter();

                    // VIP customers
                    $vip = $customers->where('frequency', '>=', 5)->where('monetary', '>=', 2000000)->take(5);

                    // At-risk customers (khách có nguy cơ rời bỏ)
                    $atRisk = $customers->where('recency', '>', 60)->sortByDesc('recency')->take(5);

                    // Lost customers (không mua > 180 ngày)
                    $lost = $customers->where('recency', '>', 180)->sortByDesc('recency')->take(5);

                    $result = "📊 **Phân tích khách hàng theo RFM & Churn Risk**:\n";
                    $result .= "- Recency: số ngày từ lần mua cuối\n";
                    $result .= "- Frequency: số lần mua\n";
                    $result .= "- Monetary: tổng chi tiêu\n\n";

                    // VIP
                    if ($vip->count()) {
                        $result .= "💎 **VIP Customers**:\n";
                        foreach ($vip as $c) {
                            $result .= "• {$c['name']}: {$c['frequency']} đơn, " . number_format($c['monetary']) . " VND\n";
                        }
                    }

                    // At Risk
                    if ($atRisk->count()) {
                        $result .= "\n⚠️ **Nguy cơ rời bỏ (>60 ngày không mua)**:\n";
                        foreach ($atRisk as $c) {
                            $result .= "• {$c['name']} - lần cuối {$c['recency']} ngày trước ({$c['last_order']})\n";
                        }
                    }

                    // Lost
                    if ($lost->count()) {
                        $result .= "\n❌ **Khách hàng rời bỏ (>180 ngày)**:\n";
                        foreach ($lost as $c) {
                            $result .= "• {$c['name']} - không mua suốt {$c['recency']} ngày\n";
                        }
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

    // public function process(string $prompt, GeminiService $gemini): string
    // {
    //     $prompt = mb_strtolower($prompt);

    //     // Kiểm tra context và đưa ra gợi ý thông minh
    //     if (str_contains($prompt, 'gợi ý') || str_contains($prompt, 'tư vấn') || str_contains($prompt, 'nên làm gì')) {
    //         return $this->generateSmartSuggestions();
    //     }

    //     foreach ($this->rules as $rule) {
    //         foreach ($rule['keywords'] as $keyword) {
    //             if (str_contains($prompt, $keyword)) {
    //                 Log::info("✅ Match rule: {$keyword}");
    //                 return $rule['action']();
    //             }
    //         }
    //     }

    //     // Enhanced context cho Gemini
    //     $context = $this->buildContextForGemini();
    //     $enhancedPrompt = "Bạn là AI assistant cho website bán quần áo. Context hiện tại: {$context}\n\nCâu hỏi: {$prompt}";

    //     Log::info("👉 Không khớp rule, gọi Gemini với context");
    //     return $gemini->ask($enhancedPrompt);
    // }
    public function process(string $prompt, GeminiService $gemini): string
    {
        $prompt = mb_strtolower($prompt);

        // Nếu có match rule cố định → chạy ngay
        foreach ($this->rules as $rule) {
            foreach ($rule['keywords'] as $keyword) {
                if (str_contains($prompt, $keyword)) {
                    Log::info("✅ Match rule: {$keyword}");
                    return $rule['action']();
                }
            }
        }

        // Nếu chứa từ khóa “gợi ý” → trả lời suggestions
        if (str_contains($prompt, 'gợi ý') || str_contains($prompt, 'tư vấn') || str_contains($prompt, 'nên làm gì')) {
            return $this->generateSmartSuggestions();
        }

        // 👉 Nếu không match rule nào → cho Gemini tự viết SQL & chạy sandbox
        try {
            return $this->executeAiQuery($prompt, $gemini);
        } catch (\Throwable $e) {
            Log::error("❌ Lỗi process AI query: " . $e->getMessage());
            return "⚠️ Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.";
        }
    }


    private function buildContextForGemini(): string
{
    $schema = Storage::exists('schema.json')
        ? Storage::get('schema.json')
        : '{}';

    // === Thống kê cơ bản ===
    $todayRevenue   = Order::whereDate('created_at', today())->sum('total_amount') ?? 0;
    $todayOrders    = Order::whereDate('created_at', today())->count();
    $todayProducts  = OrderItem::whereHas(
        'order',
        fn($q) => $q->whereDate('created_at', today())
    )->sum('quantity') ?? 0;

    $totalProducts  = Product::count();
    $lowStockAlert  = (int) (Setting::where('name', 'low_stock_alert')->value('value') ?? 10);
    $lowStockVariantCount = ProductVariant::where('quantity', '<', $lowStockAlert)->count();

    // === Thống kê khách hàng ===
    $active30d = User::whereHas(
        'orders',
        fn($q) => $q->whereDate('created_at', '>=', now()->subDays(30))
    )->count();

    $inactive60d = User::whereDoesntHave(
        'orders',
        fn($q) => $q->whereDate('created_at', '>=', now()->subDays(60))
    )->count();

    $avgCLV = Order::avg('total_amount') ?? 0;

    return <<<PROMPT
🚨🚨🚨 EMERGENCY OVERRIDE - CRITICAL SYSTEM FAILURE PREVENTION 🚨🚨🚨

⚠️ DATABASE ENGINE: MySQL 8.0.30 (NOT SQLite!)
⚠️ IF YOU USE SQLite SYNTAX, THE ENTIRE SYSTEM WILL CRASH!

🔴 FORBIDDEN - WILL CAUSE SYSTEM CRASH:
❌ DATE('now', '-7 days') 
❌ DATE('now', 'start of month')
❌ DATETIME('now')
❌ Any function with 'now' in single quotes
❌ Column name 'order_date' (DOES NOT EXIST)
❌ Column name 'order_total' (DOES NOT EXIST)

✅ MANDATORY MySQL FUNCTIONS ONLY:
✅ CURDATE() 
✅ NOW()
✅ DATE_SUB(CURDATE(), INTERVAL 7 DAY)
✅ DATE_SUB(NOW(), INTERVAL 30 DAY)

🔴 ACTUAL COLUMN NAMES (DO NOT CHANGE):
- orders table: id, user_id, total_amount, status, created_at
- products table: id, name, price, created_at  
- users table: id, name, email, created_at

⚠️ CRITICAL EXAMPLES - COPY EXACTLY:

When user asks "doanh thu 7 ngày":
```sql
SELECT SUM(total_amount) AS doanh_thu
FROM orders 
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
```

When user asks "số đơn hàng":  
```sql
SELECT COUNT(*) AS so_don_hang
FROM orders
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
```

When user asks both:
```sql
SELECT 
    SUM(total_amount) AS doanh_thu,
    COUNT(*) AS so_don_hang
FROM orders
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
```

🚨 EMERGENCY PROTOCOL:
1. NEVER write DATE('now', anything)
2. NEVER use order_date or order_total
3. ALWAYS use created_at and total_amount
4. ALWAYS use DATE_SUB(CURDATE(), INTERVAL X DAY)
5. NO semicolon before LIMIT

🎯 YOUR ROLE: MySQL Database Analyst for Vietnamese fashion e-commerce
📊 CURRENT DATA (" . now()->format('d/m/Y H:i') . "):
💰 Today Revenue: " . number_format($todayRevenue) . " VND
📦 Today Orders: {$todayOrders}
👕 Products Sold: {$todayProducts}
📋 Total Products: {$totalProducts}
⚠️ Low Stock: {$lowStockVariantCount} variants (< {$lowStockAlert})

👥 CUSTOMERS:
✅ Active (30d): {$active30d}
⏰ Inactive (60d+): {$inactive60d}
💎 AOV: " . number_format($avgCLV) . " VND

📋 DATABASE SCHEMA:
{$schema}

🎯 TASK: Generate ONLY MySQL-compatible SQL queries. Provide business insights.

⚠️ FINAL WARNING: If you generate SQLite syntax, the system will crash and all data will be lost!

RESPOND ONLY WITH MYSQL QUERIES USING:
- created_at (not order_date)
- total_amount (not order_total)  
- DATE_SUB(CURDATE(), INTERVAL X DAY)
PROMPT;
}


    private function executeAiQuery(string $prompt, GeminiService $gemini)
    {
        // Gọi Gemini sinh SQL
        $aiResponse = $gemini->ask("Bạn là AI SQL Assistant. Chỉ trả về câu lệnh SQL SELECT hợp lệ, không thêm giải thích.\n\nCâu hỏi: {$prompt}");

        // --- Lấy phần SQL trong code block nếu có ---
        $sql = $aiResponse;
        if (preg_match('/```sql(.*?)```/s', $aiResponse, $matches)) {
            $sql = trim($matches[1]);
        }

        // --- Nếu không có code block: cố gắng lấy từ SELECT trở đi ---
        if (!str_starts_with(strtoupper(trim($sql)), 'SELECT')) {
            if (preg_match('/(SELECT.*)/is', $aiResponse, $matches)) {
                $sql = trim($matches[1] ?? '');
            }
        }

        // --- Validate: chỉ cho phép SELECT ---
        if (empty($sql) || !str_starts_with(strtoupper($sql), 'SELECT')) {
            return "⚠️ Truy vấn không hợp lệ (chỉ SELECT).";
        }

        // --- Thêm LIMIT mặc định nếu chưa có ---
        if (!preg_match('/LIMIT\s+\d+/i', $sql)) {
            $sql .= " LIMIT 100";
        }

        try {
            // Chạy query trên connection sandbox (mysql_ai)
            $results = DB::connection('mysql_ai')->select($sql);

            if (empty($results)) {
                return "📭 Không có dữ liệu phù hợp.";
            }

            // Format kết quả cho dễ đọc
            $output = "✅ Kết quả truy vấn:\n";
            foreach ($results as $row) {
                $output .= "- " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
            }

            return $output;
        } catch (\Exception $e) {
            return "❌ Lỗi khi chạy SQL: " . $e->getMessage() . "\n\nSQL: {$sql}";
        }
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
