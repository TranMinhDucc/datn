<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     $categories = Category::orderBy('id', 'asc')->paginate(10);
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
        'icon'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'slug'        => 'nullable|string|max:255|unique:categories,slug',
        'status'      => 'required|boolean',
    ], [
        'icon.image'    => 'Tệp tải lên phải là hình ảnh.',
        'icon.mimes'    => 'Ảnh phải có định dạng: jpg, jpeg, png, webp.',
        'icon.max'      => 'Ảnh không được vượt quá 2MB.',
        'name.required' => 'Vui lòng nhập tên danh mục.',
        'slug.unique'   => 'Slug đã tồn tại, hãy chọn slug khác.',
        'status.required' => 'Vui lòng chọn trạng thái hiển thị.',
        'status.boolean'  => 'Giá trị trạng thái không hợp lệ.',
    ]);

    // Nếu slug chưa nhập, tự động tạo từ name
    if (empty($validated['slug'])) {
        $validated['slug'] = Str::slug($validated['name']);
    }

    // Nếu có icon, lưu ảnh
    if ($request->hasFile('icon')) {
        $validated['icon'] = $request->file('icon')->store('categories', 'public');
    }

    // Lưu danh mục
    Category::create($validated);

    return redirect()->route('admin.categories.index')
                     ->with('success', 'Thêm danh mục thành công!');
}
    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'icon'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'slug'        => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        'status'      => 'required|boolean',
    ], [
        'icon.image'    => 'Tệp tải lên phải là hình ảnh.',
        'icon.mimes'    => 'Ảnh phải có định dạng: jpg, jpeg, png, webp.',
        'icon.max'      => 'Ảnh không được vượt quá 2MB.',
        'name.required' => 'Vui lòng nhập tên danh mục.',
        'slug.unique'   => 'Slug đã tồn tại, hãy chọn slug khác.',
        'status.required' => 'Vui lòng chọn trạng thái.',
        'status.boolean'  => 'Giá trị trạng thái không hợp lệ.',
    ]);

    // Nếu slug rỗng thì tạo tự động từ name
    if (empty($validated['slug'])) {
        $validated['slug'] = Str::slug($validated['name']);
    }

    // Nếu upload icon mới
    if ($request->hasFile('icon')) {
        // Xóa icon cũ nếu tồn tại
        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }
        // Lưu icon mới
        $validated['icon'] = $request->file('icon')->store('categories', 'public');
    }

    // Cập nhật danh mục
    $category->update($validated);

    return redirect()->route('admin.categories.index')
                     ->with('success', 'Cập nhật danh mục thành công!');
}

    public function destroy(Category $category)
    {
        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
    public function show (Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }
}
