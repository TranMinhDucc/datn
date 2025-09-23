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
use App\Models\Tag;
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
        $search = trim($request->input('search'));

        $products = Product::with(['category', 'brand'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // 1. Tìm theo tên sản phẩm (chứa từ khóa)
                    $q->where('products.name', 'like', '%' . $search . '%');

                    // 2. Tìm theo danh mục (chứa từ khóa, cả danh mục con)
                    $q->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('categories.name', 'like', '%' . $search . '%')
                            ->orWhereHas('children', function ($q3) use ($search) {
                                $q3->where('categories.name', 'like', '%' . $search . '%');
                            });
                    });
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }












    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();
        return view('admin.products.create', compact('categories', 'brands', 'attributes', 'tags'));
    }

    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name'        => 'required|string|max:255|unique:products,name',
            'slug'        => 'required|string|max:255|unique:products,slug',
            'category_id' => 'required|integer',
            'brand_id'    => 'required|integer',

            'import_price' => 'required|numeric|min:0',
            'base_price'   => 'required|numeric|min:0|gte:import_price',
            'sale_price'   => 'required|numeric|min:0|lte:base_price|gte:import_price',

            'variants'              => 'required|array|min:1',
            'variants.*.sku'        => 'required|string|max:255',
            'variants.*.price'      => 'required|numeric|min:0',
            'variants.*.quantity'   => 'required|integer|min:0',
            'variants.*.weight'     => 'required|numeric|min:0',
            'variants.*.length'     => 'required|numeric|min:0',
            'variants.*.width'      => 'required|numeric|min:0',
            'variants.*.height'     => 'required|numeric|min:0',

            'attributeGroups' => 'nullable|array',
            'starts_at'       => 'nullable|date',
            'ends_at'         => 'nullable|date|after:starts_at',
            'size_chart'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'Tên sản phẩm không được để trống.',
            'name.unique'    => 'Tên sản phẩm đã tồn tại.',
            'slug.required'  => 'Slug không được để trống.',
            'slug.unique'    => 'Slug đã tồn tại, vui lòng chọn slug khác.',

            'category_id.required' => 'Vui lòng chọn danh mục.',
            'brand_id.required'    => 'Vui lòng chọn thương hiệu.',

            'import_price.required' => 'Giá nhập không được để trống.',
            'base_price.gte'        => 'Giá gốc phải lớn hơn hoặc bằng giá nhập.',
            'sale_price.lte'        => 'Giá sale phải nhỏ hơn hoặc bằng giá gốc.',
            'sale_price.gte'        => 'Giá sale phải lớn hơn hoặc bằng giá nhập.',

            'variants.required'            => 'Sản phẩm phải có ít nhất một biến thể.',
            'variants.*.sku.required'      => 'Mã SKU không được để trống.',
            'variants.*.price.required'    => 'Giá biến thể không được để trống.',
            'variants.*.quantity.required' => 'Số lượng biến thể không được để trống.',
            'variants.*.weight.required'   => 'Cân nặng không được để trống.',
            'variants.*.length.required'   => 'Chiều dài không được để trống.',
            'variants.*.width.required'    => 'Chiều rộng không được để trống.',
            'variants.*.height.required'   => 'Chiều cao không được để trống.',
        ]);





        // 2. Tạo slug duy nhất
        $slug = Str::slug($request->slug);

        $totalStock = collect($request->variants ?? [])->sum('quantity');
        // 3. Tạo sản phẩm
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'detailed_description' => $request->detailed_description, // ✅ thêm dòng này
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active ? 1 : 0,
            'import_price' => $request->import_price,
            'base_price' => $request->base_price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $totalStock,
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

        // 5.1 Ảnh bảng size
        if ($request->hasFile('size_chart')) {
            $path = $request->file('size_chart')->store('size_charts', 'public');
            $product->update(['size_chart' => $path]);
        }


        // 7. Gắn Tag cho sản phẩm
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }
        $manualVariants = array_values($request->input('variants', []) ?? []);
        $errors = [];

        // 1. Check trùng SKU trong form (giữa các dòng nhập vào)
        $seen = [];
        foreach ($manualVariants as $i => $row) {
            $sku = trim($row['sku'] ?? '');
            if ($sku === '') continue;

            if (isset($seen[$sku])) {
                $errors["variants.$i.sku"] = 'SKU bị trùng lặp giữa các biến thể.';
            } else {
                $seen[$sku] = true;
            }
        }

        // 2. Check trùng SKU với DB (toàn bảng product_variants)
        $inputSkus = array_keys($seen);
        if (!empty($inputSkus)) {
            $conflicts = ProductVariant::query()
                ->whereIn('sku', $inputSkus)
                ->pluck('id', 'sku'); // [sku => id]

            foreach ($manualVariants as $i => $row) {
                $sku = trim($row['sku'] ?? '');
                if ($sku !== '' && $conflicts->has($sku)) {
                    $errors["variants.$i.sku"] = 'Mã SKU đã tồn tại trong hệ thống.';
                }
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }


        // 6. Lưu biến thể và liên kết thuộc tính
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // SKU xử lý tránh trùng
                $sku = $variant['sku'];
                $originalSku = $sku;
                $skuCounter = 1;
                while (ProductVariant::where('sku', $sku)->exists()) {
                    $sku = $originalSku . '-' . $skuCounter++;
                }


                // Tạo biến thể
                $variantModel = ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['attributes'],
                    'price' => $variant['price'],
                    'quantity' => $variant['quantity'],
                    'sku' => $sku,
                    'weight' => $variant['weight'] ?? null,
                    'length' => $variant['length'] ?? null,
                    'width' => $variant['width'] ?? null,
                    'height' => $variant['height'] ?? null,
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
        $productCheck = Product::with('category')->findOrFail($id);

        if ($productCheck->category && $productCheck->category->trashed()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Danh mục của sản phẩm đã bị xoá. Không thể chỉnh sửa sản phẩm này.');
        }

        $product = Product::with([
            'variants' => function ($q) {
                $q->withCount('orderItems');
            },
            'tags:id,name' // nạp sẵn tag của sản phẩm
        ])->findOrFail($id);

        $details     = ProductDetail::where('product_id', $id)->get();
        $variants    = $product->variants;
        $variantIds  = $variants->pluck('id');

        $attributeNames = Attribute::pluck('name', 'id')->toArray();
        $valueNames     = AttributeValue::pluck('value', 'id')->toArray();

        $attributeGroupsRaw = DB::table('product_variant_options')
            ->join('attributes', 'product_variant_options.attribute_id', '=', 'attributes.id')
            ->join('attribute_values', 'product_variant_options.value_id', '=', 'attribute_values.id')
            ->whereIn('product_variant_options.product_variant_id', $variantIds)
            ->select('attributes.name as group_name', 'attribute_values.value as value')
            ->get()
            ->groupBy('group_name');

        $attributeGroups = $attributeGroupsRaw->map(function ($items, $groupName) {
            return [
                'name'   => $groupName,
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
                $val      = $valueNames[$opt->value_id] ?? null;

                if ($attrName && $val) {
                    $attribute_map[$attrName] = $val;
                }
            }

            return [
                'id'          => $variant->id,
                'attribute_map' => $attribute_map,
                'price'       => $variant->price,
                'quantity'    => $variant->quantity,
                'sku'         => $variant->sku,
                'weight'      => $variant->weight,
                'length'      => $variant->length,
                'width'       => $variant->width,
                'height'      => $variant->height,
                'is_active'   => (bool) $variant->is_active,
                'has_orders'  => $variant->order_items_count > 0,
            ];
        });

        $attributeValues = Attribute::with('values')->get()->mapWithKeys(function ($attr) {
            return [$attr->name => $attr->values->pluck('value')->toArray()];
        });

        $categories = Category::all();
        $brands     = Brand::all();

        // 🔹 LẤY DANH SÁCH TAG ĐỂ ĐỔ VÀO SELECT2
        $tags = Tag::orderBy('sort_order')->get(['id', 'name']);

        return view('admin.products.edit', compact(
            'product',
            'productVariants',
            'attributeGroups',
            'attributeValues',
            'categories',
            'brands',
            'details',
            'tags' // nhớ truyền vào view
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
        \Log::info('👉 BẮT ĐẦU UPDATE PRODUCT', [
            'product_id' => $id,
            'request_all' => $request->all()
        ]);

        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'import_price' => 'required|numeric|min:0',
            'base_price'   => 'required|numeric|min:0|gte:import_price',
            'sale_price' => 'nullable|numeric|min:0|lte:base_price|gte:import_price',

            'description' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'variants' => 'required|array|min:1',
            'variants.*.attributes' => 'nullable|array|min:1',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.price' => 'required|numeric|min:0.01',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.length' => 'nullable|numeric|min:0',
            'variants.*.width'  => 'nullable|numeric|min:0',
            'variants.*.height' => 'nullable|numeric|min:0',

            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'size_chart' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags'   => 'nullable|array',
            'tags.*' => 'nullable|string',
        ], [
            // Thông báo tiếng Việt
            'name.required' => 'Tên sản phẩm không được để trống.',
            'category_id.required' => 'Danh mục không được để trống.',
            'brand_id.required' => 'Thương hiệu không được để trống.',
            'import_price.required' => 'Giá nhập không được để trống.',
            'base_price.required' => 'Giá gốc không được để trống.',
            'base_price.gte' => 'Giá gốc phải lớn hơn hoặc bằng giá nhập.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',

            'variants.required' => 'Sản phẩm phải có ít nhất một biến thể.',
            'variants.*.sku.required' => 'Mã SKU của biến thể không được để trống.',
            'variants.*.price.required' => 'Giá của biến thể không được để trống.',
            'variants.*.quantity.required' => 'Số lượng của biến thể không được để trống.',
            'variants.*.weight.min' => 'Cân nặng không được nhỏ hơn 0.',
            'variants.*.length.min' => 'Chiều dài không được nhỏ hơn 0.',
            'variants.*.width.min'  => 'Chiều rộng không được nhỏ hơn 0.',
            'variants.*.height.min' => 'Chiều cao không được nhỏ hơn 0.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
        ], [
            // Custom attributes tiếng Việt
            'variants' => 'Biến thể',
            'variants.*.sku' => 'Mã SKU',
            'variants.*.price' => 'Giá biến thể',
            'variants.*.quantity' => 'Số lượng biến thể',
            'variants.*.weight' => 'Cân nặng',
            'variants.*.length' => 'Chiều dài',
            'variants.*.width'  => 'Chiều rộng',
            'variants.*.height' => 'Chiều cao',
        ]);



        $product = Product::findOrFail($id);

        $manualVariants = array_values($request->input('variants', []) ?? []); // reindex 0..n

        $errors = [];

        // 2.1 Trùng SKU giữa các dòng trong form
        $seen = [];
        foreach ($manualVariants as $i => $row) {
            $sku = trim($row['sku'] ?? '');
            if ($sku === '') continue;

            if (isset($seen[$sku])) {
                $errors["variants.$i.sku"] = 'SKU bị trùng lặp giữa các biến thể.';
            } else {
                $seen[$sku] = true;
            }
        }

        // 2.2 Trùng SKU với DB (toàn bảng product_variants), bỏ qua chính biến thể đang sửa
        $inputSkus = array_keys($seen);
        if (!empty($inputSkus)) {
            $selfIds = collect($manualVariants)->pluck('id')->filter()->map(fn($v) => (int)$v)->all();

            $conflicts = ProductVariant::query()
                ->whereIn('sku', $inputSkus)
                ->when(!empty($selfIds), fn($q) => $q->whereNotIn('id', $selfIds))
                ->pluck('id', 'sku'); // [sku => id]

            foreach ($manualVariants as $i => $row) {
                $sku = trim($row['sku'] ?? '');
                if ($sku !== '' && $conflicts->has($sku)) {
                    $errors["variants.$i.sku"] = 'Mã SKU đã tồn tại trong hệ thống.';
                }
            }
        }

        if (!empty($errors)) {
            // Trả về kèm lỗi theo từng ô; layout Toastr sẽ show từng lỗi thành toast
            return back()->withErrors($errors)->withInput();
        }

        DB::beginTransaction();
        try {
            // --- 1. Cập nhật sản phẩm chính ---
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
                'is_active' => $request->is_active ?? 0,
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                'sale_times' => $request->sale_times ?? 0,
            ]);

            \Log::info('✅ PRODUCT UPDATED', ['product_id' => $product->id]);

            // --- 2. Cập nhật ảnh đại diện ---
            if ($request->hasFile('image')) {
                \Log::info('🖼 Cập nhật ảnh đại diện');
            }

            // --- 3. Cập nhật biến thể ---
            $existingVariants = $product->variants()->with('options')->get();
            $manualVariants = $request->input('variants', []);
            $deletedVariantIds = $request->input('deleted_variant_ids', []);
            $existingSKUs = $existingVariants->pluck('sku')->toArray();
            $processedIds = [];

            \Log::info('📌 Input Variants', $manualVariants);
            \Log::info('📌 Deleted Variant IDs', $deletedVariantIds);

            foreach ($manualVariants as $variantData) {
                $variantId = !empty($variantData['id']) ? (int)$variantData['id'] : null;
                $variantName = collect($variantData['attributes'] ?? [])->values()->join(' / ');

                $newSKU = $variantData['sku'];


                if ($variantId && $existingVariants->contains('id', $variantId)) {
                    \Log::info('🔄 UPDATE VARIANT', ['id' => $variantId, 'data' => $variantData]);

                    $variant = $existingVariants->where('id', $variantId)->first();
                    $variant->update([
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                        'sku' => $newSKU,
                        'weight' => $variantData['weight'] ?? 0,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                        'variant_name' => $variantName,
                        'is_active' => array_key_exists('is_active', $variantData)
                            ? (int)$variantData['is_active']
                            : $variant->is_active,

                    ]);
                    $variant->options()->delete();
                } else {
                    \Log::info('➕ CREATE NEW VARIANT', ['data' => $variantData]);

                    $variant = $product->variants()->create([
                        'sku' => $newSKU,
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                        'weight' => $variantData['weight'] ?? 0,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                        'variant_name' => $variantName,
                        'is_active' => array_key_exists('is_active', $variantData)
                            ? (int)$variantData['is_active']
                            : 1,
                    ]);
                }

                // Lưu attributes
                foreach ($variantData['attributes'] ?? [] as $attrName => $valName) {
                    $attribute = Attribute::firstOrCreate(
                        ['name' => $attrName],
                        ['slug' => Str::slug($attrName)]
                    );
                    $value = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $valName
                    ]);
                    $variant->options()->create([
                        'attribute_id' => $attribute->id,
                        'value_id' => $value->id,
                    ]);
                }

                $existingSKUs[] = $newSKU;
                $processedIds[] = $variant->id;
            }

            // --- Xóa biến thể ---
            foreach ($existingVariants as $variant) {
                if (in_array($variant->id, $deletedVariantIds)) {
                    if ($variant->orderItems()->exists()) {
                        \Log::warning('⚠️ Không thể xóa variant đã có đơn hàng', ['id' => $variant->id]);
                        $variant->update(['is_active' => 0]);
                    } else {
                        \Log::info('🗑 XÓA VARIANT', ['id' => $variant->id]);
                        $variant->options()->delete();
                        $variant->delete();
                    }
                }
            }

            // Xoá ảnh cũ nếu người dùng bấm nút xoá
            if ($request->boolean('remove_size_chart') && $product->size_chart) {
                Storage::disk('public')->delete($product->size_chart);
                $product->size_chart = null;
            }

            // Tải ảnh mới (nếu có) -> ghi đè ảnh cũ
            if ($request->hasFile('size_chart')) {
                if ($product->size_chart) {
                    Storage::disk('public')->delete($product->size_chart);
                }
                $path = $request->file('size_chart')->store('size_charts', 'public');
                $product->size_chart = $path;
            }

            // --- 3.1 Đồng bộ Tag ---
            if ($request->has('tags')) {
                $rawTags = (array) $request->input('tags', []);
                $tagIds  = [];

                foreach ($rawTags as $t) {
                    $t = trim((string)$t);
                    if ($t === '') continue;

                    // Nếu là ID số -> dùng luôn
                    if (ctype_digit($t)) {
                        $tag = Tag::find((int)$t);
                        if ($tag) {
                            $tagIds[] = $tag->id;
                        }
                        continue;
                    }

                    // Nếu là tên mới -> tạo tag mới (slug unique, sort_order = max+1)
                    $name     = mb_substr($t, 0, 50);
                    $baseSlug = Str::slug($name) ?: Str::slug(Str::random(6));
                    $slug     = $baseSlug;
                    $i        = 1;
                    while (Tag::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $i++;
                    }

                    $tag = Tag::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'name'       => $name,
                            'description' => null,
                            'is_active'  => 1,
                            'sort_order' => (int) Tag::max('sort_order') + 1,
                        ]
                    );

                    $tagIds[] = $tag->id;
                }

                // Ghi vào bảng trung gian product_tags
                $product->tags()->sync($tagIds);   // nếu muốn cộng dồn dùng syncWithoutDetaching($tagIds)
            }

            $product->save();

            DB::commit();
            \Log::info('🎉 UPDATE PRODUCT THÀNH CÔNG');
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Lỗi cập nhật sản phẩm: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
        }
    }



    public function show(Product $product)
    {
        // Chỉ cần trả về view với sản phẩm
    }



    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã chuyển vào thùng rác.');
    }
    public function trash()
    {
        $products = Product::onlyTrashed()->paginate(10);
        return view('admin.products.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.trash')->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        // Xoá ảnh phụ
        $product->images()->each(function ($img) {
            Storage::disk('public')->delete($img->image_url);
            $img->delete();
        });

        // Xoá ảnh đại diện
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Xoá biến thể
        $product->variants()->delete();

        // Xoá bản ghi chính
        $product->forceDelete();

        return redirect()->route('admin.products.trash')->with('success', 'Xóa vĩnh viễn sản phẩm.');
    }
    public function getVariants(Request $request)
    {
        $productId = $request->query('product_id');

        $variants = ProductVariant::where('product_id', $productId)
            ->with('options')
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'options' => $variant->options->pluck('value')->toArray(),
                ];
            });

        return response()->json(['variants' => $variants]);
    }
}
