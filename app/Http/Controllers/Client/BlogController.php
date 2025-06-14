<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Hiển thị danh sách bài viết blog.
     */
    public function index(Request $request)
    {
        // $search = $request->input('search');

        $blogs = Blog::with('author')
            // published()
            // ->when($search, function ($query, $search) {
            //     return $query->search($search);
            // })
            ->latest()
            ->paginate(10);

        return view('client.blog.index', compact('blogs'));
    }

    /**
     * Hiển thị chi tiết một bài viết blog.
     */
    public function show(Blog $blog)
    {
        return view('client.blog.show', compact('blog'));
    }
}
