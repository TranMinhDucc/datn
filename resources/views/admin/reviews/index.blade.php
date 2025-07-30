@extends('layouts.admin')

@section('title', 'Đánh giá')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Danh sách đánh giá
                    </h1>
                </div>
  <div class="d-flex align-items-center gap-1 gap-lg-2">
    <a href="{{ route('admin.badwords.index') }}" class="btn btn-danger">
        Quản lý từ khóa cấm
    </a>
    <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
        Thêm đánh giá mới
    </a>
</div>

                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
    <form method="GET" action="{{ route('admin.search') }}">
        <input type="hidden" name="module" value="reviews">
        <div class="d-flex align-items-center position-relative my-1">
            <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                   class="form-control form-control-solid w-250px ps-12"
                   placeholder="Tìm đánh giá" />
        </div>
    </form>
</div>

                    </div>

                    <div class="card-body pt-0">
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
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach($reviews as $index => $review)
                                    <tr>
                                        {{-- Tính STT dựa trên paginate --}}
                                        <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $index + 1 }}</td>
                                        <td>{{ $review->user->username ?? 'N/A' }}</td>
                                        <td>{{ $review->product->name ?? 'N/A' }}</td>
                                        <td>{{ $review->rating }} <i class="fa fa-star text-warning"></i></td>
                                        <td>{{ $review->comment ?? 'Không có' }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $review->verified_purchase ? 'badge-light-success' : 'badge-light-secondary' }}">
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
        <span class="badge bg-danger">Vi phạm</span>
    @elseif ($review->approved)
        <span class="badge bg-primary">Hiển thị công khai</span>
    @else
        <span class="badge bg-warning text-dark">Chờ kiểm duyệt</span>
    @endif
</td>


                                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                    data-bs-toggle="dropdown">
                                                    Hành động <i class="fa-solid fa-arrow-down fs-9 ms-2"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.reviews.edit', $review) }}"
                                                            class="dropdown-item">Sửa</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.reviews.show', $review) }}"
                                                            class="dropdown-item">Xem</a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.reviews.destroy', $review) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Bạn chắc chắn muốn xóa?')"
                                                                class="dropdown-item text-danger">
                                                                Xóa
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

                        {{-- Pagination links --}}
                       <div class="d-flex justify-content-end mt-4">
  {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}

</div>
{{-- end pagination  --}}
                    </div>
                </div>

            </div>
        </div>
        <!--end::Content-->

    </div>
@endsection