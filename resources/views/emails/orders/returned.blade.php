@extends('emails.layout')

@section('title', 'Trả hàng thành công')

@section('content')
    <h2 style="color:#333; text-align:center;">↩️ Trả hàng thành công</h2>
    <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Đơn hàng <strong>#{{ $order->id }}</strong> đã được trả về thành công và chúng tôi đã tiếp nhận.</p>
    <p style="text-align:center;">Chúng tôi sẽ xử lý hoàn tiền trong thời gian sớm nhất.</p>
@endsection
