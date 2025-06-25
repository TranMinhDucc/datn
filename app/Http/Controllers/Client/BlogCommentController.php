<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\BlogComment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BlogCommentController extends Controller
{
    /**
     * Store a new comment for a blog post.
     */
    public function store(Request $request, Blog $blog)
    {
        $rules = [
            'content' => 'required|string|max:1000|min:3',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];

        // Validate guest information if not authenticated
        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:255|min:2';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules, [
            'content.required' => 'Comment content is required.',
            'content.min' => 'Comment must be at least 3 characters long.',
            'content.max' => 'Comment cannot exceed 1000 characters.',
            'guest_name.required' => 'Name is required.',
            'guest_name.min' => 'Name must be at least 2 characters long.',
            'guest_email.required' => 'Email is required.',
            'guest_email.email' => 'Please enter a valid email address.',
            'parent_id.exists' => 'Invalid parent comment.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Check if parent comment belongs to the same blog
        if ($request->parent_id) {
            $parentComment = BlogComment::find($request->parent_id);
            if (!$parentComment || $parentComment->blog_id !== $blog->id) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parent comment.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Invalid parent comment.');
            }
        }

        try {
            $comment = new BlogComment();
            $comment->blog_id = $blog->id;
            $comment->parent_id = $request->parent_id;
            $comment->content = strip_tags($request->content); // Basic XSS protection
            $comment->is_approved = 1; // Auto-approve for now

            if (auth()->check()) {
                $comment->user_id = auth()->id();
            } else {
                // Store guest information
                $comment->user_id = null;
                $comment->guest_name = $request->guest_name;
                $comment->guest_email = $request->guest_email;

                // Save to session for future comments
                session([
                    'guest_name' => $request->guest_name,
                    'guest_email' => $request->guest_email,
                ]);
            }

            $comment->save();

            // Load relationships for response
            $comment->load('user', 'children.user');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment posted successfully!',
                    'comment' => [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'author' => $comment->user?->username ?? $comment->guest_name,
                        'avatar' => $comment->user?->avatar ?? null,
                        'created_at' => $comment->created_at->format('M d, Y'),
                        'parent_id' => $comment->parent_id,
                    ]
                ]);
            }

            $message = $request->parent_id ? 'Reply posted successfully!' : 'Comment posted successfully!';
            return redirect()->route('client.blog.show', $blog->slug)
                ->with('success', $message)
                ->withFragment('comment-' . $comment->id);
        } catch (\Exception $e) {
            Log::error('Comment save error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while posting your comment. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while posting your comment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete a comment (for authenticated users only)
     */
    public function destroy(Request $request, Blog $blog, BlogComment $comment)
    {
        // Check if user can delete this comment
        if (!auth()->check() || (auth()->id() !== $comment->user_id && !auth()->user()->is_admin)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this comment.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized to delete this comment.');
        }

        // Check if comment belongs to this blog
        if ($comment->blog_id !== $blog->id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Comment not found.');
        }

        try {
            // Soft delete to preserve reply structure
            $comment->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment deleted successfully.'
                ]);
            }

            return redirect()->route('client.blog.show', $blog->slug)
                ->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Comment delete error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the comment.'
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while deleting the comment.');
        }
    }

    /**
     * Load more comments (for pagination)
     */
    public function loadMore(Request $request, Blog $blog)
    {
        $page = $request->get('page', 1);
        $perPage = 10;

        $comments = BlogComment::where('blog_id', $blog->id)
            ->whereNull('parent_id')
            ->with(['user', 'children.user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        if ($request->ajax()) {
            $html = '';
            foreach ($comments as $comment) {
                $html .= view('client.blog.comment', compact('comment'))->render();
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'has_more' => $comments->hasMorePages(),
                'next_page' => $comments->currentPage() + 1
            ]);
        }

        return redirect()->route('client.blog.show', $blog->slug);
    }
}
