@extends('layouts.admin')

@section('title', 'Chỉnh sửa Blog')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Form-->
        <form id="kt_blog_form" class="form d-flex flex-column flex-lg-row" action="{{ route('admin.blogs.update', $blog->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Chỉnh sửa blog</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tiêu đề blog</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="title" class="form-control mb-2 @error('title') is-invalid @enderror"
                                placeholder="Nhập tiêu đề blog" value="{{ old('title', $blog->title) }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Tiêu đề blog phải rõ ràng và dễ hiểu.</div>
                            <!--end::Description-->
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Slug</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="slug" class="form-control mb-2 @error('slug') is-invalid @enderror"
                                placeholder="duong-dan-than-thien" value="{{ old('slug', $blog->slug) }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Đường dẫn thân thiện cho URL (chỉ chứa chữ cái, số và dấu gạch ngang).</div>
                            <!--end::Description-->
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Ảnh đại diện</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="file" name="thumbnail" accept="image/*"
                                class="form-control @error('thumbnail') is-invalid @enderror" />
                            <!--end::Input-->
                            <!-- Hiển thị ảnh được chọn -->
                            <div class="mt-3" id="previewContainer" style="display: none;">
                                <label class="form-label d-block">Ảnh đang chọn:</label>
                                <img id="thumbnailPreview" src="#" alt="Preview ảnh đại diện"
                                    class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            <!-- Nếu đang sửa blog cũ có ảnh -->
                            @if(!empty($blog->thumbnail))
                            <div class="mt-3" id="oldThumbnailContainer">
                                <label class="form-label d-block">Ảnh hiện tại:</label>
                                <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="Ảnh đại diện cũ"
                                    class="img-thumbnail" style="max-height: 200px;">
                            </div>
                            @endif

                            <div class="text-muted fs-7 mt-2">Chọn một ảnh đại diện cho bài viết (jpg, png, jpeg...)</div>
                            @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="form-label required">Chuyên mục</label>
                            <!--end::Label-->

                            <!--begin::Select-->
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn chuyên mục --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <!--end::Select-->

                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="text-muted fs-7 mt-2">Chọn chuyên mục cho bài viết.</div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Tác giả</label>
                            <!--end::Label-->
                            <!--begin::Select-->
                            <select name="author_id" class="form-select mb-2 @error('author_id') is-invalid @enderror"
                                data-control="select2" data-placeholder="Chọn tác giả">
                                <option></option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('author_id', $blog->author_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->username }}
                                </option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                            @error('author_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Nội dung</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <div id="kt_blog_content_editor" style="height: 300px;">
                                {!! old('content', $blog->content) !!}
                            </div>
                            <textarea name="content" class="d-none @error('content') is-invalid @enderror">{{ old('content', $blog->content) }}</textarea>
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7 mt-2">Viết nội dung blog chi tiết và hấp dẫn.</div>
                            <!--end::Description-->
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::General options-->

                <!--begin::Blog info-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Thông tin blog</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-5">
                                    <label class="form-label fw-semibold">ID:</label>
                                    <span class="text-gray-800">#{{ $blog->id }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-5">
                                    <label class="form-label fw-semibold">Ngày tạo:</label>
                                    <span class="text-gray-800">{{ $blog->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Blog info-->

                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a href="{{ route('admin.blogs.index') }}" id="kt_blog_cancel" class="btn btn-light me-5">Hủy</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_blog_submit" class="btn btn-primary">
                        <span class="indicator-label">Cập nhật</span>
                        <span class="indicator-progress">Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <!--end::Main column-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        CKEDITOR.replace('kt_blog_content_editor', {
            language: 'vi',
            height: 400,
            removeButtons: '', // Để giữ nguyên tất cả các nút
            extraPlugins: 'uploadimage,colorbutton,font,justify,print,preview',
            filebrowserUploadUrl: "{{ route('admin.ckeditor.upload') }}?_token={{ csrf_token() }}",
            filebrowserUploadMethod: 'form',
            removeDialogTabs: 'image:advanced;image:Link',
            removePlugins: 'exportpdf',
        });

        // Tự tạo slug như cũ
        const titleInput = document.querySelector('input[name="title"]');
        const slugInput = document.querySelector('input[name="slug"]');

        titleInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[đĐ]/g, 'd')
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('thumbnailInput');
        const preview = document.getElementById('thumbnailPreview');
        const previewContainer = document.getElementById('previewContainer');
        const oldThumb = document.getElementById('oldThumbnailContainer');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    if (oldThumb) oldThumb.style.display = 'none'; // Ẩn ảnh cũ nếu có
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                if (oldThumb) oldThumb.style.display = 'block';
            }
        });
    });
</script>
@endpush