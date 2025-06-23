<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\BlogComment;

class BlogCommentController extends Controller
{
    /**
     * Store a new comment for a blog post.
     */
    public function store(Request $request, Blog $blog)
    {
        $rules = [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];

        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules);

        $comment = new BlogComment();
        $comment->blog_id = $blog->id;
        $comment->parent_id = $request->parent_id;
        $comment->content = $request->content;
        $comment->is_approved = 1;

        if (auth()->check()) {
            $comment->user_id = auth()->id();
        } else {
            // Lưu thông tin tạm của guest
            $comment->user_id = null;
            session([
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
            ]);
        }

        $comment->save();

        return redirect()->route('client.blog.show', $blog->slug)->with('success', 'Bình luận thành công!');
    }
}
