@extends('layouts.admin')

@section('title', 'Mã giảm giá')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách mã giảm giá</h5>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-light text-primary btn-sm fw-bold">
                    + Thêm mã mới
                </a>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mã</th>
                                <th>Giảm (%)</th>
                                <th>Số lượng</th>
                                <th>Đã dùng</th>
                                <th>Giá trị tối thiểu</th>
                                <th>Giá trị tối đa</th>
                                <th>Hạn sử dụng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $index => $coupon)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-uppercase">{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount }}%</td>
                                    <td>{{ $coupon->amount }}</td>
                                    <td>{{ $coupon->used }}</td>
                                    <td>{{ number_format($coupon->min) }}₫</td>
                                    <td>{{ number_format($coupon->max) }}₫</td>
                                    <td>
                                        @if ($coupon->expired_at)
                                            @php
                                                $expired = \Carbon\Carbon::parse($coupon->expired_at);
                                                $now = \Carbon\Carbon::now();
                                            @endphp

                                            @if ($expired->lt($now))
                                                {{-- expired_at < now => hết hạn --}}
                                                <span class="badge bg-danger">
                                                    {{ $expired->format('d/m/Y') }} (Hết hạn)
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    {{ $expired->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Không giới hạn</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                class="btn btn-sm btn-warning">Sửa</a>
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Xoá</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Không có mã giảm giá nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
