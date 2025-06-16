@extends('layouts.admin') {{-- Layout Metronic --}}
@section('title', 'Danh sách người dùng')
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
                            <form method="GET" action="{{ route('admin.users.index') }}"
                                class="d-flex align-items-center position-relative my-1">
                                <i class="fa fa-search fs-4 position-absolute ms-4"></i>
                                <input type="text" name="search" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Tìm kiếm người dùng" value="{{ request('search') }}">
                            </form>
                        </div>


                        <div class="card-toolbar">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Thêm người dùng</a>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Thông tin</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Điểm</th>
                                        <th>Giới tính</th>
                                        <th>Trạng thái</th>
                                        <th>Vai trò</th>
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{-- <div class="symbol symbol-50px me-5">
                                                        <div class="symbol-label">
                                                            @php
                                                            $avatarPath = $user->avatar ? 'storage/' . $user->avatar :
                                                            'assets/default-avatar.png';
                                                            @endphp
                                                            <img src="{{ $avatarPath }}" width="60" alt="Avatar"
                                                                onerror="this.onerror=null;this.src='{{ asset('assets/default-avatar.png') }}';">
                                                        </div>
                                                    </div> --}}
                                                    <div>
                                                        <div class="fs-5 fw-bold text-gray-900">{{ $user->username }}</div>
                                                        <div class="text-muted">{{ $user->fullname ?? '—' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? '—' }}</td>
                                            <td>{{ $user->address ?? '—' }}</td>
                                            <td>{{ $user->point ?? 0 }}</td>
                                            <td>{{ $user->gender ?? '—' }}</td>
                                            <td>
                                                <div
                                                    class="badge {{ $user->banned ? 'badge-light-danger' : 'badge-light-success' }}">
                                                    {{ $user->banned ? 'Bị khóa' : 'Hoạt động' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="badge badge-light-primary">
                                                    {{ $user->role == 'admin' ? 'Quản trị viên' : 'Người dùng' }}
                                                </div>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light btn-active-light-primary"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        Actions <i class="fa fa-chevron-down ms-1"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown w-125px" data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                                class="menu-link px-3">Edit</a>
                                                        </div>
                                                        <div class="menu-item px-3">

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
                const status = this.checked ? 0 : 1; // 0: active, 1: banned

                fetch(`/admin/users/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ banned: status })
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