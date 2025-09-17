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
use Illuminate\Support\Str;
use App\Models\SearchHistory;
use App\Models\Setting;
use App\Models\Tag;

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
        $keywordRaw = trim((string)$request->input('keyword', ''));
        $query = Product::query()
            ->where('is_active', 1)
            ->with(['brand', 'variants']);

        if ($keywordRaw !== '') {
            $slugKey = Str::slug($keywordRaw, '-');     // "áo thun" -> "ao-thun"
            $likeKey = mb_strtolower($keywordRaw);      // fallback cho name/sku

            // 1) Ưu tiên match theo tag
            $tagIds = Tag::where('is_active', 1)
                ->where(function ($q) use ($slugKey, $likeKey) {
                    $q->where('slug', 'like', "%{$slugKey}%")
                        ->orWhereRaw('LOWER(name) like ?', ["%{$likeKey}%"]);
                })
                ->pluck('id');

            if ($tagIds->isNotEmpty()) {
                $query->whereHas('tags', fn($t) => $t->whereIn('tags.id', $tagIds));
            } else {
                // 2) Fallback tìm trong name/sku
                $query->where(function ($q) use ($likeKey) {
                    $q->whereRaw('LOWER(name) like ?', ["%{$likeKey}%"])
                        ->orWhereRaw('LOWER(sku) like ?', ["%{$likeKey}%"]);
                });
            }

            // 3) Lưu lịch sử tìm kiếm (đếm +1)
            $normKey = Str::slug($keywordRaw, '-');
            $hist = SearchHistory::firstOrCreate(['keyword' => $normKey], ['count' => 0]);
            $hist->increment('count');
        }

        $products = $query->latest()->paginate(8)->withQueryString();

        // Gợi ý tag phổ biến (cho khu "Tìm kiếm phổ biến")
        $popularTags = Tag::where('is_active', 1)
            ->withCount('products')
            ->orderBy('sort_order')      // bạn đã có cột này
            ->orderByDesc('products_count')
            ->limit(10)
            ->get();

        $recommendedProducts = $this->getRecommendedProducts();

        return view('client.products.search', [
            'products'            => $products,
            'keyword'             => $keywordRaw,
            'popularTags'         => $popularTags,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }

    public function filter(Request $request)
    {
        $query = Product::with(['brand', 'images', 'variants.options.attribute', 'variants.options.value'])
            ->where('is_active', 1);

        // ===================== 🔎 KEYWORD =====================
        $keywordRaw = trim((string) $request->input('keyword', ''));
        if ($keywordRaw !== '') {
            $likeKey = mb_strtolower($keywordRaw);
            $slugKey = Str::slug($keywordRaw, '-'); // để bắt tag theo slug

            // Ưu tiên match theo Tag (slug/name)
            $tagIds = Tag::where('is_active', 1)
                ->where(function ($w) use ($slugKey, $likeKey) {
                    $w->where('slug', 'like', "%{$slugKey}%")
                        ->orWhereRaw('LOWER(name) like ?', ["%{$likeKey}%"]);
                })
                ->pluck('id');

            // Gộp điều kiện: có tag thì ưu tiên, kèm fallback name/sku
            $query->where(function ($q) use ($tagIds, $likeKey) {
                if ($tagIds->isNotEmpty()) {
                    $q->whereHas('tags', fn($t) => $t->whereIn('tags.id', $tagIds));
                    $q->orWhere(function ($qq) use ($likeKey) {
                        $qq->whereRaw('LOWER(name) like ?', ["%{$likeKey}%"])
                            ->orWhereRaw('LOWER(sku)  like ?', ["%{$likeKey}%"]);
                    });
                } else {
                    $q->whereRaw('LOWER(name) like ?', ["%{$likeKey}%"])
                        ->orWhereRaw('LOWER(sku)  like ?', ["%{$likeKey}%"]);
                }
            });

            // (tuỳ chọn) Heuristic: nếu người dùng gõ "áo"/"quần" thì thêm gợi ý danh mục
            $low = Str::lower($keywordRaw);
            if (str_contains($low, 'áo')) {
                $query->where('category_id', 1);
            }  // ID danh mục Áo
            if (str_contains($low, 'quần')) {
                $query->where('category_id', 2);
            }  // ID danh mục Quần

            // Lưu lịch sử tìm kiếm (an toàn, không dùng DB::raw)
            $hist = SearchHistory::firstOrCreate(['keyword' => $slugKey], ['count' => 0]);
            $hist->increment('count');
        }

        // ===================== 🏷 TAG FILTER (checkbox) =====================
        // Nhận ?tags[]=ao-thun&tags[]=retro&tags_mode=all|any
        $tagSlugs = array_values(array_unique(array_filter((array) $request->input('tags', []))));
        $tagsMode = $request->input('tags_mode', 'any');
        if (!empty($tagSlugs)) {
            // map theo cả slug raw (có dấu) và slug hoá (không dấu)
            $normalized = array_map(fn($s) => Str::slug($s, '-'), $tagSlugs);

            $tagIds = Tag::whereIn('slug', $tagSlugs)                  // raw trong DB
                ->orWhereIn('slug', $normalized)              // nếu DB đã chuẩn hoá
                ->pluck('id')
                ->all();

            if (!empty($tagIds)) {
                $expected = count($tagSlugs); // số tag user chọn, không phải số ID tìm được
                if ($tagsMode === 'all') {
                    $query->whereHas('tags', fn($t) => $t->whereIn('tags.id', $tagIds), '>=', $expected);
                } else {
                    $query->whereHas('tags', fn($t) => $t->whereIn('tags.id', $tagIds));
                }
            } else {
                // Không map được tag nào -> trong chế độ ALL nên trả về rỗng
                if ($tagsMode === 'all') $query->whereRaw('1=0');
            }
        }

        // ===================== 💰 GIÁ =====================
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = (float) $request->input('min_price');
            $maxPrice = (float) $request->input('max_price');
            if ($minPrice <= $maxPrice) {
                $query->whereBetween('sale_price', [$minPrice, $maxPrice]);
            }
        }

        // ===================== 📁 DANH MỤC =====================
        $categoryIds = (array) $request->input('category', []);
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // ===================== 🏢 THƯƠNG HIỆU =====================
        $brandIds = (array) $request->input('brand', []);
        if (!empty($brandIds)) {
            $query->whereIn('brand_id', $brandIds);
        }

        // ===================== 🎨 MÀU =====================
        if ($request->filled('color')) {
            $colorIds = (array) $request->input('color');
            $query->whereHas('variants.options', function ($q) use ($colorIds) {
                $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Màu sắc'))
                    ->whereIn('value_id', $colorIds);
            });
        }

        // ===================== 👕 SIZE =====================
        if ($request->filled('size')) {
            $sizeIds = (array) $request->input('size');
            $query->whereHas('variants.options', function ($q) use ($sizeIds) {
                $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Size'))
                    ->whereIn('value_id', $sizeIds);
            });
        }

        // ===================== 👤 GIỚI TÍNH =====================
        if ($request->filled('gender')) {
            // nếu gender có thể nhiều giá trị, đổi thành whereIn
            $gender = $request->input('gender');
            $query->whereHas('variants.options', function ($q) use ($gender) {
                $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Giới tính'))
                    ->whereIn('value_id', (array) $gender);
            });
        }

        // ===================== ✅ TỒN KHO =====================
        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->where(function ($q) {
                    $q->whereHas('variants', fn($q) => $q->where('stock_quantity', '>', 0))
                        ->orWhere(function ($q2) {
                            $q2->doesntHave('variants')->where('stock_quantity', '>', 0);
                        });
                });
            } elseif ($request->availability === 'out_of_stock') {
                $query->where(function ($q) {
                    $q->whereHas('variants', fn($q) => $q->where('stock_quantity', '<=', 0))
                        ->orWhere(function ($q2) {
                            $q2->doesntHave('variants')->where('stock_quantity', '<=', 0);
                        });
                });
            }
        }

        // ===================== ⚙️ SẮP XẾP =====================
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
            case 'popularity':
                $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                break;
            case 'best_selling':
                $query->orderBy('sale_times', 'desc');
                break; // nếu có cột sale_times
            case 'discount_desc':
                $query->orderByRaw('(base_price - sale_price) DESC');
                break;
            case 'featured':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // ===================== KẾT QUẢ & DỮ LIỆU HIỂN THỊ =====================
        $products   = $query->paginate(8)->withQueryString();
        $categories = Category::all();
        $brands     = Brand::all();

        // Màu sắc
        $colors = AttributeValue::whereHas('variantOptions.attribute', fn($q) => $q->where('name', 'Màu sắc'))
            ->select('attribute_values.id', 'attribute_values.value')
            ->distinct()->get();

        // Size
        $sizes = [];
        $options = ProductVariantOption::with(['attribute', 'value'])
            ->whereHas('attribute', fn($q) => $q->where('name', 'Size'))
            ->get();
        foreach ($options as $option) {
            $value = $option->value;
            if (!collect($sizes)->contains('id', $value->id)) {
                $sizes[] = $value;
            }
        }

        // Giới tính
        $genders = AttributeValue::whereHas('variantOptions.attribute', fn($q) => $q->where('name', 'Giới tính'))
            ->select('attribute_values.id', 'attribute_values.value')
            ->distinct()->get();

        // Wishlist
        $wishlistProductIds = auth()->check()
            ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray()
            : [];

        // (tuỳ chọn) danh sách tag để render checkbox filter
        $allTags = Tag::where('is_active', 1)->orderBy('sort_order')->get();

        return view('client.products.filter-sidebar', compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'genders',
            'wishlistProductIds',
            'allTags'
        ));
    }




    // public function filter(Request $request)
    // {
    //     $query = Product::with(['brand', 'images', 'variants.options.attribute', 'variants.options.value'])
    //         ->where('is_active', 1);
    //     $keyword = $request->input('keyword');
    //     // 🔍 Từ khóa
    //     if ($request->filled('keyword')) {
    //         $keyword = strtolower(trim($request->input('keyword')));
    //         $regex = '[[:<:]]' . $keyword . '[[:>:]]';

    //         $query->where(function ($q) use ($keyword) {
    //             // Tìm chính xác từ trong tên sản phẩm
    //             $q->whereRaw("CONCAT(' ', LOWER(name), ' ') LIKE ?", ['% ' . $keyword . ' %']);

    //             // Gợi ý lọc theo danh mục nếu nhận ra keyword phù hợp
    //             if (str_contains($keyword, 'áo')) {
    //                 $q->where('category_id', 1); // Danh mục Áo
    //             } elseif (str_contains($keyword, 'quần')) {
    //                 $q->where('category_id', 2); // Danh mục Quần
    //             }
    //             // Bạn có thể thêm các điều kiện khác ở đây nếu cần
    //         });
    //     }
    //     // Ghi lại từ khóa tìm kiếm
    //     if ($keyword) {
    //         SearchHistory::updateOrCreate(
    //             ['keyword' => $keyword],
    //             ['count' => DB::raw('count + 1')]
    //         );
    //     }
    //     // 💰 Giá
    //     if ($request->filled('min_price') && $request->filled('max_price')) {
    //         $minPrice = floatval($request->input('min_price'));
    //         $maxPrice = floatval($request->input('max_price'));
    //         if ($minPrice <= $maxPrice) {
    //             $query->whereBetween('sale_price', [$minPrice, $maxPrice]);
    //         }
    //     }

    //     // 🏷 Danh mục (bảo vệ không lọc nếu không có checkbox nào được chọn)
    //     $categoryIds = $request->input('category', []); // luôn trả array (nếu không có -> rỗng)
    //     if (!empty($categoryIds)) {
    //         $query->whereIn('category_id', $categoryIds);
    //     }

    //     // 🏢 Thương hiệu
    //     if ($request->filled('brand')) {
    //         $query->whereIn('brand_id', $request->brand);
    //     }

    //     // 🎨 Màu sắc (lọc theo value_id)
    //     if ($request->filled('color')) {
    //         $query->whereHas('variants.options', function ($q) use ($request) {
    //             $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Màu sắc'))
    //                 ->whereIn('value_id', $request->color);
    //         });
    //     }

    //     // 👕 Kích cỡ (lọc theo value_id)
    //     if ($request->filled('size')) {
    //         $query->whereHas('variants.options', function ($q) use ($request) {
    //             $q->whereHas('attribute', fn($attr) => $attr->where('name', 'Size'))
    //                 ->whereIn('value_id', $request->size);
    //         });
    //     }
    //     // 👤 Giới tính (lọc theo value_id)
    //     if ($request->filled('gender')) {
    //         $query->whereHas('variants.options', function ($q) use ($request) {
    //             $q->whereHas('attribute', function ($attr) {
    //                 $attr->where('name', 'Giới tính');
    //             })->where('value_id', $request->gender);
    //         });
    //     }
    //     // ✅ Tình trạng còn hàng / hết hàng
    //     if ($request->filled('availability')) {
    //         if ($request->availability === 'in_stock') {
    //             $query->where(function ($q) {
    //                 $q->whereHas('variants', fn($q) => $q->where('stock_quantity', '>', 0))
    //                     ->orWhere(function ($q2) {
    //                         $q2->doesntHave('variants')
    //                             ->where('stock_quantity', '>', 0); // sản phẩm không có variant nhưng vẫn còn hàng
    //                     });
    //             });
    //         } elseif ($request->availability === 'out_of_stock') {
    //             $query->where(function ($q) {
    //                 $q->whereHas('variants', fn($q) => $q->where('stock_quantity', '<=', 0))
    //                     ->orWhere(function ($q2) {
    //                         $q2->doesntHave('variants')
    //                             ->where('stock_quantity', '<=', 0); // sản phẩm không có variant và cũng hết hàng
    //                     });
    //             });
    //         }
    //     }

    //     // ⚙️ Sắp xếp theo yêu cầu
    //     switch ($request->input('sort_by')) {
    //         case 'price_desc':
    //             $query->orderBy('sale_price', 'desc');
    //             break;
    //         case 'price_asc':
    //             $query->orderBy('sale_price', 'asc');
    //             break;
    //         case 'alpha_desc':
    //             $query->orderBy('name', 'desc');
    //             break;
    //         case 'alpha_asc':
    //             $query->orderBy('name', 'asc');
    //             break;
    //         case 'featured':
    //             $query->orderBy('created_at', 'desc');
    //             break;
    //         case 'popularity':
    //             $query->withCount('reviews')->orderBy('reviews_count', 'desc');
    //             break;
    //         case 'best_selling':
    //             $query->orderBy('sale_times', 'desc'); // giả sử bạn có trường sale_times
    //             break;
    //         case 'discount_desc':
    //             $query->orderByRaw('(base_price - sale_price) DESC');
    //             break;
    //         default:
    //             $query->orderBy('created_at', 'desc');
    //             break;
    //     }


    //     $products = $query->paginate(8)->withQueryString();

    //     // Dữ liệu hiển thị
    //     $categories = Category::all();
    //     $brands = Brand::all();

    //     // 🎨 Màu sắc
    //     $colors = AttributeValue::whereHas('variantOptions.attribute', fn($q) => $q->where('name', 'Màu sắc'))
    //         ->select('attribute_values.id', 'attribute_values.value')
    //         ->distinct()
    //         ->get();

    //     // 👕 Kích cỡ
    //     $sizes = [];

    //     $options = ProductVariantOption::with(['attribute', 'value'])
    //         ->whereHas('attribute', fn($q) => $q->where('name', 'Size'))
    //         ->get();

    //     $genders = AttributeValue::whereHas('variantOptions.attribute', fn($q) => $q->where('name', 'Giới tính'))
    //         ->select('attribute_values.id', 'attribute_values.value')
    //         ->distinct()
    //         ->get();
    //     foreach ($options as $option) {
    //         $value = $option->value;

    //         if (!collect($sizes)->contains('id', $value->id)) {
    //             $sizes[] = $value;
    //         }
    //     }
    //     $wishlistProductIds = [];

    //     if (auth()->check()) {
    //         $wishlistProductIds = \App\Models\Wishlist::where('user_id', auth()->id())
    //             ->pluck('product_id')
    //             ->toArray();
    //     }
    //     return view('client.products.filter-sidebar', compact(
    //         'products',
    //         'categories',
    //         'brands',
    //         'colors',
    //         'sizes',
    //         'genders',
    //         'wishlistProductIds',
    //     ));
    // }

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
