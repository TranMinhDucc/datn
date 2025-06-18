<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;




class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $blogs = Blog::with(['author', 'category'])
            ->when($request->filled('search'), fn($q) => $q->search($request->search))
            ->when($request->filled('category'), fn($q) => $q->where('category_id', $request->category))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', 'username')->get();
        $categories = BlogCategory::select('id', 'name')->get();
        return view('admin.blogs.create', compact('users', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'thumbnail' => 'nullable|image|max:2048'
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.max' => 'Tiêu đề không được quá 255 ký tự.',
            'slug.required' => 'Slug không được để trống.',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug không được quá 255 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
            'author_id.exists' => 'Tác giả không tồn tại.',
            'category_id.required' => 'Chuyên mục không được để trống.',
            'category_id.exists' => 'Chuyên mục không hợp lệ.',
            'thumbnail.image' => 'Ảnh đại diện phải là định dạng hình ảnh.',
            'thumbnail.max' => 'Ảnh đại diện không được vượt quá 2MB.',         
        ]);

        // Nếu có ảnh đại diện được upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/thumbnails', $filename, 'public');
            $data['thumbnail'] = $path;
        }

        $blog = Blog::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'author_id' => $request->author_id ?: auth()->id(),
            'thumbnail' => $data['thumbnail'] ?? null,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $blog->load('author');
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        $users = User::select('id', 'username')->get();
        $categories = BlogCategory::select('id', 'name')->get();
        return view('admin.blogs.edit', compact('blog', 'users', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:blog_categories,id',
            'thumbnail' => 'nullable|image|max:2048', // max 2MB
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.max' => 'Tiêu đề không được quá 255 ký tự.',
            'slug.required' => 'Slug không được để trống.',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug không được quá 255 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
            'author_id.exists' => 'Tác giả không tồn tại.',
            'category_id.required' => 'Vui lòng chọn chuyên mục.',
            'category_id.exists' => 'Chuyên mục không hợp lệ.',
            'thumbnail.image' => 'File phải là hình ảnh.',
            'thumbnail.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Xoá ảnh cũ nếu có
            if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
                Storage::disk('public')->delete($blog->thumbnail);
            }

            // Lưu ảnh mới
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'thumbnail' => $data['thumbnail'] ?? $blog->thumbnail,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog đã được xóa thành công!');
    }

    /**
     * Generate slug from title
     */
    public function generateSlug(Request $request)
    {
        $title = $request->get('title', '');
        $slug = Str::slug($title);

        // Check if slug exists
        $count = Blog::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        return response()->json(['slug' => $slug]);
    }
}
