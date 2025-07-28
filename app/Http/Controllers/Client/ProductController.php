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

class ProductController extends Controller
{
    public function index()
    {
        return view('client.products.index');
    }

    public function show(string $slug)
    {

        $product = Product::with([
            'variants.options.attribute',
            'variants.options.value',
            'category',
            'brand',
            'tags',
            'images',
            'productDetails', // thay vì 'details'
        ])->where('slug', $slug)->firstOrFail();
        $groupedDetails = $product->productDetails
            ? collect($product->productDetails)->groupBy('group_name')
            : collect([]);

        $productImages = ProductImage::where('product_id', $product->id)
            ->where('is_thumbnail', false)
            ->get();

        // Gom các thuộc tính
        $attributeGroups = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->options as $option) {
                $attrName = $option->attribute->name;
                $value = $option->value->value;

                if (!isset($attributeGroups[$attrName])) {
                    $attributeGroups[$attrName] = [];
                }

                if (!in_array($value, $attributeGroups[$attrName])) {
                    $attributeGroups[$attrName][] = $value;
                }
            }
        }

        // Lấy đánh giá
        $reviews = Review::where('product_id', $product->id)
            ->where('approved', true)
            ->with('user')
            ->latest()
            ->get();

        $rating_summary = [
            'avg_rating' => null,
            'total_rating' => count($reviews),
            '5_star_percent' => 0,
            '4_star_percent' => 0,
            '3_star_percent' => 0,
            '2_star_percent' => 0,
            '1_star_percent' => 0,
        ];

        if ($rating_summary['total_rating'] > 0) {
            $star_5 = $star_4 = $star_3 = $star_2 = $star_1 = 0;

            foreach ($reviews as $review) {
                switch ($review->rating) {
                    case '1':
                        $star_1++;
                        break;
                    case '2':
                        $star_2++;
                        break;
                    case '3':
                        $star_3++;
                        break;
                    case '4':
                        $star_4++;
                        break;
                    case '5':
                        $star_5++;
                        break;
                }
            }

            $total = $rating_summary['total_rating'];
            $rating_summary['1_star_percent'] = round($star_1 / $total * 100);
            $rating_summary['2_star_percent'] = round($star_2 / $total * 100);
            $rating_summary['3_star_percent'] = round($star_3 / $total * 100);
            $rating_summary['4_star_percent'] = round($star_4 / $total * 100);
            $rating_summary['5_star_percent'] = round($star_5 / $total * 100);
            $rating_summary['avg_rating'] = ($star_5 * 5 + $star_4 * 4 + $star_3 * 3 + $star_2 * 2 + $star_1) / $total;
        }

        // Lấy danh sách biến thể (variants) để truyền sang JavaScript
        $variants = ProductVariant::where('product_id', $product->id)
            ->with(['variantOptions.attribute', 'variantOptions.value'])
            ->get();

        $formattedVariants = $variants->map(function ($variant) {
            $attributes = [];
            foreach ($variant->variantOptions as $option) {
                $attributes[$option->attribute->name] = $option->value->value;
            }

            return [
                'id' => $variant->id,
                'attributes' => $attributes,
                'price' => $variant->price,
                'quantity' => $variant->quantity,
                'weight' => $variant->weight,
                'length' => $variant->length,
                'width' => $variant->width,
                'height' => $variant->height,
            ];
        });

        // Gom thuộc tính hiển thị
        $attributes = [];

        foreach ($variants as $variant) {
            foreach ($variant->variantOptions as $option) {
                $attrId = $option->attribute->id;
                $attrName = $option->attribute->name;
                $value = [
                    'id' => $option->value->id,
                    'value' => $option->value->value
                ];

                if (!isset($attributes[$attrId])) {
                    $attributes[$attrId] = [
                        'name' => $attrName,
                        'values' => []
                    ];
                }

                $attributes[$attrId]['values'][$value['id']] = $value['value'];
            }
        }

        $product->related_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        // Lấy danh sách sản phẩm gợi y
        $recommendedProducts = $this->getRecommendedProducts();
        return view('client.products.show', compact(
            'product',
            'attributeGroups',
            'productImages',
            'reviews',
            'rating_summary',
            'attributes',
            'groupedDetails',
            'recommendedProducts',
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
                'quantity' => $variant->quantity,
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
