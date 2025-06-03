<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->paginate(10); // phân trang 10 sp/trang
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
            'name' => 'required',
            'images' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // thêm các validate khác
        ]);

        $data = $request->all();

        if ($request->hasFile('images')) {
            $path = $request->file('images')->store('uploads/products', 'public');
            $data['images'] = '/storage/' . $path;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact(['product', 'categories']));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'images' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // thêm các rules khác nếu cần
        ]);

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->code = $request->code;
        // ...

        // Nếu có upload ảnh mới
        if ($request->hasFile('images')) {
            $file = $request->file('images');
            $path = $file->store('products', 'public');
            $product->images = $path;
        }

        $product->update_gettime = now(); // nếu bạn dùng update_gettime thay vì updated_at

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công.');
    }
}
