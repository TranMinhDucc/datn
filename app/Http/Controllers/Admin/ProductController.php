<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\ProductDetail;
use App\Models\ProductVariantOption;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\VariantAttribute;
use App\Models\VariantValue;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand'])->orderByDesc('id')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();
        return view('admin.products.create', compact('categories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'import_price' => 'required|numeric',
            'base_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'variants' => 'nullable|array',
            'attributeGroups' => 'nullable|array',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        // 2. Tạo slug duy nhất
        $slug = Str::slug($request->slug);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // 3. Tạo sản phẩm
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'sku' => $request->sku,
            'description' => $request->description,
            'detailed_description' => $request->detailed_description, // ✅ thêm dòng này
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active ? 1 : 0,
            'import_price' => $request->import_price,
            'base_price' => $request->base_price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'sale_times' => $request->sale_times ?? 0,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
        ]);
        // 🟩 Thêm đoạn này để lưu chi tiết sản phẩm
        if ($request->has('details')) {
            foreach ($request->details as $group) {
                $groupName = $group['group_name'] ?? null;

                if (!$groupName || empty($group['items'])) continue;

                foreach ($group['items'] as $item) {
                    if (!empty($item['label']) || !empty($item['value'])) {
                        $product->productDetails()->create([
                            'group_name' => $groupName,
                            'label' => $item['label'],
                            'value' => $item['value'],
                        ]);
                    }
                }
            }
        }


        // 4. Ảnh đại diện
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
            $product->save();
        }

        // 5. Ảnh phụ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path
                ]);
            }
        }

        // 6. Lưu biến thể và liên kết thuộc tính
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variant) {
                // SKU xử lý tránh trùng
                $sku = $variant['sku'] ?? null;
                if ($sku) {
                    $originalSku = $sku;
                    $skuCounter = 1;
                    while (ProductVariant::where('sku', $sku)->exists()) {
                        $sku = $originalSku . '-' . $skuCounter++;
                    }
                }

                // Tạo biến thể
                $variantModel = ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['attributes'],
                    'price' => $variant['price'],
                    'quantity' => $variant['quantity'],
                    'sku' => $sku,
                    'weight' => $variant['weight'],
                    'length' => $variant['length'],
                    'width' => $variant['width'],
                    'height' => $variant['height'],
                ]);

                // Tách thuộc tính
                $values = explode(' / ', $variant['attributes']); // ["Đỏ", "XS"]
                $attributeGroups = $request->attributeGroups;      // ["Màu sắc", "Size"]

                foreach ($values as $i => $valueName) {
                    if (!isset($attributeGroups[$i])) continue;

                    // Tìm attribute_id theo tên
                    $attribute = Attribute::firstOrCreate(['name' => $attributeGroups[$i]]);

                    // Tìm hoặc tạo value
                    $value = AttributeValue::firstOrCreate([
                        'value' => $valueName,
                        'attribute_id' => $attribute->id
                    ]);

                    // Ghi vào bảng liên kết
                    DB::table('product_variant_options')->insert([
                        'product_variant_id' => $variantModel->id,
                        'attribute_id' => $attribute->id,
                        'value_id' => $value->id,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }


    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $details = ProductDetail::where('product_id', $id)->get();
        $variants = $product->variants;
        $variantIds = $variants->pluck('id');

        $attributeNames = Attribute::pluck('name', 'id')->toArray();
        $valueNames = AttributeValue::pluck('value', 'id')->toArray();

        $attributeGroupsRaw = DB::table('product_variant_options')
            ->join('attributes', 'product_variant_options.attribute_id', '=', 'attributes.id')
            ->join('attribute_values', 'product_variant_options.value_id', '=', 'attribute_values.id')
            ->whereIn('product_variant_options.product_variant_id', $variantIds)
            ->select('attributes.name as group_name', 'attribute_values.value as value')
            ->get()
            ->groupBy('group_name');

        $attributeGroups = $attributeGroupsRaw->map(function ($items, $groupName) {
            return [
                'name' => $groupName,
                'values' => $items->pluck('value')->unique()->values()->toArray()
            ];
        })->values()->all();

        $productVariants = $variants->map(function ($variant) use ($attributeNames, $valueNames) {
            $optionsRaw = DB::table('product_variant_options')
                ->where('product_variant_id', $variant->id)
                ->get();

            $attribute_map = [];

            foreach ($optionsRaw as $opt) {
                $attrName = $attributeNames[$opt->attribute_id] ?? null;
                $val = $valueNames[$opt->value_id] ?? null;

                if ($attrName && $val) {
                    $attribute_map[$attrName] = $val;
                }
            }

            return [
                'attribute_map' => $attribute_map,
                'price' => $variant->price,
                'quantity' => $variant->quantity,
                'sku' => $variant->sku,
                'weight' => $variant->weight,
                'length' => $variant->length,
                'width' => $variant->width,
                'height' => $variant->height,
            ];
        });

        $attributeValues = Attribute::with('values')->get()->mapWithKeys(function ($attr) {
            return [$attr->name => $attr->values->pluck('value')->toArray()];
        });

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact(
            'product',
            'productVariants',
            'attributeGroups',
            'attributeValues',
            'categories',
            'brands',
            'details'
        ));
    }



    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'slug' => 'nullable|string|max:255',
    //         'category_id' => 'required|exists:categories,id',
    //         'brand_id' => 'required|exists:brands,id',
    //         'import_price' => 'required|numeric|min:0',
    //         'base_price' => 'required|numeric|min:0',
    //         'sale_price' => 'nullable|numeric|min:0',
    //         'stock_quantity' => 'required|integer|min:0',
    //         'description' => 'nullable|string',
    //         'detailed_description' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    //         'variants' => 'nullable|array',
    //         'variants.*.attributes' => 'required_with:variants|array|min:1',
    //         'variants.*.price' => 'required_with:variants|numeric|min:0.01',
    //         'variants.*.quantity' => 'required_with:variants|integer|min:0',
    //         'variants.*.sku' => 'required_with:variants|string|max:100',
    //         'starts_at' => 'nullable|date',
    //         'ends_at' => 'nullable|date|after:starts_at',


    //     ], [
    //         'name.required' => 'Tên sản phẩm không được để trống.',
    //         'category_id.required' => 'Vui lòng chọn danh mục.',
    //         'brand_id.required' => 'Vui lòng chọn thương hiệu.',
    //         'import_price.required' => 'Vui lòng nhập giá nhập.',
    //         'base_price.required' => 'Vui lòng nhập giá gốc.',
    //         'stock_quantity.required' => 'Vui lòng nhập tồn kho.',
    //         // 'variants.required' => 'Phải có ít nhất một biến thể.', // Bỏ dòng này để cho phép không có biến thể
    //         'variants.*.attributes.required_with' => 'Mỗi biến thể phải có ít nhất một thuộc tính.',
    //         'variants.*.price.required_with' => 'Vui lòng nhập giá cho biến thể.',
    //         'variants.*.quantity.required_with' => 'Vui lòng nhập số lượng tồn kho cho biến thể.',
    //         'variants.*.sku.required_with' => 'Vui lòng nhập SKU cho biến thể.',
    //     ]);

    //     $product = Product::findOrFail($id);

    //     DB::beginTransaction();

    //     try {
    //         // === 1. Cập nhật thông tin sản phẩm chính ===
    //         $product->update([
    //             'name' => $request->input('name'),
    //             'slug' => $request->input('slug') ?? Str::slug($request->input('name')),
    //             'category_id' => $request->input('category_id'),
    //             'brand_id' => $request->input('brand_id'),
    //             'description' => $request->input('description'),
    //             'detailed_description' => $request->input('detailed_description'),
    //             'import_price' => $request->input('import_price'),
    //             'base_price' => $request->input('base_price'),
    //             'sale_price' => $request->input('sale_price'),
    //             'stock_quantity' => $request->input('stock_quantity'),
    //             'is_active' => $request->has('is_active'),
    //             'starts_at' => $request->starts_at,
    //             'ends_at' => $request->ends_at,
    //             'sale_times' => $request->sale_times,
    //         ]);

    //         // === 2. Cập nhật ảnh đại diện nếu có ===
    //         if ($request->hasFile('image')) {
    //             if ($product->image) {
    //                 Storage::disk('public')->delete($product->image);
    //             }
    //             $path = $request->file('image')->store('products', 'public');
    //             $product->update(['image' => $path]);
    //         }

    //         // === 3. Xoá ảnh phụ nếu có yêu cầu ===
    //         if ($request->has('delete_image_ids')) {
    //             foreach ($request->delete_image_ids as $imageId) {
    //                 $image = ProductImage::find($imageId);
    //                 if ($image) {
    //                     Storage::disk('public')->delete($image->image_url);
    //                     $image->delete();
    //                 }
    //             }
    //         }

    //         // === 4. Thêm ảnh phụ mới ===
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $img) {
    //                 $path = $img->store('products', 'public');
    //                 ProductImage::create([
    //                     'product_id' => $product->id,
    //                     'image_url' => $path,
    //                     'is_thumbnail' => false,
    //                 ]);
    //             }
    //         }

    //         // === 5. Xoá toàn bộ biến thể cũ và options ===
    //         $product->variants()->each(function ($variant) {
    //             $variant->options()->delete();
    //             $variant->delete();
    //         });


    //         // === 6. Xoá chi tiết sản phẩm cũ nếu có ===
    //         $product->productDetails()->delete(); // Xoá chi tiết cũ

    //         $details = $request->input('details', []);

    //         foreach ($details as $group) {
    //             $groupName = $group['group_name'] ?? null;

    //             if (!$groupName || empty($group['items'])) continue;

    //             foreach ($group['items'] as $item) {
    //                 if (!empty($item['label']) || !empty($item['value'])) {
    //                     $product->productDetails()->create([
    //                         'group_name' => $groupName,
    //                         'label' => $item['label'],
    //                         'value' => $item['value'] ?? null
    //                     ]);
    //                 }
    //             }
    //         }

    //         // === 6. Lưu lại biến thể mới nếu có ===
    //         $manualVariants = $request->input('variants', []);
    //         if (!empty($manualVariants)) {
    //             $attributeMap = Attribute::pluck('id', 'name')->toArray();

    //             foreach ($manualVariants as $variantData) {
    //                 $variant = $product->variants()->create([
    //                     'sku' => $variantData['sku'],
    //                     'price' => $variantData['price'],
    //                     'quantity' => $variantData['quantity'],
    //                 ]);

    //                 foreach ($variantData['attributes'] ?? [] as $attributeName => $valueName) {
    //                     // Tìm hoặc tạo Attribute
    //                     $attribute = Attribute::firstOrCreate(
    //                         ['name' => $attributeName],
    //                         ['slug' => Str::slug($attributeName)]
    //                     );

    //                     // Tìm hoặc tạo Value
    //                     $value = AttributeValue::firstOrCreate([
    //                         'attribute_id' => $attribute->id,
    //                         'value' => $valueName
    //                     ]);

    //                     // Lưu option
    //                     $variant->options()->create([
    //                         'attribute_id' => $attribute->id,
    //                         'value_id' => $value->id,
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
    //     }
    // }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'import_price' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants' => 'nullable|array',
            'variants.*.attributes' => 'required_with:variants|array|min:1',
            'variants.*.price' => 'required_with:variants|numeric|min:0.01',
            'variants.*.quantity' => 'required_with:variants|integer|min:0',
            'variants.*.sku' => 'required_with:variants|string|max:100',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            // 1. Cập nhật thông tin sản phẩm chính
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug ?? Str::slug($request->name),
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'import_price' => $request->import_price,
                'base_price' => $request->base_price,
                'sale_price' => $request->sale_price,
                'stock_quantity' => $request->stock_quantity,
                'description' => $request->description,
                'detailed_description' => $request->detailed_description,
                'is_active' => $request->has('is_active'),
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                'sale_times' => $request->sale_times,
            ]);

            // 2. Cập nhật ảnh đại diện nếu có
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                $product->update(['image' => $path]);
            }

            // 3. Xoá ảnh phụ nếu có yêu cầu
            if ($request->has('delete_image_ids')) {
                foreach ($request->delete_image_ids as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_url);
                        $image->delete();
                    }
                }
            }

            // 4. Thêm ảnh phụ mới
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_thumbnail' => false,
                    ]);
                }
            }

            // 5. Cập nhật chi tiết sản phẩm
            $product->productDetails()->delete();
            foreach ($request->input('details', []) as $group) {
                $groupName = $group['group_name'] ?? null;
                if (!$groupName || empty($group['items'])) continue;

                foreach ($group['items'] as $item) {
                    if (!empty($item['label']) || !empty($item['value'])) {
                        $product->productDetails()->create([
                            'group_name' => $groupName,
                            'label' => $item['label'],
                            'value' => $item['value'] ?? null
                        ]);
                    }
                }
            }

            // 6. Cập nhật biến thể
            $existingVariants = $product->variants()->with('options')->get();
            $manualVariants = $request->input('variants', []);
            $newSKUs = collect($manualVariants)->pluck('sku')->toArray();

            // 6.1 Xử lý các biến thể cũ không còn
            foreach ($existingVariants as $variant) {
                if (!in_array($variant->sku, $newSKUs)) {
                    if ($variant->orderItems()->exists()) {
                        $variant->update(['is_active' => false]);
                    } else {
                        $variant->options()->delete();
                        $variant->delete();
                    }
                }
            }

            // 6.2 Thêm hoặc cập nhật biến thể mới
            foreach ($manualVariants as $variantData) {
                // Tạo variant_name từ attributes
                $variantName = collect($variantData['attributes'] ?? [])->values()->join(' / ');


                $variant = $product->variants()->where('sku', $variantData['sku'])->first();

                if ($variant) {
                    $variant->update([
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                        'sku' => $variantData['sku'],
                        'weight' => $variantData['weight'] ?? 0,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                        'variant_name' => $variantName,
                        'is_active' => true,
                    ]);

                    $variant->options()->delete();
                } else {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                        'weight' => $variantData['weight'] ?? 0,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                        'variant_name' => $variantName,
                        'is_active' => true,
                    ]);
                }

                // Lưu options
                foreach ($variantData['attributes'] ?? [] as $attributeName => $valueName) {
                    $attribute = Attribute::firstOrCreate(
                        ['name' => $attributeName],
                        ['slug' => Str::slug($attributeName)]
                    );

                    $value = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $valueName
                    ]);

                    $variant->options()->create([
                        'attribute_id' => $attribute->id,
                        'value_id' => $value->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
        }
    }





    public function destroy(Product $product)
    {
        $product->images()->each(function ($img) {
            Storage::disk('public')->delete($img->image_url);
            $img->delete();
        });

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công!');
    }
}
