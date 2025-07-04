<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariantOption;
use App\Models\AttributeValue;


class ProductController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, có thể dùng paginate

        return view('client.products.index');
    }


    public function show(string $slug)
    {
        // test trước khi thêm slug vào hàm

        $product = Product::with([
            'variants.options.attribute',
            'variants.options.value',
            'category',
            'brand',
            'tags',
            'images',
        ])->where('slug', $slug)->firstOrFail();
        $test_id = $product->id;
        // 👉 Lấy danh sách ảnh phụ (không phải thumbnail)
        $productImages = ProductImage::where('product_id', $product->id)
            ->where('is_thumbnail', false)
            ->get();

        // 👉 Gom các giá trị phân loại (Size, Màu...)
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

        // // 👉 Lấy đánh giá và thông tin người đánh giá
        $reviews = Review::join('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.product_id', $product->id)
            ->where('reviews.verified_purchase', 1)
            ->orderBy('reviews.id', 'desc')
            ->select('reviews.*', 'users.fullname as user_fullname', 'users.avatar as user_avatar')
            ->get();

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
            $star_5 = 0;
            $star_4 = 0;
            $star_3 = 0;
            $star_2 = 0;
            $star_1 = 0;

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

            $rating_summary['1_star_percent'] = round($star_1 / $rating_summary['total_rating'] * 100);
            $rating_summary['2_star_percent'] = round($star_2 / $rating_summary['total_rating'] * 100);
            $rating_summary['3_star_percent'] = round($star_3 / $rating_summary['total_rating'] * 100);
            $rating_summary['4_star_percent'] = round($star_4 / $rating_summary['total_rating'] * 100);
            $rating_summary['5_star_percent'] = round($star_5 / $rating_summary['total_rating'] * 100);

            $rating_summary['avg_rating'] = (
                $star_5 * 5 + $star_4 * 4 + $star_3 * 3 + $star_2 * 2 + $star_1 * 1
            ) / $rating_summary['total_rating'];
        }


        $product->related_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // dd($product->related_products);
        // Lấy danh sách sản phẩm gợi y
        $recommendedProducts = $this->getRecommendedProducts();
        return view('client.products.show', compact(
            'product',
            'attributeGroups',
            'productImages',
            'reviews',
            'rating_summary',
            'test_id',
            'recommendedProducts'
        ));
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

        $products = $query->latest()->paginate(8)->withQueryString();

        return view('client.products.search', compact('products', 'keyword'));
    }
    public function filter(Request $request)
    {
        $query = Product::with(['brand', 'images', 'variants.options.attribute', 'variants.options.value'])
            ->where('is_active', 1);

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

        // 💰 Giá
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = floatval($request->input('min_price'));
            $maxPrice = floatval($request->input('max_price'));
            if ($minPrice <= $maxPrice) {
                $query->whereBetween('sale_price', [$minPrice, $maxPrice]);
            }
        }

        // 🏷 Danh mục
        if ($request->filled('category')) {
            $query->whereIn('category_id', $request->category);
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

        return view('client.products.filter-sidebar', compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'genders'
        ));
    }
    private function getRecommendedProducts($limit = 6)
    {
        return Product::where('is_active', 1)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }
}
