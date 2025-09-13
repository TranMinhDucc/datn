@extends('emails.layout')

@section('title', 'Đơn hàng đang được giao')

@section('content')
    <h2 style="color:#333; text-align:center;">🚚 Đơn hàng đang trên đường giao</h2>
    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Đơn hàng <strong>#{{ $order->id }}</strong> đang được vận chuyển đến địa chỉ của bạn.</p>
    <p style="text-align:center; margin:30px 0;">
        <a href="{{ route('client.orders.tracking.show', $order->id) }}"
           style="background-color:#000; color:#fff; padding:12px 25px; text-decoration:none; border-radius:5px;">
            Theo dõi đơn hàng
        </a>
    </p>
    <p style="text-align:center;">Chúc bạn một ngày tuyệt vời cùng <strong>Katie Shop</strong>!</p>
@endsection
