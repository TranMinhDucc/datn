@extends('layouts.admin')

@section('title', 'Danh sách mã giảm giá')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Danh sách mã giảm giá</h3>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Thêm mới
        </a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Đã dùng</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                        <td>
                            {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . 'đ' }}
                        </td>
                        <td>{{ $coupon->usage_count }}/{{ $coupon->max_usage }}</td>
                        <td>
                            {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y H:i') : '-' }} <br>
                            đến <br>
                            {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có mã giảm giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
