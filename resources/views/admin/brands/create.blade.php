@extends('layouts.admin')

@section('title', 'Thêm thương hiệu mới')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Thêm thương hiệu mới
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-sm btn-light btn-active-light-primary">
                        <i class="fa-solid fa-arrow-left fs-6 me-1"></i> Quay lại
                    </a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Brand Create-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Thêm thương hiệu mới</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu bạn nhập.
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-6">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Tên thương hiệu <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        class="form-control form-control-solid @error('name') is-invalid @enderror"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">Trạng thái <span
                                            class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                        class="form-select form-select-solid @error('status') is-invalid @enderror"
                                        data-kt-select2="true" data-placeholder="Chọn trạng thái" required>
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-md-6">
                                    <label for="logo" class="form-label fw-semibold">Logo</label>
                                    <input type="file" name="logo" id="logo"
                                        class="form-control form-control-solid @error('logo') is-invalid @enderror"
                                        accept="image/*">
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.brands.index') }}"
                                    class="btn btn-sm btn-light btn-active-light-primary">
                                    Hủy
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Lưu
                                </button>
                            </div>
                        </form>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Brand Create-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection
