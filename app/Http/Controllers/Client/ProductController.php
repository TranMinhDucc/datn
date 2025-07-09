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
        ])->where('slug', $slug)->firstOrFail();

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
            'attributes',
            'variantMap'
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
}
