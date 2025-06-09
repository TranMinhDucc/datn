@extends('layouts.admin')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa mã giảm giá</h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu.
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="code" class="form-label">Mã giảm giá</label>
                    <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required>
                </div>

                <div class="col-md-6">
                    <label for="discount_type" class="form-label">Loại giảm giá</label>
                    <select name="discount_type" class="form-select" required>
                        <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Cố định</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="discount_value" class="form-label">Giá trị giảm</label>
                    <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ $coupon->discount_value }}" required>
                </div>

                <div class="col-md-6">
                    <label for="max_usage" class="form-label">Số lượt sử dụng tối đa</label>
                    <input type="number" name="max_usage" class="form-control" value="{{ $coupon->max_usage }}" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Ngày bắt đầu</label>
                    <input type="datetime-local" name="start_date" class="form-control"
                        value="{{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">Ngày kết thúc</label>
                    <input type="datetime-local" name="end_date" class="form-control"
                        value="{{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') }}" required>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
