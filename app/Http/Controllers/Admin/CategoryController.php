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
        $data = $request->validate([
           
            'icon'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name'        => 'required|string|max:255',
            
            'description' => 'nullable|string',
            
            'slug'        => 'required|string|max:255|unique:categories,slug',
            
            'status'      => 'required|boolean',
        ]);
    $data['slug'] = $data['slug'] ?? str::slug($data['name']);

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
           
            'icon'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name'        => 'required|string|max:255',
            
            'description' => 'nullable|string',
           
            'slug'        => 'required|string|max:255|unique:categories,slug,' . $category->id,
          
            'status'      => 'required|boolean',
        ]);

        if ($request->hasFile('icon')) {
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }
            $data['icon'] = $request->file('icon')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
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
