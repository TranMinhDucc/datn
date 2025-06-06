@extends('layouts.admin')

@section('title', 'Tạo mã giảm giá mới')

@section('content')
    <div class="container py-4" style="max-width: 720px;">
        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-center align-items-center" style="height: 70px;">
                <h2 class="mb-0">+ Tạo mã giảm giá mới</h2>
            </div>

            <div class="card-body p-3">

                {{-- Hiển thị lỗi validate --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.coupons.store') }}" method="POST" novalidate>
                    @csrf

                    {{-- Mã giảm giá --}}
                    <div class="mb-2">
                        <label for="coupon-code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="code" id="coupon-code" class="form-control"
                                value="{{ old('code') }}" required placeholder="Nhập mã giảm giá">
                            <button type="button" class="btn btn-danger" onclick="generateRandomCode()">Tạo mã ngẫu nhiên</button>
                        </div>
                    </div>

                    {{-- Số lượng mã giảm giá --}}
                    <div class="mb-2" style="max-width: 220px;">
                        <label for="amount" class="form-label">Số lượng <span class="text-danger">*</span></label>
                       <div class="input-group">
    <button type="button" class="btn btn-outline-primary" onclick="changeAmount(-1)">-</button>
    <input type="number" name="amount" id="amount" class="form-control text-center"
        value="{{ old('amount', 1) }}" min="1" required>
    <button type="button" class="btn btn-outline-primary" onclick="changeAmount(1)">+</button>
</div>

                        <small class="text-muted">Ví dụ chọn 10 sẽ có 10 lượt dùng cho 10 user khác nhau.</small>
                    </div>

                    {{-- Chiết khấu --}}
                    <div class="mb-2">
                        <label for="discount" class="form-label">Chiết khấu giảm (%) <span class="text-danger">*</span></label>
                        <input type="number" name="discount" id="discount" class="form-control"
                            value="{{ old('discount') }}" placeholder="VD: 10 = giảm 10%" required min="1" max="100">
                        <small class="text-muted">User đã có chiết khấu sẽ không dùng được mã này.</small>
                    </div>

                    {{-- Sản phẩm áp dụng --}}
                    <div class="mb-2">
                        <label for="products" class="form-label">Sản phẩm áp dụng</label>
                        <input type="text" name="products" id="products" class="form-control"
                            value="{{ old('products') }}" placeholder="Mặc định áp dụng toàn bộ sản phẩm nếu để trống">
                    </div>

                    {{-- Giá trị đơn hàng tối thiểu --}}
                    <div class="mb-2">
                        <label for="min" class="form-label">Giá trị đơn hàng tối thiểu (VND) <span class="text-danger">*</span></label>
                        <input type="number" name="min" id="min" class="form-control"
                            value="{{ old('min', 100000) }}" min="0" required>
                    </div>

                    {{-- Giá trị đơn hàng tối đa --}}
                    <div class="mb-2">
                        <label for="max" class="form-label">Giá trị đơn hàng tối đa (VND) <span class="text-danger">*</span></label>
                        <input type="number" name="max" id="max" class="form-control"
                            value="{{ old('max', 1000000000) }}" min="0" required>
                    </div>

                    {{-- Hạn sử dụng --}}
                    <div class="mb-2">
                        <label for="expired_at" class="form-label">Hạn sử dụng <span class="text-danger">*</span></label>
                        <input type="date" name="expired_at" id="expired_at" class="form-control"
                            value="{{ old('expired_at') }}" required>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary me-2">← Quay lại</a>
                        <button type="submit" class="btn btn-success">+ Tạo mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script hỗ trợ tạo mã ngẫu nhiên và tăng giảm số lượng --}}
    <script>
        function generateRandomCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            for (let i = 0; i < 8; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('coupon-code').value = code;
        }

        function changeAmount(delta) {
            const input = document.getElementById('amount');
            let value = parseInt(input.value) || 1;
            value = Math.max(1, value + delta);
            input.value = value;
        }
    </script>
@endsection
