<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .invoice-wrapper {
            background: rgba(204, 162, 112, 0.05) url('{{ public_path("img-invoice/bg.png") }}') no-repeat center/cover;
            padding: 30px;
            border-radius: 10px;
        }
        .invoice-label {
            background-color: #cca170;
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            text-align: right;
        }
        .invoice-title {
            text-transform: uppercase;
            font-size: 20px;
            margin: 0;
        }
        .invoice-subtitle {
            font-size: 14px;
            font-weight: 500;
        }
        .invoice-section {
            background-color: #cca270;
            padding: 20px;
            color: #fff;
            border-radius: 6px;
        }
        .invoice-table th {
            color: #cca270;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            border-bottom: 2px solid #cca270;
            padding: 12px 8px;
        }
        .invoice-table td {
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            padding: 10px 8px;
        }
        .invoice-summary td {
            padding: 6px 0;
            font-size: 15px;
        }
        .invoice-summary .total {
            background-color: #cca270;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            font-size: 17px;
        }
        .invoice-footer img {
            object-fit: contain;
        }
        .contact-info {
            font-size: 14px;
            color: #6e6d6d;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table.table-bordered td, table.table-bordered th {
            border: 1px solid #dee2e6;
        }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .fw-semibold { font-weight: 600; }
        .text-success { color: #28a745; }
        .text-warning { color: #cca270; }
        .text-muted { color: #6c757d; }
        .small { font-size: 12px; }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        {{-- Tiêu đề --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ public_path('img-invoice/logo.png') }}" height="40" alt="Logo">
            </div>
            <div style="text-align: right;">
                <h5 style="margin: 0;">HÓA ĐƠN</h5>
                <small class="text-muted">#{{ $order->order_code }}</small>
            </div>
        </div>

        {{-- Thông tin --}}
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h6 style="margin: 0 0 5px; text-transform: uppercase; font-weight: 600;">Thông tin người nhận</h6>
                <p style="margin: 0;">{{ $order->address->full_name }}</p>
                <p style="margin: 0;">{{ $order->address->address }}</p>
                <p style="margin: 0;">Điện thoại: {{ $order->address->phone }}</p>
                <p style="margin: 0;">Mã bưu chính: {{ $order->address->pincode }}</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0;"><strong>Ngày lập:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p style="margin: 0;"><strong>Khách hàng:</strong> {{ $order->user->fullname ?? '---' }}</p>
                <p style="margin: 0;"><strong>Email:</strong> {{ $order->user->email }}</p>
                <p style="margin: 0;"><strong>Hình thức thanh toán:</strong> {{ $order->paymentMethod->name }}</p>
            </div>
        </div>

        {{-- Bảng sản phẩm --}}
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th style="width: 60px;">STT</th>
                    <th class="text-start">Tên sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>SL</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $index => $item)
                    <tr>
                        <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-start">
                            {{ $item->product_name }}
                            <div class="small text-muted">Mã SP: {{ $item->sku ?: 'N/A' }}</div>
                        </td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Tổng kết --}}
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <table style="width: 300px;">
                <tr>
                    <td class="text-end text-muted">Tạm tính:</td>
                    <td class="text-end fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td class="text-end text-muted">Thuế (VAT):</td>
                    <td class="text-end fw-semibold">{{ number_format($order->tax_amount, 0, ',', '.') }}₫</td>
                </tr>
                @if ($order->discount_amount > 0)
                    <tr>
                        <td class="text-end text-muted">Giảm giá:</td>
                        <td class="text-end fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
                    </tr>
                @endif
                <tr style="border-top: 1px solid #dee2e6;">
                    <td class="text-end fw-bold">Tổng thanh toán:</td>
                    <td class="text-end fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                </tr>
            </table>
        </div>

        {{-- Chân trang --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
            <div>
                <img src="{{ public_path('img-invoice/signature.svg') }}" width="100" alt="Chữ ký">
                <p style="margin: 5px 0 0;">{{ $order->user->fullname }}</p>
                <small class="text-muted">Quản lý kế toán</small>
            </div>
            <div style="text-align: right;">
                <h6 class="text-warning fw-bold" style="margin: 0 0 5px;">XIN CẢM ƠN QUÝ KHÁCH!</h6>
                <p style="margin: 0;">{{ $order->address->phone }}</p>
                <p style="margin: 0;">{{ $order->user->email }}</p>
            </div>
        </div>
    </div>
</body>
</html>
