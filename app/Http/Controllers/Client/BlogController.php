<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    /**
     * Hiển thị danh sách bài viết blog.
     */
    public function index(Request $request)
    {
        $topViewedBlogs = Blog::published()
            ->orderByDesc('views')
            ->take(4)
            ->get();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 12); // Số bài viết mỗi trang, mặc định 12
        $categories = BlogCategory::withCount('blogs')->get();
        $blogs = Blog::with('author')
            ->published()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
                //    ->orWhere('excerpt', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);

        // Append search parameter to pagination links
        $blogs->appends($request->query());

        return view('client.blog.index', compact('blogs', 'search', 'categories', 'topViewedBlogs'));
    }

    /**
     * Hiển thị chi tiết một bài viết blog.
     */
    public function show(Blog $blog)
    {
        $categories = BlogCategory::withCount('blogs')->get();
        // Increment view count if needed
        $blog->increment('views');
        $topViewedBlogs = Blog::published()
            ->orderByDesc('views')
            ->take(4)
            ->get();
        // Load related data
        $blog->load('author', 'category');
        // Load comments with nested replies
        $blog->load([
            'comments' => function ($q) {
                $q->with('children')->latest();
            },
        ]);

        // Get related posts
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('client.blog.show', compact('blog', 'relatedBlogs', 'categories', 'topViewedBlogs'));
    }
}
