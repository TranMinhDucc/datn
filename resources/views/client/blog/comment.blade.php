<li class="{{ $comment->parent_id ? 'reply' : '' }}">
    <div class="comment-items">
        <div class="user-img">
            <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('assets/client/images/user/1.jpg') }}"
                alt="{{ $comment->user->name }}">
        </div>
        <div class="user-content">
            <div class="user-info">
                <div class="d-flex justify-content-between gap-3">
                    <h6><i class="iconsax" data-icon="user-1"></i>{{ $comment->user?->username ?? session('guest_name', 'Khách') }}</h6>
                    <span><i class="iconsax" data-icon="clock"></i>{{ $comment->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <p>{{ $comment->content }}</p>
            <a href="#" class="reply-btn" data-comment-id="{{ $comment->id }}">
                <span><i class="iconsax" data-icon="undo"></i> Reply</span>
            </a>
        </div>
    </div>

    {{-- Đệ quy nếu có con, đặt bên trong li --}}
    @if($comment->children->count())
        <ul class="theme-scrollbar">
            @foreach($comment->children as $child)
                @include('client.blog.comment', ['comment' => $child])
            @endforeach
        </ul>
    @endif
</li>