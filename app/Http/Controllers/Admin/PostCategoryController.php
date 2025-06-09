<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::withCount('posts')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'total' => PostCategory::count(),
            'active' => PostCategory::where('status', 1)->count(),
            'inactive' => PostCategory::where('status', 0)->count(),
            'total_posts' => PostCategory::withCount('posts')->get()->sum('posts_count'),
        ];

        return view('admin.post_categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.post_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:post_categories,name',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        PostCategory::create([
            'title' => $request->title,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'status' => $request->status
        ]);

        return redirect()->route('admin.post-categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function edit(PostCategory $category)
    {
        return view('admin.post_categories.edit', compact('category'));
    }

    public function update(Request $request, PostCategory $category)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:post_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $category->update([
            'title' => $request->title,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'status' => $request->status
        ]);

        return redirect()->route('admin.post-categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(PostCategory $category)
    {
        // Kiểm tra xem danh mục có bài viết nào không
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.post-categories.index')
                ->with('error', 'Không thể xóa danh mục này vì vẫn còn bài viết!');
        }

        $category->delete();

        return redirect()->route('admin.post-categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }
}