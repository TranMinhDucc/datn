<div class="comment border p-3 rounded bg-light">
    <div class="fw-bold mb-1">{{ $comment->user->username ?? 'Ẩn danh' }}</div>
    <div class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
    <div class="mt-2">{{ $comment->content }}</div>

    @if($comment->children && $comment->children->count())
        <div class="ms-4 mt-3 border-start ps-3">
            @foreach($comment->children as $child)
                @include('admin.blogs._comment_item', ['comment' => $child])
            @endforeach
        </div>
    @endif
</div>