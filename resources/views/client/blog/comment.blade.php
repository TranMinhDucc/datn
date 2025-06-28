<div class="comment-thread {{ $comment->parent_id ? 'comment-reply' : 'comment-root' }}">
    <div class="comment-item">
        <div class="comment-avatar">
            <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('assets/client/images/user/1.jpg') }}"
                alt="{{ $comment->user->name }}" class="avatar-img">
        </div>
        <div class="comment-content">
            <div class="comment-header">
                <div class="comment-meta">
                    <h6 class="comment-author">
                        <i class="iconsax" data-icon="user-1"></i>
                        {{ $comment->user?->username ?? session('guest_name', 'Khách') }}
                    </h6>
                    <span class="comment-date">
                        <i class="iconsax" data-icon="clock"></i>
                        {{ $comment->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
            <div class="comment-body">
                <p>{{ $comment->content }}</p>
            </div>
            <div class="comment-actions">
                <button class="reply-button" data-comment-id="{{ $comment->id }}" data-parent-author="{{ $comment->user?->username ?? session('guest_name', 'Guest') }}">
                    <i class="iconsax" data-icon="undo"></i>
                    <span>Reply</span>
                </button>
            </div>
        </div>
    </div>
     <!-- Reply Form (Hidden by default) -->
    <div class="reply-form-container" id="reply-form-{{ $comment->id }}" style="display: none;">
        <form action="{{ route('client.blog.comment.store', $blog) }}" method="POST" class="reply-form">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div class="reply-form-content">
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" placeholder="Write your reply to {{ $comment->user?->username ?? 'this comment' }}..." required></textarea>
                </div>
                
                @guest
                <div class="guest-info-inline">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="guest_name" class="form-control form-control-sm" placeholder="Your Name" 
                                   value="{{ session('guest_name', '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="guest_email" class="form-control form-control-sm" placeholder="Your Email" 
                                   value="{{ session('guest_email', '') }}" required>
                        </div>
                    </div>
                </div>
                @endguest
                
                <div class="reply-form-actions">
                    <button type="submit" class="btn btn-primary btn-sm">Post Reply</button>
                    <button type="button" class="btn btn-secondary btn-sm cancel-reply">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Nested replies --}}
    @if($comment->children->count())
        <div class="comment-replies">
            @foreach($comment->children as $child)
                @include('client.blog.comment', ['comment' => $child])
            @endforeach
        </div>
    @endif
</div>