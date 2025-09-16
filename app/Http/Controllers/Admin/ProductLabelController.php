<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductLabelController extends Controller
{
    public function index()
    {
        $labels = ProductLabel::with('products')->orderByDesc('id')->paginate(10);
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
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'image' => 'required|image',
            'position' => 'nullable|string',
        ]);

        // Check sản phẩm đã có label
        $duplicate = DB::table('product_label_product')
            ->whereIn('product_id', $request->products)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['products' => 'Sản phẩm hoặc số sản phẩm đã có nhãn, không thể gán thêm.']);
        }

        // Upload ảnh
        $file = $request->file('image');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('labels'), $filename);
        $imagePath = 'labels/' . $filename;

        // Tạo label
        $label = ProductLabel::create([
            'image' => $imagePath,
            'position' => $request->position,
        ]);

        // Gán nhiều sản phẩm
        $label->products()->sync($request->products);

        return redirect()->route('admin.product-labels.index')->with('success', 'Tạo nhãn dán thành công');
    }


    public function update(Request $request, $id)
    {
        $label = ProductLabel::findOrFail($id);

        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'image' => 'nullable|image',
            'position' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if (File::exists(public_path($label->image))) {
                File::delete(public_path($label->image));
            }
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('labels'), $filename);
            $label->image = 'labels/' . $filename;
        }

        $label->position = $request->position;
        $label->save();

        // Cập nhật sản phẩm gán nhãn
        $label->products()->sync($request->products);

        return redirect()->route('admin.product-labels.index')->with('success', 'Cập nhật nhãn dán thành công');
    }

    public function edit($id)
    {
        $label = ProductLabel::findOrFail($id);
        $products = Product::all();
        return view('admin.product_labels.edit', compact('label', 'products'));
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
