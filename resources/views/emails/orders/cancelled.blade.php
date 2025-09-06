@extends('emails.layout')

@section('title', 'Đơn hàng đã hủy')

@section('content')
    <h2 style="color:#333; text-align:center;">❌ Đơn hàng đã bị hủy</h2>
    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Đơn hàng <strong>#{{ $order->id }}</strong> đã bị hủy theo yêu cầu.</p>
    <p style="text-align:center;">Nếu có thắc mắc, bạn vui lòng liên hệ <strong>Katie Shop</strong> để được hỗ trợ.</p>
@endsection
