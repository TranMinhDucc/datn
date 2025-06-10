<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\VariantAttribute;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand'])->orderByDesc('id')->paginate(10);
        $perPage = 10;
        $page = $request->input('page', 1);
        $products = Product::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $page);
        return view('admin.products.index', compact('products'));
    }
    public function show(Product $product)
    {
        $product->load('category', 'brand', 'images', 'variants.options');
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $variantAttributes = VariantAttribute::with('values')->get();
        return view('admin.products.create', compact('categories', 'brands', 'variantAttributes'));
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'code' => 'required|string|max:100|unique:products,code',
        'import_price' => 'required|numeric',
        'base_price' => 'required|numeric',
        'sale_price' => 'nullable|numeric',
        'stock_quantity' => 'required|integer',
        'status' => 'required|boolean',
        'image' => 'nullable|image|max:2048',
        'images.*' => 'nullable|image|max:2048',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
    ]);

    // Kiểm tra logic giá
    $validator->after(function ($validator) use ($request) {
        $import = $request->input('import_price');
        $base = $request->input('base_price');
        $sale = $request->input('sale_price');

        if ($import >= $base) {
            $validator->errors()->add('import_price', 'Giá nhập phải nhỏ hơn giá gốc.');
        }

        if ($sale !== null && $sale >= $base) {
            $validator->errors()->add('sale_price', 'Giá khuyến mãi phải nhỏ hơn giá gốc.');
        }

        if (!$request->has('manual_variants') || count($request->manual_variants) == 0) {
            $validator->errors()->add('manual_variants', 'Sản phẩm phải có ít nhất một biến thể.');
        }
    });

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();
    try {
        $data = $request->only([
            'name',
            'slug',
            'code',
            'import_price',
            'base_price',
            'sale_price',
            'stock_quantity',
            'status',
            'category_id',
            'brand_id',
            'short_desc',
            'description'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Thêm nhiều ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                if ($img->isValid()) {
                    $path = $img->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_thumbnail' => false
                    ]);
                }
            }
        }

        // Thêm biến thể
        $validVariantCount = 0;
        foreach ($request->manual_variants as $variantData) {
            // Kiểm tra SKU trùng
            if (ProductVariant::where('sku', $variantData['sku'])->exists()) {
                return back()->withErrors(['sku' => 'SKU "' . $variantData['sku'] . '" đã tồn tại.'])->withInput();
            }

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku'        => $variantData['sku'],
                'price'      => $variantData['price'],
                'quantity'   => $variantData['quantity'],
            ]);

            $validAttributes = 0;

            if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                foreach ($variantData['attributes'] as $attrId => $valId) {
                    if (!empty($valId)) {
                        ProductVariantOption::create([
                            'product_variant_id' => $variant->id,
                            'attribute_id'       => $attrId,
                            'value_id'           => $valId,
                        ]);
                        $validAttributes++;
                    }
                }
            }

            // Nếu không có thuộc tính nào hợp lệ thì xoá biến thể
            if ($validAttributes === 0) {
                $variant->delete();
            } else {
                $validVariantCount++;
            }
        }

        // Nếu sau khi lọc không còn biến thể nào hợp lệ thì rollback
        if ($validVariantCount === 0) {
            DB::rollBack();
            return back()->withErrors(['manual_variants' => 'Ít nhất một biến thể phải có thuộc tính hợp lệ.'])->withInput();
        }

        DB::commit();
        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}



    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $variantAttributes = VariantAttribute::with('values')->get();
        $product->load('variants.options'); // chỉ load các quan hệ thực sự cần
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'variantAttributes'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'code' => 'required|string|max:100|unique:products,code,' . $product->id,
            'import_price' => 'required|numeric',
            'base_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:0,1',
        ]);

        $validator->after(function ($validator) use ($request) {
            $import = $request->input('import_price');
            $base = $request->input('base_price');
            $sale = $request->input('sale_price');

            if ($import >= $base) {
                $validator->errors()->add('import_price', 'Giá nhập phải nhỏ hơn giá gốc.');
            }

            if ($sale !== null && $sale >= $base) {
                $validator->errors()->add('sale_price', 'Giá khuyến mãi phải nhỏ hơn giá gốc.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name',
                'slug',
                'code',
                'import_price',
                'base_price',
                'sale_price',
                'stock_quantity',
                'category_id',
                'brand_id',
                'short_desc',
                'description',
                'status',
            ]);

            // Cập nhật ảnh đại diện
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            // Cập nhật ảnh phụ
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

            // Xoá ảnh theo request
            if ($request->has('delete_image_ids')) {
                foreach ($request->delete_image_ids as $id) {
                    $img = ProductImage::find($id);
                    if ($img) {
                        Storage::disk('public')->delete($img->image_url);
                        $img->delete();
                    }
                }
            }

            // Cập nhật biến thể
            $product->variants()->delete();
            if ($request->has('manual_variants')) {
                foreach ($request->manual_variants as $variantData) {
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                    ]);

                    if (isset($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attrId => $valId) {
                            ProductVariantOption::create([
                                'product_variant_id' => $variant->id,
                                'attribute_id' => $attrId,
                                'value_id' => $valId,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()])->withInput();
        }
    }


    public function destroy(Product $product)
{
    // Xoá ảnh liên quan
    $product->images()->each(function ($img) {
        Storage::disk('public')->delete($img->image_path); // Xoá file vật lý
        $img->delete(); // Xoá bản ghi ảnh
    });

    // Xoá ảnh đại diện nếu có
    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }

    // Xoá các biến thể
    $product->variants()->delete();

    // Xoá sản phẩm
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công!');
}

}
