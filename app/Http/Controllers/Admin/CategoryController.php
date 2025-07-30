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

        // Khôi phục record
        $category->restore();

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
