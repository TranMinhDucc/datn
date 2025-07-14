@extends('layouts.admin')

@section('title', 'Chi tiết Banner')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush">
                <div class="card-header py-5">
                    <h3 class="card-title">Chi tiết Banner</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Ảnh chính --}}
                    <div class="mb-5">
                        <label class="fw-bold fs-6 text-gray-700">Ảnh chính</label><br>
                        <img src="{{ asset('storage/' . $banner->main_image) }}" class="img-thumbnail mt-2" style="max-width: 300px;">
                    </div>

                    {{-- Tiêu đề & mô tả --}}
                    <div class="mb-5">
                        <label class="fw-bold fs-6 text-gray-700">Tiêu đề & Mô tả</label>
                        <div class="mt-2">
                            <strong class="d-block text-dark">{{ $banner->subtitle }}</strong>
                            <span class="d-block">{{ $banner->title }}</span>
                            <p class="text-muted mt-1">{{ $banner->description }}</p>
                        </div>
                    </div>

                    {{-- Ảnh phụ 1 --}}
                    <div class="mb-5">
                        <label class="fw-bold fs-6 text-gray-700">Ảnh phụ 1</label>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('storage/' . $banner->sub_image_1) }}" class="img-thumbnail me-3" style="width: 100px;">
                            <div>
                                <div class="fw-semibold">{{ $banner->sub_image_1_name }}</div>
                                <div class="text-primary fw-bold">{{ number_format($banner->sub_image_1_price, 0, ',', '.') }}₫</div>
                            </div>
                        </div>
                    </div>

                    {{-- Ảnh phụ 2 --}}
                    <div class="mb-5">
                        <label class="fw-bold fs-6 text-gray-700">Ảnh phụ 2</label>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('storage/' . $banner->sub_image_2) }}" class="img-thumbnail me-3" style="width: 100px;">
                            <div>
                                <div class="fw-semibold">{{ $banner->sub_image_2_name }}</div>
                                <div class="text-primary fw-bold">{{ number_format($banner->sub_image_2_price, 0, ',', '.') }}₫</div>
                            </div>
                        </div>
                    </div>

                    {{-- Quay lại --}}
                    <div class="mt-5">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
