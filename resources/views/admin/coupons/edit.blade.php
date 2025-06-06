@extends('layouts.admin')

@section('title', 'Cập nhật mã giảm giá')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <div class="card shadow-sm rounded">
        <div class="card-header bg-warning text-white d-flex justify-content-center align-items-center" style="height: 60px;">
    <h2 class="mb-0 m-0">✏️ Cập nhật mã giảm giá</h2>
</div>


        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required placeholder="Nhập mã giảm giá">
                </div>

                <div class="mb-3">
                    <label for="discount" class="form-label">Chiết khấu (%) <span class="text-danger">*</span></label>
                    <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', $coupon->discount) }}" required min="1" max="100" placeholder="Ví dụ: 10">
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Số lượt áp dụng <span class="text-danger">*</span></label>
                    <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount', $coupon->amount) }}" required min="1" placeholder="Số lượt sử dụng tối đa">
                </div>

                <div class="mb-3">
                    <label for="min" class="form-label">Giá trị đơn hàng tối thiểu</label>
                    <input type="number" id="min" name="min" class="form-control" value="{{ old('min', $coupon->min) }}" min="0" placeholder="Không bắt buộc">
                    <small class="text-muted">Để trống nếu không yêu cầu giá trị tối thiểu</small>
                </div>

                <div class="mb-3">
                    <label for="max" class="form-label">Giá trị đơn hàng tối đa</label>
                    <input type="number" id="max" name="max" class="form-control" value="{{ old('max', $coupon->max) }}" min="0" placeholder="Không bắt buộc">
                    <small class="text-muted">Để trống nếu không yêu cầu giá trị tối đa</small>
                </div>

                <div class="mb-3">
                    <label for="expired_at" class="form-label">Hạn sử dụng</label>
                    <input type="date" id="expired_at" name="expired_at" class="form-control"
                        value="{{ old('expired_at', optional(\Carbon\Carbon::parse($coupon->expired_at))->format('Y-m-d')) }}">
                    <small class="text-muted">Chọn ngày hết hạn mã giảm giá (nếu có)</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary me-2">← Quay lại</a>
                    <button type="submit" class="btn btn-success">✔️ Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
