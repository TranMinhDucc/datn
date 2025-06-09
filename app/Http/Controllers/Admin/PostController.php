<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(6);

        $stats = Cache::remember('post_stats', 600, function () {
            return [
                'total' => Post::count(),
                'draft' => Post::where('status', 0)->count(),
                'published' => Post::where('status', 1)->count(),
                'hidden' => Post::where('status', 2)->count(),
                'total_views' => Post::sum('view'),
                'max_views' => Post::max('view') ?? 0,
                'today_views' => Post::whereDate('created_at', today())->sum('view'),
                'popular_posts' => Post::orderBy('view', 'desc')->take(5)->get(),
            ];
        });

        return view('admin.posts.index', compact('posts', 'stats'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PostCategory::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:post_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts,slug',
            'content' => 'required',
            'status' => 'nullable|in:0,1,2',
            'thumbnail' => 'nullable|image'  // trùng với input form
        ]);

        // Tạo slug tự động nếu không nhập
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']) . '-' . time();

        // Mặc định view = 0
        $data['view'] = 0;

        // Xử lý upload ảnh thumbnail nếu có
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('posts', 'public');
        }

        // Ghi dữ liệu vào DB
        Post::create($data);

        Cache::forget('post_stats');

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công');
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = PostCategory::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:post_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $post->id,
            'content' => 'required',
            'status' => 'required|in:0,1,2',
            'thumbnail' => 'nullable|image'
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']) . '-' . $post->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        Cache::forget('post_stats');
        
        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Đã xóa bài viết');
    }

    public function toggleStatus(Post $post)
    {
        $post->status = $post->status == 1 ? 0 : 1;
        $post->save();
        return back()->with('success', 'Đã cập nhật trạng thái');
    }
}
