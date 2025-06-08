@extends('layouts.admin')

@section('content')
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-1 ">
    <!--begin::Title-->
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
        Tạo danh mục mới
    </h1>
    <!--end::Title-->

    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
                Trang chủ </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-500 w-5px h-2px"></span>
        </li>
        <!--end::Item-->

        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.post-categories.index') }}" class="text-muted text-hover-primary">
                Danh mục </a>
        </li>
        <!--end::Item-->
        
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-500 w-5px h-2px"></span>
        </li>
        <!--end::Item-->

        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            Tạo mới </li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
</div>
<!--end::Page title-->

<!--begin::Actions-->
<div class="d-flex align-items-center gap-2 gap-lg-3 ">
    <!--begin::Back button-->
    <a href="{{ route('admin.post-categories.index') }}" class="btn btn-sm fw-bold btn-secondary">
        <i class="ki-duotone ki-arrow-left fs-2 me-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        Quay lại
    </a>
    <!--end::Back button-->
</div>
<!--end::Actions-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!--begin::Form-->
        <form method="POST" action="{{ route('admin.post-categories.store') }}" class="form d-flex flex-column flex-lg-row">
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
                            <label class="required form-label">Tiêu đề danh mục</label>
                            <!--end::Label-->

                            <!--begin::Input-->
                            <input type="text" name="title" id="title" class="form-control mb-2" placeholder="Nhập tiêu đề danh mục" value="{{ old('title') }}" required />
                            <!--end::Input-->

                            <!--begin::Description-->
                            <div class="text-muted fs-7">Tiêu đề hiển thị của danh mục</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tên danh mục</label>
                            <!--end::Label-->

                            <!--begin::Input-->
                            <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Nhập tên danh mục" value="{{ old('name') }}" required />
                            <!--end::Input-->

                            <!--begin::Description-->
                            <div class="text-muted fs-7">Tên định danh của danh mục (không trùng lặp)</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Biểu tượng</label>
                            <!--end::Label-->

                            <!--begin::Input-->
                            <input type="text" name="icon" class="form-control mb-2" placeholder="Ví dụ: ki-duotone ki-category" value="{{ old('icon') }}" />
                            <!--end::Input-->

                            <!--begin::Description-->
                            <div class="text-muted fs-7">Class CSS của biểu tượng (tùy chọn)</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::General options-->

                <!--begin::Actions-->
                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a href="{{ route('admin.post-categories.index') }}" class="btn btn-light me-5">Hủy</a>
                    <!--end::Button-->

                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Lưu danh mục</span>
                        <span class="indicator-progress">Đang xử lý...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Main column-->

            <!--begin::Aside column-->
            <div class="d-flex flex-column flex-row-auto gap-7 gap-lg-10 w-100 w-lg-300px">
                <!--begin::Status-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Trạng thái</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Select2-->
                        <select class="form-select mb-2" name="status" required>
                            <option value="">Chọn trạng thái...</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        <!--end::Select2-->

                        <!--begin::Description-->
                        <div class="text-muted fs-7">Thiết lập trạng thái hiển thị của danh mục</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Status-->

                <!--begin::Preview-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Xem trước</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="card border h-100 w-100 d-flex flex-column">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-9 flex-shrink-0">
                                <!--begin::Card Title-->
                                <div class="card-title m-0">
                                    <div class="symbol symbol-50px w-50px bg-light">
                                        <i id="preview-icon" class="ki-duotone ki-category fs-2x text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <!--end::Card Title-->

                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <span id="preview-status" class="badge badge-light-success fw-bold px-4 py-3">Hoạt động</span>
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end:: Card header-->

                            <!--begin:: Card body-->
                            <div class="card-body p-9 d-flex flex-column flex-grow-1">
                                <!--begin::Title-->
                                <div class="fs-3 fw-bold text-gray-900 mb-3" id="preview-title">
                                    Tiêu đề danh mục
                                </div>
                                <!--end::Title-->

                                <!--begin::Name-->
                                <p class="text-gray-600 fw-semibold fs-5 mb-7 flex-grow-1" id="preview-name">
                                    Tên: Tên danh mục
                                </p>
                                <!--end::Name-->
                            </div>
                            <!--end:: Card body-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Preview-->
            </div>
            <!--end::Aside column-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview functionality
    const titleInput = document.getElementById('title');
    const nameInput = document.getElementById('name');
    const iconInput = document.querySelector('input[name="icon"]');
    const statusSelect = document.querySelector('select[name="status"]');
    
    const previewTitle = document.getElementById('preview-title');
    const previewName = document.getElementById('preview-name');
    const previewIcon = document.getElementById('preview-icon');
    const previewStatus = document.getElementById('preview-status');

    // Update preview on input change
    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Tiêu đề danh mục';
    });

    nameInput.addEventListener('input', function() {
        previewName.textContent = 'Tên: ' + (this.value || 'Tên danh mục');
    });

    iconInput.addEventListener('input', function() {
        if (this.value) {
            previewIcon.className = this.value + ' fs-2x text-primary';
        } else {
            previewIcon.className = 'ki-duotone ki-category fs-2x text-primary';
            previewIcon.innerHTML = '<span class="path1"></span><span class="path2"></span>';
        }
    });

    statusSelect.addEventListener('change', function() {
        if (this.value === '1') {
            previewStatus.className = 'badge badge-light-success fw-bold px-4 py-3';
            previewStatus.textContent = 'Hoạt động';
        } else if (this.value === '0') {
            previewStatus.className = 'badge badge-light-warning fw-bold px-4 py-3';
            previewStatus.textContent = 'Không hoạt động';
        } else {
            previewStatus.className = 'badge badge-light fw-bold px-4 py-3';
            previewStatus.textContent = 'Chưa chọn';
        }
    });
});
</script>

@endsection