@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card">
            <div class="card-body p-12">
                <h2 class="mb-8">Chi tiết bài viết</h2>

                <div class="mb-5">
                    <label class="form-label fs-6 fw-bold text-gray-700">Tiêu đề</label>
                    <div class="form-control form-control-solid">{{ $post->title }}</div>
                </div>

                <div class="mb-5">
                    <label class="form-label fs-6 fw-bold text-gray-700">Slug</label>
                    <div class="form-control form-control-solid">{{ $post->slug }}</div>
                </div>

                <div class="mb-5">
                    <label class="form-label fs-6 fw-bold text-gray-700">Danh mục</label>
                    <div class="form-control form-control-solid">
                        {{ $post->category?->title ?? 'Không có' }}
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label fs-6 fw-bold text-gray-700">Ảnh đại diện</label><br>
                    @if($post->thumbnail)
                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded w-25">
                    @else
                    <span class="text-muted">Không có ảnh</span>
                    @endif
                </div>

                <div class="mb-5">
                    <label class="form-label fs-6 fw-bold text-gray-700">Nội dung</label>
                    <div class="p-4 border rounded bg-light text-gray-800">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-10">
                    <!-- Nút ẩn -->
                    @if ($post->status !== 2)
                    <form action="{{ route('admin.posts.toggle-status', $post) }}" method="POST" class="me-3" onsubmit="return confirm('Bạn có chắc chắn muốn ẩn bài viết này?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-light-danger">
                            <i class="ki-duotone ki-eye-off fs-2"></i> Ẩn bài viết
                        </button>
                    </form>
                    @endif

                    <!-- Nút xoá -->
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="me-3" onsubmit="return confirm('Bạn có chắc chắn muốn xoá bài viết này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ki-duotone ki-trash fs-2"></i> Xoá
                        </button>
                    </form>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning me-3">
                        <i class="ki-duotone ki-pencil fs-2"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                        <i class="ki-duotone ki-arrow-left fs-2"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection