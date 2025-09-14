<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    protected array $rules;

    public function __construct()
    {
        $this->rules = [

            [
                'keywords' => ['doanh thu hôm nay', 'doanh số hôm nay', 'bán được hôm nay', 'thu nhập hôm nay'],
                'action' => function () {
                    $total = Order::whereDate('created_at', today())->sum('total_amount');
                    return "📊 Doanh thu hôm nay: " . number_format($total) . " VND";
                }
            ],
            [
                'keywords' => ['doanh thu hôm qua', 'doanh số hôm qua', 'bán được hôm qua', 'thu nhập hôm qua'],
                'action' => function () {
                    $total = Order::whereDate('created_at', today()->subDay())->sum('total_amount');
                    return "📊 Doanh thu hôm qua: " . number_format($total) . " VND";
                }
            ],
            [
                'keywords' => ['doanh thu tuần này', 'doanh số tuần này'],
                'action' => function () {
                    $total = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->sum('total_amount');
                    return "📊 Doanh thu tuần này: " . number_format($total) . " VND";
                }
            ],
            [
                'keywords' => ['doanh thu tháng này', 'doanh số tháng này'],
                'action' => function () {
                    $total = Order::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_amount');
                    return "📊 Doanh thu tháng này: " . number_format($total) . " VND";
                }
            ],

            // ===================== USER =====================
            [
                'keywords' => ['user nạp nhiều nhất', 'khách hàng nạp nhiều nhất', 'ai chi nhiều nhất'],
                'action' => function () {
                    $topUser = User::withSum('orders', 'total_amount')
                        ->orderByDesc('orders_sum_total_amount')
                        ->first();

                    return $topUser
                        ? "👑 User nạp nhiều nhất: {$topUser->fullname} ({$topUser->email}) với " .
                        number_format($topUser->orders_sum_total_amount) . " VND"
                        : "⚠️ Chưa có dữ liệu user nạp.";
                }
            ],
            [
                'keywords' => ['tổng user', 'bao nhiêu user', 'có bao nhiêu khách hàng'],
                'action' => function () {
                    $count = User::count();
                    return "👥 Tổng số user hiện tại: {$count}";
                }
            ],
            [
                'keywords' => ['user mới hôm nay', 'khách hàng mới hôm nay', 'đăng ký hôm nay'],
                'action' => function () {
                    $count = User::whereDate('created_at', today())->count();
                    return "🆕 User mới hôm nay: {$count}";
                }
            ],
            [
                'keywords' => ['top 5 sản phẩm', '5 sản phẩm bán chạy nhất', 'top sản phẩm tuần'],
                'action' => function () {
                    $topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as total')
                        ->groupBy('product_id')
                        ->orderByDesc('total')
                        ->with('product')
                        ->limit(5)
                        ->get();

                    if ($topProducts->isEmpty()) {
                        return "⚠️ Chưa có dữ liệu sản phẩm bán chạy.";
                    }

                    $list = $topProducts->map(function ($item, $index) {
                        return ($index + 1) . ". {$item->product->name} ({$item->total} đơn)";
                    })->implode("\n");

                    return "🔥 Top 5 sản phẩm bán chạy:\n" . $list;
                }
            ],
            [
                'keywords' => ['đơn hàng tháng này', 'bao nhiêu đơn tháng này'],
                'action' => function () {
                    $count = Order::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();
                    return "🛒 Số đơn hàng tháng này: {$count}";
                }
            ],
            [
                'keywords' => ['giá trị trung bình đơn hôm nay', 'trung bình đơn hôm nay'],
                'action' => function () {
                    $avg = Order::whereDate('created_at', today())->avg('total_amount');
                    return "💵 Giá trị trung bình mỗi đơn hôm nay: " . number_format($avg, 0) . " VND";
                }
            ],


            // ===================== ĐƠN HÀNG =====================
            [
                'keywords' => ['đơn hàng hôm nay', 'bao nhiêu đơn hôm nay', 'hôm nay có mấy đơn'],
                'action' => function () {
                    $orders = Order::whereDate('created_at', today())->count();
                    return "🛒 Số đơn hàng hôm nay: {$orders}";
                }
            ],

            // ===================== SẢN PHẨM =====================
            [
                'keywords' => ['sản phẩm bán chạy', 'mặt hàng bán chạy', 'top sản phẩm'],
                'action' => function () {
                    $topProduct = OrderItem::selectRaw('product_id, SUM(quantity) as total')
                        ->groupBy('product_id')
                        ->orderByDesc('total')
                        ->with('product')
                        ->first();

                    return $topProduct
                        ? "🔥 Sản phẩm bán chạy nhất: {$topProduct->product->name} ({$topProduct->total} đơn)"
                        : "⚠️ Chưa có dữ liệu sản phẩm bán chạy.";
                }
            ],
            [
                'keywords' => ['so sánh doanh thu tháng', 'tăng trưởng doanh thu', 'phần trăm tăng trưởng'],
                'action' => function () {
                    $currentMonth = Order::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_amount');

                    $lastMonth = Order::whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year)
                        ->sum('total_amount');

                    $growth = $lastMonth ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;

                    return "📈 So sánh doanh thu: 
                    • Tháng này: " . number_format($currentMonth) . " VND
                    • Tháng trước: " . number_format($lastMonth) . " VND
                    • Tăng trưởng: " . number_format($growth, 2) . "%";
                }
            ],
            [
                'keywords' => ['sản phẩm sắp hết hàng', 'hàng tồn kho ít', 'cần nhập hàng'],
                'action' => function () {
                    $lowStock = Product::where('stock_quantity', '<=', 5)
                        ->orderBy('stock_quantity')
                        ->limit(5)
                        ->get();

                    if ($lowStock->isEmpty()) {
                        return "✅ Tất cả sản phẩm đều đủ số lượng tồn kho";
                    }

                    $list = $lowStock->map(function ($product, $index) {
                        return ($index + 1) . ". {$product->name} (Còn {$product->stock_quantity} sản phẩm)";
                    })->implode("\n");

                    return "⚠️ Các sản phẩm sắp hết hàng:\n" . $list;
                }
            ],
            [
                'keywords' => ['khách hàng thân thiết', 'vip pro', 'khách hàng trung thành'],
                'action' => function () {
                    $loyalCustomers = User::withCount('orders')
                        ->withSum('orders', 'total_amount')
                        ->having('orders_count', '>', 3)
                        ->orderByDesc('orders_sum_total_amount')
                        ->limit(5)
                        ->get();

                    if ($loyalCustomers->isEmpty()) {
                        return "⚠️ Chưa có khách hàng thân thiết";
                    }

                    $list = $loyalCustomers->map(function ($user, $index) {
                        return ($index + 1) . ". {$user->fullname} - {$user->orders_count} đơn - " .
                            number_format($user->orders_sum_total_amount) . " VND";
                    })->implode("\n");

                    return "🎯 Top khách hàng thân thiết:\n" . $list;
                }
            ],
            [
                'keywords' => ['khu vực mua nhiều', 'thành phố mua nhiều', 'tỉnh thành phổ biến'],
                'action' => function () {
                    $popularCities = Order::selectRaw('shipping_city, COUNT(*) as order_count')
                        ->groupBy('shipping_city')
                        ->orderByDesc('order_count')
                        ->limit(5)
                        ->get();

                    $list = $popularCities->map(function ($city, $index) {
                        return ($index + 1) . ". {$city->shipping_city} ({$city->order_count} đơn)";
                    })->implode("\n");

                    return "🗺️ Các khu vực mua hàng nhiều nhất:\n" . $list;
                }
            ]
        ];
    }

    public function process(string $prompt, GeminiService $gemini): string
    {
        $prompt = mb_strtolower($prompt);

        foreach ($this->rules as $rule) {
            foreach ($rule['keywords'] as $keyword) {
                if (str_contains($prompt, $keyword)) {
                    Log::info("✅ Match rule: {$keyword}");
                    return $rule['action']();
                }
            }
        }

        // Nếu không khớp rule nào thì fallback về Gemini
        Log::info("👉 Không khớp rule, gọi Gemini với prompt: " . $prompt);
        return $gemini->ask($prompt);
    }
}
