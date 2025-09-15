@extends('layouts.admin')
@section('title', 'Banner')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="fa fa-search fs-4 position-absolute ms-4"></i>
                            <input type="text" class="form-control form-control-solid w-250px ps-12"
                                placeholder="Search Banner" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Thêm Banner</a>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">

                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-125px">Ảnh chính</th>
                                    <th>Nội dung</th>
                                    <!-- <th>Phụ 1</th>
                                     <th>Phụ 2</th> -->
                                    <th class="w-125px">Trạng thái</th>
                                    <th class="text-end w-150px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($banners as $banner)
                                <tr>
                                    <td>
                                        <div class="symbol symbol-75px">
                                            <img src="{{ asset('storage/' . $banner->main_image) }}"
                                                alt="main image" style="object-fit: cover;" />
                                        </div>
                                    </td>
                                    {{-- Nội dung (subtitle / title / description) --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-muted small">Subtitle</span>
                                            <strong class="mb-1">{{ $banner->subtitle }}</strong>

                                            <span class="text-muted small">Title</span>
                                            <span class="mb-1">{{ $banner->title }}</span>

                                            <span class="text-muted small">Description</span>
                                            <span class="text-truncate" style="max-width: 420px;">
                                                {{ Str::limit(strip_tags($banner->description), 120) }}
                                            </span>
                                        </div>
                                    </td>
                                    <!-- 

                                    <td>
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $banner->sub_image_1) }}" width="60" />
                                        </div>
                                        {{ $banner->sub_image_1_name }}<br>
                                        <strong>{{ number_format($banner->sub_image_1_price, 0, ',', '.') }}₫</strong>
                                    </td>

                                    <td>
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $banner->sub_image_2) }}" width="60" />
                                        </div>
                                        {{ $banner->sub_image_2_name }}<br>
                                        <strong>{{ number_format($banner->sub_image_2_price, 0, ',', '.') }}₫</strong>
                                    </td> -->
                                    <td>
                                        <div class="mb-2">
                                            <div class="badge badge-light-success fw-bold">


                                                @if ($banner->status)
                                                <span class="badge bg-success">Hiển thị</span>
                                                @else
                                                <span class="badge bg-secondary">Ẩn</span>
                                                @endif

                                            </div>

                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Hành động <i class="fa fa-chevron-down ms-1"></i>
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown w-125px" data-kt-menu="true">
                                                <!-- Edit -->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="menu-link px-3">Sửa</a>
                                                </div>

                                                <!-- Show -->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.banners.show', $banner->id) }}" class="menu-link px-3">Xem</a>
                                                </div>

                                                <!-- Delete -->
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này không?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="menu-link px-3 text-primary w-100" style="background: none; border: none;">
                                                            Xoá
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>


                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $banners->links('pagination::bootstrap-5') }}

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


@endsection