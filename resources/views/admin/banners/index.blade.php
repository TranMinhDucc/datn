@extends('layouts.admin') {{-- Layout Metronic --}}
@section('title', 'Banner')
@section('content')
<div class="d-flex flex-column flex-column-fluid">

 <div id="kt_app_content" class="app-content  flex-column-fluid ">
      <div id="kt_app_content_container" class="app-container  container-xxl ">
    <div class="card card-flush">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="fa fa-search fs-4 position-absolute ms-4"></i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Search Banner" />
                </div>
            </div>

            <div class="card-toolbar">
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Add Banner</a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th>Banner</th>
                        <th>Nội dung</th>
                        <th>Thứ tự</th>
                        <th>Ngôn ngữ</th>
                        <th>Status</th>

                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($banners as $banner)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label"
                                            style="background-image:url('{{ $banner->hinh_anh }}'); background-size: cover;"></span>
                                    </div>
                                    <div>
                                        <div class="fs-5 fw-bold text-gray-900">{{ $banner->ten }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{!! Str::limit(strip_tags($banner->mo_ta), 80) !!}</td>
                            <td>{{ $banner->thu_tu }}</td>
                            <td>
                                <div class="badge badge-light">{{ strtoupper($banner->ngon_ngu) }}</div>
                            </td>
                            <td>
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $banner->id }}"
                                        @checked($banner->status) />
                                </div>
                            </td>


                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click"
                                        data-kt-menu-placement="bottom-end">
                                        Actions <i class="fa fa-chevron-down ms-1"></i>
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown w-125px" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                                class="menu-link px-3">Edit</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this banner?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="menu-link px-3 btn btn-link p-0 text-start">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.toggle-status').forEach(switchEl => {
            switchEl.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const status = this.checked ? 1 : 0;

                fetch(`/admin/banners/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: status })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Đã có lỗi xảy ra!');
                            this.checked = !this.checked;
                        }
                    })
                    .catch(() => {
                        alert('Không thể kết nối đến server!');
                        this.checked = !this.checked;
                    });
            });
        });
    </script>
@endpush