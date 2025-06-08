<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);
        $products = Product::orderBy('id', 'asc')->paginate($perPage, ['*'], 'page', $page);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'code' => 'required|string|max:100|unique:products,code',
            'price' => 'required|numeric|min:0',
            'min_purchase_quantity' => 'required|integer|min:1|max:100',
            'max_purchase_quantity' => 'required|integer|min:' . $request->input('min_purchase_quantity', 1) . '|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'short_desc' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'price', 'description', 'category_id', 'code', 'min_purchase_quantity', 'max_purchase_quantity', 'status', 'short_desc']);

        if ($request->hasFile('images')) {
            $path = $request->file('images')->store('uploads/products', 'public');
            $data['images'] = $path;
        }

        Product::create($data);

        // Tính trang cuối cùng sau khi thêm
        $perPage = 10;
        $total = Product::count();
        $lastPage = ceil($total / $perPage);

        return redirect()->route('admin.products.index', ['page' => $lastPage])->with('success', 'Đã thêm sản phẩm');
    }
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'code' => 'required|string|max:100|unique:products,code,' . $product->id,
            'price' => 'required|numeric|min:0',
            'min_purchase_quantity' => 'required|integer|min:1|max:1000000',
            'max_purchase_quantity' => 'required|integer|min:' . $request->input('min_purchase_quantity', 1) . '|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'status' => 'required|in:0,1',
            'short_desc' => 'nullable|string|max:255',
        ]);

        $product->status = $request->input('status');
        $product->short_desc = $request->input('short_desc');
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->min_purchase_quantity = $request->min_purchase_quantity;
        $product->max_purchase_quantity = $request->max_purchase_quantity;
        $product->category_id = $request->category_id;

        if ($request->hasFile('images')) {
            if ($product->images && \Storage::disk('public')->exists($product->images)) {
                \Storage::disk('public')->delete($product->images);
            }
            $path = $request->file('images')->store('uploads/products', 'public');
            $product->images = $path;
        }

        $product->update_gettime = now();
        $product->save();

        // Lấy page hiện tại từ query param để redirect về đúng trang
        $page = $request->input('page', 1);

        return redirect()->route('admin.products.index', ['page' => $page])->with('success', 'Cập nhật sản phẩm thành công');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công.');
    }
}
