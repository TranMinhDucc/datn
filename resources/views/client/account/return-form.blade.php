@extends('layouts.client')

@section('title', 'Yêu cầu Hoàn / Đổi hàng')

@section('content')
    <div class="container py-5">
        <h3 class="mb-4">Yêu cầu hoàn / đổi hàng cho đơn hàng #{{ $order->code }}</h3>

        <form action="{{ route('client.account.orders.return_request', $order->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="reason" class="form-label">Lý do</label>
                <textarea name="reason" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh sản phẩm (nếu có)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-danger">Gửi yêu cầu</button>
            <a href="{{ route('client.account.dashboard') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
