@extends('emails.layout')

@section('title', 'Hoàn tất đơn hàng')

@section('content')
    <h2 style="color:#333; text-align:center;">🎉 Giao hàng thành công!</h2>
    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Đơn hàng <strong>#{{ $order->id }}</strong> đã được giao thành công. Hy vọng bạn hài lòng với sản phẩm từ <strong>Katie Shop</strong>.</p>
    <p style="text-align:center;">Đừng quên để lại đánh giá nhé!</p>
@endsection
