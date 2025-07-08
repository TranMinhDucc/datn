<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;

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


        $variants = ProductVariant::where('product_id', $product->id)
            ->with(['variantOptions.attribute', 'variantOptions.value'])
            ->get();


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
        // dd($variants,$attributes);
        $product->related_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // dd($product->related_products);


        $variantMap = [];

        foreach ($product->variants as $variant) {
            $attributeValues = [];

            foreach ($variant->options as $option) {
                $attributeValues[$option->attribute->name] = $option->value->value;
            }

            $variantMap[] = [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->price,
                'stock' => $variant->quantity,
                'attributes' => $attributeValues
            ];
        }



        return view('client.products.show', compact(
            'product',
            'attributeGroups',
            'productImages',
            'reviews',
            'rating_summary',
            'test_id',
            'variantMap',
            'variants',
            'attributes'
        ));
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

        Log::info("variant", [$variant]);

        if ($variant) {
            return response()->json([
                'status' => 'ok',
                'price' => $variant->price,
                'quantity' => $variant->quantity,
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }


}
