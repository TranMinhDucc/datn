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

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'code' => 'required|string|max:100|unique:products,code',
            'import_price' => 'required|numeric',
            'base_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['name', 'slug', 'code', 'import_price', 'base_price', 'sale_price', 'stock_quantity', 'category_id', 'brand_id', 'short_desc', 'description']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);

            // Save multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // Save manual variants
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
            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $variantAttributes = VariantAttribute::with('values')->get();
        $product->load('images', 'variants.options');
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'variantAttributes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
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
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['name', 'slug', 'code', 'import_price', 'base_price', 'sale_price', 'stock_quantity', 'category_id', 'brand_id', 'short_desc', 'description']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            // Sync additional images
            if ($request->hasFile('images')) {
                foreach ($product->images as $oldImg) {
                    Storage::disk('public')->delete($oldImg->image_path);
                    $oldImg->delete();
                }
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
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
            return back()->withErrors('Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $product->images()->each(function ($img) {
            Storage::disk('public')->delete($img->image_path);
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
