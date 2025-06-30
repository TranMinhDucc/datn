@extends('layouts.admin')

@section('title', 'Chi tiết Blog')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body p-12">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column align-items-start flex-xxl-row">
                            <!--begin::Input group-->
                            <div class="d-flex align-items-center flex-equal fw-row me-4 order-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-original-title="Specify invoice date" data-kt-initialized="1">
                                <!--begin::Date-->
                                <div class="fs-6 fw-bold text-gray-700 text-nowrap">Ngày tạo:</div>
                                <!--end::Date-->
                                <!--begin::Input-->
                                <div class="position-relative d-flex align-items-center w-150px">
                                    <span class="fs-6 fw-semibold text-gray-800 ms-2">{{ $blog->created_at->format('d/m/Y') }}</span>
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-original-title="Enter invoice number" data-kt-initialized="1">
                                <span class="fs-2x fw-bold text-gray-800">#{{ $blog->id }}</span>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex align-items-center justify-content-end flex-equal order-3 fw-row" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-original-title="Specify invoice due date" data-kt-initialized="1">
                                <!--begin::Date-->
                                <div class="fs-6 fw-bold text-gray-700 text-nowrap">Xuất bản:</div>
                                <!--end::Date-->
                                <!--begin::Input-->
                                <div class="position-relative d-flex align-items-center w-150px">
                                    @if($blog->published_at)
                                    <span class="fs-6 fw-semibold text-gray-800 ms-2">{{ $blog->published_at->format('d/m/Y') }}</span>
                                    @else
                                    <span class="fs-6 fw-semibold text-gray-800 ms-2">Chưa xuất bản</span>
                                    @endif
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-10"></div>
                        <!--end::Separator-->
                        <!--begin::Order details-->
                        <div class="d-flex flex-column">
                            <!--begin::Title-->
                            <div class="fs-2 fw-bold mb-3">{{ $blog->title }}</div>
                            <!--end::Title-->
                            <!--begin::Slug-->
                            <div class="fs-6 text-muted mb-5">
                                <span class="fw-semibold">Slug:</span> {{ $blog->slug }}
                            </div>
                            <!--end::Slug-->
                            <!--begin::Author-->
                            <div class="fs-6 text-muted mb-8">
                                <span class="fw-semibold">Tác giả:</span> {{ $blog->author->username ?? 'N/A' }}
                            </div>
                            <!--end::Author-->
                            <!--begin::Category-->
                            <div class="fs-6 text-muted mb-8">
                                <span class="fw-semibold">Danh mục:</span>
                                @if($blog->category)
                                <span class="badge badge-light-primary">{{ $blog->category->name }}</span>
                                @else
                                N/A
                                @endif
                            </div>
                            <!--end::Category-->
                            <!--begin::Status-->
                            <div class="fs-6 text-muted mb-8">
                                <span class="fw-semibold">Trạng thái:</span>
                                @if($blog->status === 'published')
                                <span class="badge badge-light-success">Đã xuất bản</span>
                                @elseif($blog->status === 'draft')
                                <span class="badge badge-light-warning">Bản nháp</span>
                                @else
                                <span class="badge badge-light-danger">Không hoạt động</span>
                                @endif
                            </div>
                            <!--end::Status-->
                            <!--begin::Featured Image-->
                            @if($blog->thumbnail)
                            <div class="mb-8">
                                <div class="fs-6 fw-bold text-gray-700 mb-3">Ảnh đại diện:</div>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}" class="rounded mw-100" style="max-height: 300px;">
                                </div>
                            </div>
                            @endif
                            <!--end::Featured Image-->
                            <!--begin::Content-->
                            <div class="mb-8">
                                <div class="fs-6 fw-bold text-gray-700 mb-3">Nội dung:</div>
                                <div class="text-gray-800 fs-6">
                                    {!! $blog->content !!}
                                </div>
                            </div>
                            <!--end::Content-->
                            <!--begin::Meta Description-->
                            @if($blog->meta_description)
                            <div class="mb-8">
                                <div class="fs-6 fw-bold text-gray-700 mb-3">Mô tả SEO:</div>
                                <div class="text-gray-600 fs-6">
                                    {{ $blog->meta_description }}
                                </div>
                            </div>
                            @endif
                            <!--end::Meta Description-->
                            <!-- begin::Tags-->
                            @if($blog->tags && $blog->tags->count() > 0)
                            <div class="mb-8">
                                <div class="fs-6 fw-bold text-gray-700 mb-3">Thẻ:</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($blog->tags as $tag)
                                    <span class="badge badge-light-info">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <!--end::Tags -->
                        </div>
                        <!--end::Order details-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
            <!--begin::Sidebar-->
            <div class="flex-lg-auto min-w-lg-300px">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body p-10">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-bold fs-6 text-gray-700">Thao tác</label>
                            <!--end::Label-->
                            <!--begin::Actions-->
                            <div class="d-flex flex-column gap-5">
                                <a href="{{ route('admin.blogs.edit', $blog->slug) }}" class="btn btn-light-primary">
                                    <i class="fa-solid fa-pen fs-2"></i>
                                    Chỉnh sửa
                                </a>
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light-secondary">
                                    <i class="fa-solid fa-arrow-left fs-2"></i>
                                    Quay lại danh sách
                                </a>
                                @if($blog->status === 'published')
                                <a href="{{ route('client.blog.show', $blog->slug) }}" target="_blank" class="btn btn-light-success">
                                    <i class="fas fa-eye fs-2"></i>
                                    Xem trên website
                                </a>
                                @endif
                                <form action="{{ route('admin.blogs.destroy', $blog->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light-danger w-100">
                                        <i class="fas fa-trash-alt fs-2"></i>
                                        Xóa bài viết
                                    </button>
                                </form>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Statistics-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label fw-bold fs-6 text-gray-700">Thống kê</label>
                            <!--end::Label-->
                            <!--begin::Stats-->
                            <div class="d-flex flex-column gap-5">
                                <div class="d-flex justify-content-between">
                                    <span class="text-gray-600">Lượt xem:</span>
                                    <span class="fw-bold">{{ $blog->views ?? 0 }}</span>
                                </div>
                                <!-- <div class="d-flex justify-content-between">
                                    <span class="text-gray-600">Chia sẻ:</span>
                                    <span class="fw-bold">{{ $blog->shares ?? 0 }}</span>
                                </div> -->
                                <div class="d-flex justify-content-between">
                                    <span class="text-gray-600">Bình luận:</span>
                                    <span class="fw-bold">{{ $blog->comments_count ?? 0 }}</span>
                                </div>
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Statistics-->
                        <!--begin::Comments-->
                        <div class="mb-10">
                            <label class="form-label fw-bold fs-6 text-gray-700">Bình luận</label>
                            <div id="blog-comments" class="d-flex flex-column gap-4" style="max-height: 400px; overflow-y: auto;"></div>
                            <div class="text-center mt-3" id="loading-spinner" style="display: none;">
                                <span class="spinner-border spinner-border-sm text-primary"></span> Đang tải...
                            </div>
                        </div>
                        <!--end::Comments-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Sidebar-->
        </div>
        <!--end::Layout-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
@endsection
@push('scripts')
<script>
    const commentsContainer = document.getElementById('blog-comments');
    const spinner = document.getElementById('loading-spinner');
    let nextPage = '{{ route('admin.blogs.comments', $blog->slug) }}';

    function loadComments() {
        if (!nextPage) return;

        spinner.style.display = 'block';

        fetch(nextPage)
            .then(res => res.json())
            .then(data => {
                data.comments.forEach(html => {
                    const div = document.createElement('div');
                    div.innerHTML = html;
                    commentsContainer.appendChild(div);
                });
                nextPage = data.next_page_url;
                spinner.style.display = 'none';
            });
    }

    // Tải bình luận đầu tiên
    loadComments();

    // Tải thêm khi cuộn xuống cuối container
    commentsContainer.addEventListener('scroll', function () {
        if (commentsContainer.scrollTop + commentsContainer.clientHeight >= commentsContainer.scrollHeight - 50) {
            loadComments();
        }
    });
</script>
@endpush