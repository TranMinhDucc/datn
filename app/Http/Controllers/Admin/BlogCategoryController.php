<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = BlogCategory::withCount('blogs')
        ->when($request->filled('search'), fn($q) => $q->search($request->search))
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('admin.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Canh mục đã được tạo thành công!');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog_categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Canh mục đã được cập nhật thành công!');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Canh mục đã được xóa thành công!');
    }
}