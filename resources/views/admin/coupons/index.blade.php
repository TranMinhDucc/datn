@extends('layouts.admin')

@section('title', 'Mã giảm giá')

@section('content')
<div class="container">
    <h1 class="mb-4">Danh sách mã giảm giá</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mb-3">+ Thêm mã mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Giảm (%)</th>
                <th>Số lượng</th>
                <th>Đã dùng</th>
                <th>Giá trị tối thiểu</th>
                <th>Giá trị tối đa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->discount }}</td>
                    <td>{{ $coupon->amount }}</td>
                    <td>{{ $coupon->used }}</td>
                    <td>{{ $coupon->min }}</td>
                    <td>{{ $coupon->max }}</td>
                    <td>
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
