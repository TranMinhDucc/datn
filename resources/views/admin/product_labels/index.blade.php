@extends('layouts.admin')

@section('title', 'Danh sách nhãn dán')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Danh sách nhãn dán sản phẩm
                    </h1>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.product-labels.create') }}" class="btn btn-danger">
                        Thêm nhãn
                    </a>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card card-flush">
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>STT</th>
                                    <th>Hình ảnh</th>
                                    <th>Vị trí</th>
                                    <th>Sản phẩm</th>
                                    <th>Thời gian thêm</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($labels as $index => $label)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ asset($label->image) }}" alt="label" width="60"
                                                class="rounded shadow-sm">
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info">{{ $label->position }}</span>
                                        </td>
                                        <td>{{ $label->product->name ?? 'Không có' }}</td>
                                        <td>{{ $label->created_at->format('H:i:s d/m/Y') }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                    data-bs-toggle="dropdown">
                                                    Hành động <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.product-labels.edit', $label->id) }}"
                                                            class="dropdown-item">
                                                            <i class="fa fa-edit me-2"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.product-labels.destroy', $label->id) }}"
                                                            method="POST" onsubmit="return confirm('Xoá nhãn này?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa fa-trash me-2"></i> Xoá
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end mt-4">
                            {{ $labels->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection