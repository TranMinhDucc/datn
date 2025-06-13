@extends('layouts.admin')

@section('title', 'Viết Blog')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')



<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Form-->
        <form id="kt_blog_form" class="form d-flex flex-column flex-lg-row" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Thông tin chung</h2>
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
                                placeholder="Nhập tiêu đề blog" value="{{ old('title') }}" />
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
                                placeholder="duong-dan-than-thien" value="{{ old('slug') }}" />
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
                            <div class="text-muted fs-7 mt-2">Chọn một ảnh đại diện cho bài viết (jpg, png, jpeg...)</div>
                            @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                <option value="{{ $user->id }}" {{ old('author_id') == $user->id ? 'selected' : '' }}>
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
                            <textarea id="kt_blog_content_editor" name="content" class="form-control @error('content') is-invalid @enderror" rows="10">
                            {{ old('content') }}
                            </textarea>
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

                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a href="{{ route('admin.blogs.index') }}" id="kt_blog_cancel" class="btn btn-light me-5">Hủy</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_blog_submit" class="btn btn-primary">
                        <span class="indicator-label">Lưu</span>
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
            extraPlugins: 'colorbutton,font,justify,print,preview',
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
</script>
@endpush