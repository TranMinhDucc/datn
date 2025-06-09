@extends('layouts.admin')

@section('title', 'Danh sách mã giảm giá')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-center align-items-center py-5 rounded-top">
    <h2 class="mb-0 fw-bold text-uppercase">Danh sách mã giảm giá</h2>
</div>


        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="d-flex justify-content-start mb-3">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i> Tạo mã giảm giá mới
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Mã</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Sử dụng</th>
                            <th>Thời gian</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
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
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>

                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-muted">Không có mã giảm giá nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
