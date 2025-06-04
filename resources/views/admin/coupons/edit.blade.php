@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Cập nhật mã giảm giá</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mã giảm giá</label>
            <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required>
        </div>

        <div class="mb-3">
            <label>Phần trăm giảm (%)</label>
            <input type="number" name="discount" class="form-control" value="{{ $coupon->discount }}" required min="1" max="100">
        </div>

        <div class="mb-3">
            <label>Số lượt áp dụng</label>
            <input type="number" name="amount" class="form-control" value="{{ $coupon->amount }}" required min="1">
        </div>

        <div class="mb-3">
            <label>Giá trị tối thiểu</label>
            <input type="number" name="min" class="form-control" value="{{ $coupon->min }}" min="0">
        </div>

        <div class="mb-3">
            <label>Giá trị tối đa</label>
            <input type="number" name="max" class="form-control" value="{{ $coupon->max }}" min="0">
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
