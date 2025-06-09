@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')

@section('content')
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">

        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Edit Post
            </h1>
            <!--end::Title-->

            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
                        Home </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <!--end::Item-->

                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.posts.index') }}" class="text-muted text-hover-primary">
                        Posts Manager </a>
                </li>
                <!--end::Item-->

                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <!--end::Item-->

                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    Edit Post
                </li>
                <!--end::Item-->

            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Back button-->
            <a href="{{ route('admin.posts.index') }}" class="btn btn-sm fw-bold btn-secondary">
                <i class="ki-duotone ki-arrow-left fs-2"></i> Quay lại
            </a>
            <!--end::Back button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                <div class="card">
                    <div class="card-body p-12">
                        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="post_edit_form">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-700">Tiêu đề bài viết</label>
                                <input type="text" name="title" class="form-control form-control-solid" 
                                       placeholder="Nhập tiêu đề bài viết" 
                                       value="{{ old('title', $post->title) }}" required />
                                @error('title')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-700">Slug</label>
                                <input type="text" name="slug" class="form-control form-control-solid" 
                                       placeholder="auto hoặc tự nhập" 
                                       value="{{ old('slug', $post->slug) }}" />
                                @error('slug')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-700">Danh mục bài viết</label>
                                <select name="category_id" class="form-select form-select-solid" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-700">Trạng thái</label>
                                <select name="status" class="form-select form-select-solid" required>
                                    <option value="0" {{ old('status', $post->status) == 0 ? 'selected' : '' }}>Nháp</option>
                                    <option value="1" {{ old('status', $post->status) == 1 ? 'selected' : '' }}>Đã xuất bản</option>
                                    <option value="2" {{ old('status', $post->status) == 2 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-700">Ảnh đại diện</label>
                                @if($post->thumbnail)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($post->thumbnail) }}" alt="Current thumbnail" 
                                             class="img-thumbnail" style="max-width: 200px;">
                                        <p class="text-muted mt-2">Ảnh hiện tại</p>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control form-control-solid" />
                                <div class="form-text">Chọn ảnh mới để thay thế (để trống nếu không muốn thay đổi)</div>
                                @error('image')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold text-gray-700">Nội dung bài viết</label>
                                <textarea name="content" class="form-control form-control-solid" rows="10" 
                                          placeholder="Viết nội dung ở đây...">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                                    <i class="ki-duotone ki-cross fs-2"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i> Cập nhật bài viết
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- end::card-body -->
                </div>
                <!-- end::card -->
            </div>
            <!-- end::content -->
        </div>
    </div>
</div>

@endsection