@extends('layouts.admin')
@section('title', 'Biến động số dư')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-clock-rotate-left me-2 text-gray-600"></i>
                            Biến động số dư: <strong>{{ $user->username }}</strong>
                        </h3>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary">
                            ← Quay lại
                        </a>
                    </div>


                    <div class="card-body pt-0">
                        <div style="overflow-x: auto; width: 100%;">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" style="min-width: 1200px;">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>#</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Biến động</th>
                                        <th>Số dư trước</th>
                                        <th>Số dư sau</th>
                                        <th>Thời gian</th>
                                        <th>Nội dung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $log)
                                        <tr>
                                            <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $log->user_id) }}"
                                                    class="text-primary fw-bold text-hover-primary">
                                                    {{ $log->user->username }}
                                                </a>
                                                <br>
                                                <small class="text-muted">ID {{ $log->user_id }}</small>
                                            </td>

                                            <td>
                                                @php
                                                    $icon = $log->type === 'add' ? 'bi-arrow-up-circle text-success' : 'bi-arrow-down-circle text-danger';
                                                @endphp
                                                <span
                                                    class="badge bg-light {{ $log->type === 'add' ? 'text-success' : 'text-danger' }} fw-semibold">
                                                    {{-- <i class="bi {{ $icon }}"></i> --}}
                                                    {{ $log->type === 'add' ? '+' : '-' }}{{ number_format($log->amount) }}đ
                                                </span>
                                            </td>
                                            <td>{{ number_format($log->balance_before) }}đ</td>
                                            <td>{{ number_format($log->balance_after) }}đ</td>
                                            <td>
                                                <span
                                                    class="badge badge-light fw-normal border text-gray-700 px-3 py-2 rounded-2">
                                                    <i class="bi bi-clock me-1"></i> {{ $log->created_at->format('H:i d/m/Y') }}
                                                </span>
                                            </td>

                                            <td class="text-muted text-start small text-wrap">
                                                {{ $log->description }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $transactions->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection