@extends('layouts.admin')
@section('title', 'Danh sách đánh giá')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush">
                <!-- Header -->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <form method="GET" action="{{ route('admin.search') }}">
                            <input type="hidden" name="module" value="reviews">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                       class="form-control form-control-solid w-250px ps-12"
                                       placeholder="Tìm kiếm đánh giá (người dùng, sản phẩm)..." />
                            </div>
                        </form>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('admin.badwords.index') }}" class="btn btn-danger me-2">
                            Quản lý từ khóa cấm
                        </a>
                        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                            Thêm đánh giá mới
                        </a>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>STT</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm</th>
                                    <th>Đánh giá</th>
                                    <th>Bình luận</th>
                                    <th>Xác minh</th>
                                    <th>Trạng thái duyệt</th>
                                    <th>Thời gian</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($reviews as $index => $review)
                                    <tr>
                                        <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $index + 1 }}</td>
                                        <td>{{ $review->user->username ?? 'N/A' }}</td>
                                        <td>{{ $review->product->name ?? 'N/A' }}</td>
                                        <td>{{ $review->rating }} <i class="fa fa-star text-warning"></i></td>
                                        <td>{{ $review->comment ?? 'Không có' }}</td>
                                        <td>
                                            <span class="badge {{ $review->verified_purchase ? 'badge-light-success' : 'badge-light-secondary' }}">
                                                {{ $review->verified_purchase ? 'Đã xác minh' : 'Chưa xác minh' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $comment = strtolower($review->comment ?? '');
                                                $isViolated = false;
                                                foreach ($badwords as $word) {
                                                    if (str_contains($comment, strtolower($word))) {
                                                        $isViolated = true;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            @if ($isViolated)
                                                <span class="badge badge-light-danger">Vi phạm</span>
                                            @elseif ($review->approved)
                                                <span class="badge badge-light-primary">Hiển thị công khai</span>
                                            @else
                                                <span class="badge badge-light-warning">Chờ kiểm duyệt</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                 Hành Động <i class="fa fa-chevron-down ms-1"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown w-125px" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.reviews.edit', $review) }}" class="menu-link px-3">Sửa</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.reviews.show', $review) }}" class="menu-link px-3">Xem</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="menu-link px-3 text-danger border-0 bg-transparent">
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Không có đánh giá nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-4">
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                    <!-- End Pagination -->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
</div>
@endsection
