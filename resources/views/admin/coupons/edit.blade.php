@extends('layouts.admin')

@section('title', 'Chỉnh sửa mã giảm giá')

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
                        Chỉnh sửa mã giảm giá
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-light btn-active-light-primary">
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
                <!--begin::Coupon Edit-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Chỉnh sửa mã giảm giá</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu.
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-6">
                                <div class="col-md-6">
                                    <label for="code" class="form-label fw-semibold">Mã giảm giá</label>
                                    <input type="text" name="code" class="form-control form-control-solid" value="{{ $coupon->code }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="discount_type" class="form-label fw-semibold">Loại giảm giá</label>
                                    <select name="discount_type" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Chọn loại" required>
                                        <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                                        <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Cố định</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-md-6">
                                    <label for="discount_value" class="form-label fw-semibold">Giá trị giảm</label>
                                    <input type="number" step="0.01" name="discount_value" class="form-control form-control-solid" value="{{ $coupon->discount_value }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="max_usage" class="form-label fw-semibold">Số lượt sử dụng tối đa</label>
                                    <input type="number" name="max_usage" class="form-control form-control-solid" value="{{ $coupon->max_usage }}" required>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu</label>
                                    <input type="datetime-local" name="start_date" class="form-control form-control-solid" value="{{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label fw-semibold">Ngày kết thúc</label>
                                    <input type="datetime-local" name="end_date" class="form-control form-control-solid" value="{{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-light btn-active-light-primary">
                                    Hủy
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Coupon Edit-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection