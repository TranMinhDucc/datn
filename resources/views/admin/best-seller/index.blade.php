@extends('layouts.admin')
@section('content')
<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
  <!--begin::Content container-->
  <div id="kt_app_content_container" class="app-container container-xxl">
       <!-- ✅ Page Title -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fw-bold text-dark fs-2">Quản lý Trưng bày</h1>
                <a href="{{ route('admin.best-seller.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus fs-2"></i> Thêm Trưng bày mới
                </a>
            </div>
    <!--begin::Card-->
    <div class="card">
      <!--begin::Card header-->
      <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
          <!-- <form method="GET" action="{{ route('admin.search') }}">
                            <input type="hidden" name="module" value="blogs">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    class="form-control form-control-solid w-250px ps-12" placeholder="Tìm kiếm bài viết" />
                            </div>
                        </form> -->
        </div>


        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
          <!--begin::Toolbar-->
          <!--end::Toolbar-->
        </div>
        <!--end::Card toolbar-->
      </div>
      <!--end::Card toolbar-->
      <!--end::Card header-->
      <!--begin::Card body-->
      <div class="card-body py-4 table-responsive">
        <!--begin::Table-->

        <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_blogs_table">
          <thead class="table-light">
            <tr class="fw-bold text-muted">
              <th class="min-w-50px">ID</th>
              <th class="min-w-200px">Tiêu đề</th>
              <th class="min-w-100px text-center">Kích hoạt</th>
              <th class="min-w-150px">Cập nhật</th>
              <th class="min-w-150px text-end">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $it)
            <tr>
              <td class="text-gray-800 fw-semibold">{{ $it->id }}</td>
              <td class="text-gray-800">{{ $it->title_main }}</td>
              <td class="text-center">
                @if($it->is_active)
                <span class="badge badge-light-success">Yes</span>
                @else
                <span class="badge badge-light-danger">No</span>
                @endif
              </td>
              <td class="text-gray-600">{{ $it->updated_at->format('Y-m-d H:i') }}</td>
              <td class="text-end">
                <a class="btn btn-sm btn-light-primary" href="{{ route('admin.best-seller.edit', $it) }}">
                  <i class="fa-solid fa-pen-to-square"></i> Sửa
                </a>
                <form action="{{ route('admin.best-seller.destroy', $it) }}" method="post" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-light-danger" onclick="return confirm('Xóa mục này?')">
                    <i class="fa-solid fa-trash"></i> Xóa
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <!--end::Table-->
        <!-- Phần pagination được sửa -->
        @if ($items->total() > 0)
        <div class="d-flex flex-stack flex-wrap pt-10">
          <div class="fs-6 fw-semibold text-gray-700">
            Hiển thị {{ $items->firstItem() ?? 0 }} đến {{ $items->lastItem() ?? 0 }}
            trong tổng số {{ $items->total() }} kết quả
          </div>
          @if ($items->hasPages())
          <div class="d-flex align-items-center">
            {{-- Giữ lại tham số search khi phân trang --}}
            {{ $items->appends(request()->query())->links('vendor.pagination.adminPagi') }}
          </div>
          @endif
        </div>
        @endif
      </div>
      <!--end::Card body-->
    </div>
    <!--end::Card-->
  </div>
</div>
<!--end::Content-->
@endsection