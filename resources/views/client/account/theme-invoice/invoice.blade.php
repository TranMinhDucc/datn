@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng')

@section('content')

    <style>
        .invoice-wrapper {
            background: rgba(204, 162, 112, 0.05) url('{{ asset('img-invoice/bg.png') }}') no-repeat center/cover;
            padding: 30px;
            box-shadow: 0 0 13px rgba(0, 0, 0, 0.05);
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
    </style>

    <div class="container invoice-wrapper position-relative">
        <div class="container invoice-wrapper p-4 bg-white shadow rounded">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('img-invoice/logo.png') }}" height="40" alt="Logo">
                   
                </div>
                <div class="text-end">
                    <h5 class="text-dark mb-1">Invoice</h5>
                    <small class="text-muted">#{{ $order->order_code }}</small>
                </div>
            </div>

            {{-- Info --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-uppercase fw-semibold mb-2">Invoice To:</h6>
                    <p class="mb-1">{{ $order->address->full_name }}</p>
                    <p class="mb-1">{{ $order->address->address }}</p>
                    <p class="mb-1">Phone: {{ $order->address->phone }}</p>
                    <p class="mb-0">Postal Code: {{ $order->address->pincode }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->fullname ?? '---' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-0"><strong>Payment:</strong> {{ $order->paymentMethod->name }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th class="text-start">Product name</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
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
            </div>

            {{-- Summary --}}
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <table class="table table-borderless">
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
                                <td class="text-end fw-semibold">
                                    -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
                            </tr>
                        @endif
                        <tr class="border-top">
                            <td class="text-end fw-bold">Tổng thanh toán:</td>
                            <td class="text-end fw-bold text-success">
                                {{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="row mt-4 align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('img-invoice/signature.svg') }}" width="100" alt="Signature">
                    <p class="mb-0 mt-2">{{ $order->user->fullname }}</p>
                    <small class="text-muted">Accounting Manager</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="text-warning fw-bold">GRATEFUL FOR YOUR BUSINESS!</h6>
                    <p class="mb-1">
                        <i class="bi bi-telephone"></i> {{ $order->address->phone }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-envelope"></i> {{ $order->user->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
