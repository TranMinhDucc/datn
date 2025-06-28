<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function loadByBlog(Request $request, Blog $blog)
    {
        $comments = BlogComment::with(['user', 'children.user'])
            ->where('blog_id', $blog->id)
            ->where('is_approved', true)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return view('admin.blogs._comment_item', compact('comment'))->render();
            }),
            'next_page_url' => $comments->nextPageUrl(),
        ]);
    }
}
