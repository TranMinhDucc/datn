<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductLabelController extends Controller
{
    public function index()
    {
        // Sắp xếp label mới nhất trước
        $labels = ProductLabel::with('product')->orderByDesc('id')->get();
        return view('admin.product_labels.index', compact('labels'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.product_labels.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image',
            'position' => 'nullable|string',
        ]);

        // Lưu ảnh vào public/labels
        $file = $request->file('image');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('labels'), $filename);
        $imagePath = 'labels/' . $filename;

        ProductLabel::create([
            'product_id' => $request->product_id,
            'image' => $imagePath,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.product-labels.index')->with('success', 'Tạo nhãn dán thành công');
    }

    public function edit($id)
    {
        $label = ProductLabel::findOrFail($id);
        $products = Product::all();
        return view('admin.product_labels.edit', compact('label', 'products'));
    }

    public function update(Request $request, $id)
    {
        $productLabel = ProductLabel::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'nullable|image',
            'position' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            $oldPath = public_path($productLabel->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            // Lưu ảnh mới
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('labels'), $filename);
            $productLabel->image = 'labels/' . $filename;
        }

        $productLabel->update([
            'product_id' => $request->product_id,
            'image' => $productLabel->image,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.product-labels.index')->with('success', 'Cập nhật nhãn dán thành công');
    }

    public function destroy($id)
    {
        $productLabel = ProductLabel::findOrFail($id);

        // Xóa file ảnh khỏi thư mục public
        $path = public_path($productLabel->image);
        if (File::exists($path)) {
            File::delete($path);
        }

        $productLabel->delete();
        return redirect()->route('admin.product-labels.index')->with('success', 'Đã xoá nhãn dán');
    }
}
