<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use App\Models\ProductDetail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariantOption;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

use App\Models\SearchHistory;
use App\Models\Setting;

class ProductController extends Controller
{
    public function index()
    {
        return view('client.products.index');
    }


    public function show(string $slug)
    {
        // 1) Load product + VARIANTS ĐANG BẬT
        $product = Product::with([
            'variants' => function ($q) {
                $q->where('is_active', 1)
                    ->with(['options.attribute', 'options.value']);
            },
            'category',
            'brand',
            'tags',
            'images',
            'productDetails',
        ])->where('slug', $slug)->firstOrFail();

        // 2) Nhóm chi tiết
        $groupedDetails = $product->productDetails
            ? collect($product->productDetails)->groupBy('group_name')
            : collect([]);

        // 3) Ảnh phụ (nếu bạn có quan hệ images rồi thì có thể filter qua quan hệ)
        $productImages = ProductImage::where('product_id', $product->id)
            ->where('is_thumbnail', false)
            ->get();

        // 4) GOM THUỘC TÍNH từ các variant đã được lọc (is_active=1)
        $attributeGroups = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->options as $option) {
                $attrName = $option->attribute->name;
                $val      = $option->value->value;

                $attributeGroups[$attrName] ??= [];
                if (!in_array($val, $attributeGroups[$attrName], true)) {
                    $attributeGroups[$attrName][] = $val;
                }
            }
        }

        // 5) Đánh giá (giữ nguyên logic của bạn)
        $reviews = Review::select('reviews.*', 'order_items.variant_values', 'order_items.price')
            ->leftJoin('order_items', 'reviews.order_item_id', '=', 'order_items.id')
            ->where('reviews.product_id', $product->id)
            ->where('reviews.approved', true)
            ->with('user')
            ->latest()
            ->get();

        $rating_summary = [
            'avg_rating' => null,
            'total_rating' => $reviews->count(),
            '5_star_percent' => 0,
            '4_star_percent' => 0,
            '3_star_percent' => 0,
            '2_star_percent' => 0,
            '1_star_percent' => 0,
        ];

        if ($rating_summary['total_rating'] > 0) {
            $star_1 = $star_2 = $star_3 = $star_4 = $star_5 = 0;
            foreach ($reviews as $review) {
                ${"star_{$review->rating}"}++;
            }
            $total = $rating_summary['total_rating'];
            $rating_summary['1_star_percent'] = round($star_1 / $total * 100);
            $rating_summary['2_star_percent'] = round($star_2 / $total * 100);
            $rating_summary['3_star_percent'] = round($star_3 / $total * 100);
            $rating_summary['4_star_percent'] = round($star_4 / $total * 100);
            $rating_summary['5_star_percent'] = round($star_5 / $total * 100);
            $rating_summary['avg_rating'] = ($star_5 * 5 + $star_4 * 4 + $star_3 * 3 + $star_2 * 2 + $star_1) / $total;
        }

        // 6) Danh sách VARIANTS cho JS (dùng cùng filter is_active=1)
        $variants = ProductVariant::where('product_id', $product->id)
            ->where('is_active', 1)
            ->with(['options.attribute', 'options.value'])
            ->get();

        $formattedVariants = $variants->map(function ($variant) {
            $attrs = [];
            foreach ($variant->options as $opt) {
                $attrs[$opt->attribute->name] = $opt->value->value;
            }
            return [
                'id'        => $variant->id,
                'attributes' => $attrs,
                'price'     => $variant->price,
                'quantity'  => $variant->available_quantity, // accessor của bạn
                'weight'    => $variant->weight,
                'length'    => $variant->length,
                'width'     => $variant->width,
                'height'    => $variant->height,
            ];
        });

        // 7) Sản phẩm liên quan + gợi ý
        // 7) Sản phẩm liên quan + gợi ý
        $product->related_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->withAvg(['reviews' => function ($q) {
                $q->where('approved', true);
            }], 'rating')
            ->take(4)
            ->get()
            ->map(function ($p) {
                $p->reviews_avg_rating = round($p->reviews_avg_rating, 1); // làm tròn 1 số thập phân
                return $p;
            });



        $recommendedProducts = $this->getRecommendedProducts();
        // đã có $product trong show()
        $sizeChart = $product->size_chart;
        $returnPolicy = Setting::where('name', 'return_policy')->value('value');
        // 8) Trả view (chỉ truyền $attributeGroups cho UI chọn biến thể)
        $minPrice = $variants->min('price');
        $maxPrice = $variants->max('price');

        return view('client.products.show', compact(
            'product',
            'attributeGroups',
            'productImages',
            'reviews',
            'rating_summary',
            'groupedDetails',
            'recommendedProducts',
            'sizeChart',
            'returnPolicy',
            'minPrice',
            'maxPrice'
        ))->with('variants', $formattedVariants);
    }

    public function getVariantInfo(Request $request)
    {
        $productId = $request->input('product_id');
        $attributes = $request->input('attributes', []);

        Log::info("productId", [$productId]);
        Log::info("attributes", [$attributes]);

        $variantQuery = ProductVariant::where('product_id', $productId);

        foreach ($attributes as $attributeId => $valueId) {
            $variantQuery->whereHas('variantOptions', function ($q) use ($attributeId, $valueId) {
                $q->where('attribute_id', $attributeId)
                    ->where('value_id', $valueId);
            });
        }

        $variant = $variantQuery->first();

        if ($variant) {
            return response()->json([
                'status' => 'ok',
                'price' => $variant->price,
                'quantity' => $variant->available_quantity, // sử dụng accessor    
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Product::query()
            ->where('is_active', 1)
            ->with(['brand', 'variants']);

        // Tìm kiếm đúng từ
        if ($keyword) {
            $keyword = strtolower(trim($keyword));
            $regex = '[[:<:]]' . $keyword . '[[:>:]]';
            $query->whereRaw("CONCAT(' ', LOWER(name), ' ') LIKE ?", ['% ' . strtolower($keyword) . ' %']);
        }
        // Ghi lại lịch sử tìm kiếm
        SearchHistory::updateOrCreate(
            ['keyword' => $keyword],
            ['count' => DB::raw('count + 1')]
        );
        $products = $query->latest()->paginate(8)->withQueryString();

        $recommendedProducts = $this->getRecommendedProducts();

        return view('client.products.search', compact('products', 'keyword', 'recommendedProducts'));
    }
    public function filter(Request $request)
    {
        $query = Product::with(['brand', 'images', 'variants.options.attribute', 'variants.options.value'])
            ->where('is_active', 1);
        $keyword = $request->input('keyword');
        // 🔍 Từ khóa
        if ($request->filled('keyword')) {
            $keyword = strtolower(trim($request->input('keyword')));
            $regex = '[[:<:]]' . $keyword . '[[:>:]]';

            $query->where(function ($q) use ($keyword) {
                // Tìm chính xác từ trong tên sản phẩm
                $q->whereRaw("CONCAT(' ', LOWER(name), ' ') LIKE ?", ['% ' . $keyword . ' %']);

                // Gợi ý lọc theo danh mục nếu nhận ra keyword phù hợp
                if (str_contains($keyword, 'áo')) {
                    $q->where('category_id', 1); // Danh mục Áo
                } elseif (str_contains($keyword, 'quần')) {
                    $q->where('category_id', 2); // Danh mục Quần
                }
                // Bạn có thể thêm các điều kiện khác ở đây nếu cần
            });
        }
        // Ghi lại từ khóa tìm kiếm
        if ($keyword) {
            SearchHistory::updateOrCreate(
                ['keyword' => $keyword],
                ['count' => DB::raw('count + 1')]
            );
        }
        // 💰 Giá
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = floatval($request->input('min_price'));
            $maxPrice = floatval($request->input('max_price'));
            if ($minPrice <= $maxPrice) {
                $query->whereBetween('sale_price', [$minPrice, $maxPrice]);
            }
        }

        // 🏷 Danh mục (bảo vệ không lọc nếu không có checkbox nào được chọn)
        $categoryIds = $request->input('category', []); // luôn trả array (nếu không có -> rỗng)
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // 🏢 Thương hiệu
        if ($request->filled('brand')) {
            $query->whereIn('brand_id', $request->brand);
        }

        // 🎨 Màu sắc (lọc theo value_id)
        if ($request->filled('color')) {
            $query->whereHas('variants.options', function ($q) use ($request) {
                $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Màu sắc'))
                    ->whereIn('value_id', $request->color);
            });
        }

        // 👕 Kích cỡ (lọc theo value_id)
        if ($request->filled('size')) {
            $query->whereHas('variants.options', function ($q) use ($request) {
                $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Size'))
                    ->whereIn('value_id', $request->size);
            });
        }
        // 👤 Giới tính (lọc theo value_id)
        if ($request->filled('gender')) {
            $query->whereHas('variants.options', function ($q) use ($request) {
                $q->whereHas('attribute', function ($attr) {
                    $attr->where('name', 'Giới tính');
                })->where('value_id', $request->gender);
            });
        }
        // ✅ Tình trạng còn hàng / hết hàng
        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->where(function ($q) {
                    $q->whereHas('variants', fn($q) => $q->where('stock_quantity', '>', 0))
                        ->orWhere(function ($q2) {
                            $q2->doesntHave('variants')
                                ->where('stock_quantity', '>', 0); // sản phẩm không có variant nhưng vẫn còn hàng
                        });
                });
            } elseif ($request->availability === 'out_of_stock') {
                $query->where(function ($q) {
                    $q->whereHas('variants', fn($q) => $q->where('stock_quantity', '<=', 0))
                        ->orWhere(function ($q2) {
                            $q2->doesntHave('variants')
                                ->where('stock_quantity', '<=', 0); // sản phẩm không có variant và cũng hết hàng
                        });
                });
            }
        }

        // ⚙️ Sắp xếp theo yêu cầu
        switch ($request->input('sort_by')) {
            case 'price_desc':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'alpha_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'alpha_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popularity':
                $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                break;
            case 'best_selling':
                $query->orderBy('sale_times', 'desc'); // giả sử bạn có trường sale_times
                break;
            case 'discount_desc':
                $query->orderByRaw('(base_price - sale_price) DESC');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }


        $products = $query->paginate(8)->withQueryString();

        // Dữ liệu hiển thị
        $categories = Category::all();
        $brands = Brand::all();

        // 🎨 Màu sắc
        $colors = AttributeValue::whereHas('variantOptions.attribute', fn($q) => $q->where('name', 'Màu sắc'))
            ->select('attribute_values.id', 'attribute_values.value')
            ->distinct()
            ->get();

        // 👕 Kích cỡ
        $sizes = [];

        $options = ProductVariantOption::with(['attribute', 'value'])
            ->whereHas('attribute', fn($q) => $q->where('name', 'Size'))
            ->get();

        $genders = AttributeValue::whereHas('variantOptions.attribute', fn($q) => $q->where('name', 'Giới tính'))
            ->select('attribute_values.id', 'attribute_values.value')
            ->distinct()
            ->get();
        foreach ($options as $option) {
            $value = $option->value;

            if (!collect($sizes)->contains('id', $value->id)) {
                $sizes[] = $value;
            }
        }
        $wishlistProductIds = [];

        if (auth()->check()) {
            $wishlistProductIds = \App\Models\Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }
        return view('client.products.filter-sidebar', compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'genders',
            'wishlistProductIds',
        ));
    }

    private function getRecommendedProducts($limit = 6)
    {
        return Product::where('is_active', 1)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }
    public function suggest(Request $request)
    {
        $keyword = strtolower(trim($request->input('keyword')));

        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([]);
        }

        $products = Product::where('is_active', 1)
            ->whereRaw("LOWER(name) LIKE ?", ["%$keyword%"])
            ->select('id', 'name', 'slug', 'image', 'sale_price', 'base_price')
            ->limit(6)
            ->get();

        return response()->json($products);
    }
}
