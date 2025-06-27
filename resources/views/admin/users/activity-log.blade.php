@extends('layouts.admin')
@section('title', 'Nhật ký hoạt động')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-clock-rotate-left me-2 text-gray-600"></i>
                            Lịch sử hoạt động: <strong>{{ $user->username }}</strong>
                        </h3>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary">
                            ← Quay lại
                        </a>
                    </div>

                    <div class="card-body pt-0">
                        <div class="table-scroll-wrapper" style="overflow-x: auto; width: 100%;">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" style="min-width: 1300px;">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>#</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Hành động</th>
                                        <th>Thời gian</th>
                                        <th class="text-center">IP</th>
                                        <th>Thiết bị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $log)
                                        <tr>
                                            <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $log->id) }}"
                                                    class="text-primary fw-bold text-hover-primary">
                                                    {{ $log->username }}
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $icons = [
                                                        'Login' => 'fa-sign-in-alt text-success',
                                                        'Logout' => 'fa-sign-out-alt text-warning',
                                                        'admin_add_balance' => 'fa-coins text-primary',
                                                        'admin_edit_user' => 'fa-user-edit text-info',
                                                    ];
                                                    $iconClass = $icons[$log->action] ?? 'fa-info-circle text-muted';
                                                @endphp
                                                <i class="fas {{ $iconClass }} me-2"></i>
                                                {{ $log->action }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-light fw-normal border text-gray-700 px-3 py-2 rounded-2">
                                                    {{ $log->created_at->format('H:i d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light-danger fw-semibold">{{ $log->ip_address }}</span>
                                            </td>
                                            <td>
                                                <span class="d-inline-block text-truncate text-muted small"
                                                    title="{{ $log->user_agent }}">
                                                    {{ $log->user_agent }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Phân trang --}}
                            <div class="d-flex justify-content-center mt-3">
                                {{ $activities->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection