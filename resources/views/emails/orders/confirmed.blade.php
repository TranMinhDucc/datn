@extends('emails.layout')

@section('title', 'Xác nhận đơn hàng')

@section('content')
    <h2 style="color:#333; text-align:center;">🛍️ Đơn hàng đã được xác nhận!</h2>

    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Chúng tôi đã xác nhận đơn hàng <strong>#{{ $order->id }}</strong> với tổng giá trị <strong>{{ number_format($order->total_amount, 0, ',', '.') }} VND</strong>.</p>
    <p>Chúng tôi sẽ tiến hành xử lý và giao hàng sớm nhất có thể.</p>

    <p style="text-align:center; margin:30px 0;">
        <a href="{{ route('client.orders.tracking.show', $order->id) }}"
           style="background-color:#f57ea4; color:#fff; padding:12px 25px; text-decoration:none; border-radius:5px;">
            Xem đơn hàng
        </a>
    </p>

    <p style="color:#555;">Cảm ơn bạn đã mua sắm tại <strong>Katie Shop</strong>!</p>
@endsection
