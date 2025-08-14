@extends('emails.layout')

@section('title', 'Hoàn tiền đơn hàng')

@section('content')
    <h2 style="color:#333; text-align:center;">💸 Hoàn tiền thành công</h2>
    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Số tiền <strong>{{ number_format($order->total_amount, 0, ',', '.') }} VND</strong> từ đơn hàng <strong>#{{ $order->id }}</strong> đã được hoàn vào tài khoản của bạn.</p>
    <p style="text-align:center;">Cảm ơn bạn đã hiểu và thông cảm với <strong>Katie Shop</strong>.</p>
@endsection
