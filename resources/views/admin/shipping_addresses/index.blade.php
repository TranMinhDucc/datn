@extends('layouts.admin')

@section('title', 'Danh sách địa chỉ nhận hàng')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Danh sách địa chỉ nhận hàng
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.shipping-addresses.create') }}" class="btn btn-danger">
                    Thêm địa chỉ
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
                                <th>Người dùng</th>
                                <th>Tiêu đề</th>
                                <th>Địa chỉ</th>
                                <th>Mã bưu chính</th>
                                <th>Điện thoại</th>
                                <th>Mặc định</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach ($addresses as $index => $address)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $address->user->fullname ?? 'Không có' }}</td>
                                    <td>{{ $address->title }}</td>
                                    <td>{{ $address->address }}, {{ $address->city }}, {{ $address->state }}, {{ $address->country }}</td>
                                    <td>{{ $address->pincode }}</td>
                                    <td>{{ $address->phone }}</td>
                                    <td>
                                        @if($address->is_default)
                                            <span class="badge badge-light-success">Có</span>
                                        @else
                                            <span class="badge badge-light">Không</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($address->status)
                                            <span class="badge badge-light-success">Hiện</span>
                                        @else
                                            <span class="badge badge-light-danger">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>{{ $address->created_at->format('H:i:s d/m/Y') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-bs-toggle="dropdown">
                                                Hành động <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.shipping-addresses.edit', $address->id) }}" class="dropdown-item">
                                                        <i class="fa fa-edit me-2"></i> Sửa
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.shipping-addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Xoá địa chỉ này?')">
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

                    @if ($addresses instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $addresses->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <!--end::Content-->
</div>
@endsection