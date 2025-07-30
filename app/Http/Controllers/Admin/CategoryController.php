<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withTrashed()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::all();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
        ]);

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path; // Lưu đường dẫn vào DB
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }
    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
        ]);

        // Xử lý upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }
    public function destroy(Category $category)
    {
        // Xóa ảnh khỏi thư mục storage nếu có
        // if ($category->image && Storage::disk('public')->exists($category->image)) {
        //     Storage::disk('public')->delete($category->image);
        // }
        // Ẩn tất cả sản phẩm thuộc danh mục này
        $category->products()->update(['is_active' => 0]);

        // Lấy tất cả danh mục con
        $children = Category::where('parent_id', $category->id)->get();

        foreach ($children as $child) {
            // Ẩn sản phẩm của danh mục con
            $child->products()->update(['is_active' => 0]);

            // Xoá mềm danh mục con
            $child->delete();
        }

        // Xóa record danh mục
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục thành công.');
    }
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        // Kiểm tra nếu có parent_id và parent đã bị xoá thì không cho khôi phục
        if ($category->parent_id) {
            $parent = Category::withTrashed()->find($category->parent_id);

            if ($parent && $parent->trashed()) {
                return redirect()->route('admin.categories.index')->with('error', 'Không thể khôi phục danh mục vì danh mục cha đã bị xoá.');
            }
        }
        // Khôi phục record
        $category->restore();

        $category->products()->update(['is_active' => 1]);  // Khôi phục sản phẩm liên quan

        return redirect()->route('admin.categories.index')->with('success', 'Đã khôi phục danh mục.');
    }
    // public function search(Request $request)
    // {
    //     $keyword = $request->input('keyword');

    //     $categories = Category::withTrashed()
    //         ->where('name', 'like', "%{$keyword}%")
    //         ->get();

    //     return response()->json($categories);
    // }
}
