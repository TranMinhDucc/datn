@extends('emails.layout')

@section('title', 'Đã thanh toán đơn hàng')

@section('content')
    <h2 style="color:#333; text-align:center;">💳 Thanh toán thành công!</h2>

    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>
        Đơn hàng <strong>#{{ $order->id }}</strong> đã được thanh toán thành công với số tiền 
        <strong>{{ number_format($order->total_amount, 0, ',', '.') }} VND</strong>.
    </p>

    <p style="text-align:center; margin:30px 0;">
        <a href="{{ route('client.orders.tracking.show', $order->id) }}"
           style="background-color:#1a1a1a; color:#fff; padding:12px 25px; text-decoration:none; border-radius:5px;">
            Xem chi tiết đơn hàng
        </a>
    </p>

    <p style="color:#555; text-align:center;">Cảm ơn bạn đã mua sắm tại <strong>Katie Shop</strong>!</p>
@endsection
