<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0; padding: 0;
        }
        .email-container {
            background-color: #fff;
            max-width: 700px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 13px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #cca270;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 40px;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .order-info p {
            margin: 4px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #cca270;
            color: #fff;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        .summary {
            text-align: right;
            margin-top: 15px;
        }
        .summary p {
            margin: 3px 0;
            font-size: 14px;
        }
        .summary .total {
            font-weight: bold;
            font-size: 16px;
            color: #28a745;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background-color: #cca270;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <img src="{{ asset('img-invoice/logo.png') }}" alt="Logo">
        <div>
            <h3>Hóa đơn #{{ $order->order_code }}</h3>
            <small>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
        </div>
    </div>

    <div class="order-info">
        <p><strong>Người nhận:</strong> {{ $order->address->full_name }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->address->phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address->address }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>Thanh toán:</strong> {{ $order->paymentMethod->name }}</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>SL</th>
            <th>Giá</th>
            <th>Tổng</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p>Tạm tính: {{ number_format($order->subtotal, 0, ',', '.') }}₫</p>
        <p>Thuế: {{ number_format($order->tax_amount, 0, ',', '.') }}₫</p>
        @if($order->discount_amount > 0)
            <p>Giảm giá: -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
        @endif
        <p class="total">Tổng thanh toán: {{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/invoice/'.$order->id) }}" class="btn">Xem hóa đơn online</a>
    </div>

    <div class="footer">
        Cảm ơn bạn đã mua hàng tại {{ config('app.name') }}!<br>
        File PDF hóa đơn đã được đính kèm trong email này.
    </div>
</div>
</body>
</html>
