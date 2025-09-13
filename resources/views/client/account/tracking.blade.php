@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<section class="section-b-space pt-0">
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-6">
          <h4>Chi tiết đơn hàng</h4>
        </div>
        <div class="col-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"> <a href="{{ route('client.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
          </ul>
        </div>

      </div>
    </div>
  </div>
  
</section>

<section class="section-b-space pt-0">
  <div class="custom-container container order-tracking">
    <div class="row g-4">
      <div class="col-12">
        <div class="order-table">
          <div class="table-responsive theme-scrollbar">
            <table class="table">
              <thead>
                <tr>
                  <th>Mã đơn</th>
                  <th>Ngày đặt</th>
                  <th>Người nhận</th>
                  <th>SĐT</th>
                  <th>Địa chỉ</th>
                  <th>Giao bởi</th>
                  <th>Trạng thái</th>
                  <th>Thanh toán</th>
                  <th>Tải Hóa Đơn</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>#{{ $order->order_code ?? $order->id }}</td>
                  <td>{{ $order->created_at->format('M d, Y') }}</td>
                  <td>{{ $order->address->full_name }}</td>
                  <td>{{ $order->address->phone }}</td>
                  <td style="max-width: 300px; word-wrap: break-word; white-space: normal;">
                    {{ $order->address->address }},
                    {{ $order->address->ward->name ?? '' }},
                    {{ $order->address->district->name ?? '' }},
                    {{ $order->address->province->name ?? '' }}
                  </td>

                  <td>{{ $order->courier_name ?? 'Đang xử lý' }}</td>
                  <td><span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span></td>
                  <td>{{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Đã thanh toán' }}</td>
                  <td>
                    <a href="{{ route('client.orders.invoice', $order->id) }}"
                      class="btn btn-sm btn-outline-primary" target="_blank">
                      <i class="bi bi-download"></i> Hóa đơn
                    </a>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="tracking-box">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Order Progress/Status</h4>
          </div>
          <div class="tracking-timeline">
            <h4>Timeline</h4>
            <ul>
              @foreach ($order->tracking_steps ?? [] as $step)
              <li>
                <div>
                  <h6>{{ $step['date'] }}</h6>
                  <p>{{ $step['status'] }}</p>
                </div><span>{{ $step['time'] }}</span>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="tracking-box">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Live tracking</h4>
          </div>
          <div class="tracking-map">
            <iframe src="https://www.google.com/maps?q={{ $order->lat ?? 10 }},{{ $order->lng ?? 106 }}&hl=vi&z=14&output=embed" width="100%" height="420" frameborder="0" style="border:0"></iframe>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="order-table tracking-table">
          <div class="table-responsive theme-scrollbar">
            <table class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Sản phẩm</th>
                  <th>Số lượng</th>
                  <th>Giá </th>
                  <th>Tổng</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->orderItems as $index => $item)
                @php
                $variantValues = json_decode($item->variant_values ?? '{}', true);
                @endphp
                <tr>
                  <td>{{ $index + 1 }}.</td>
                  <td>
                    <div class="cart-box">
                      <a href="">
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}">
                      </a>
                      <div>
                        <a href="">
                          <h5>{{ $item->product_name }}</h5>
                        </a>
                        <p>Brand: <span>{{ $item->product->brand->name ?? 'N/A' }}</span></p>

                        @php
                        $variantValues = json_decode($item->variant_values ?? '{}', true);
                        @endphp

                        @foreach ($variantValues as $key => $value)
                        <p>{{ ucfirst($key) }}: <span>{{ $value }}</span></p>
                        @endforeach
                      </div>
                    </div>
                  </td>
                  <td>{{ $item->quantity }}</td>
                  <td>${{ number_format($item->price, 2) }}</td>
                  <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div style="max-width: 600px; margin-left: auto; padding: 20px; border: 1px solid #eee; background-color: #fff;">
              {{-- Phương thức thanh toán --}}
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-weight: 500;">Phương thức thanh toán:</span>
                <span style="font-weight: 600;">{{ $order->paymentMethod->name ?? '---' }}</span>
              </div>

              {{-- Tạm tính --}}
              <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
              </div>

              {{-- Giảm giá nếu có --}}
              @if ($order->discount_amount > 0)
              <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span>Mã giảm giá:</span>
                <span style="color: green;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
              </div>
              @endif

              {{-- Thuế nếu có --}}
              <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span>Thuế (VAT):</span>
                <span>{{ number_format($order->tax_amount, 0, ',', '.') }}đ</span>
              </div>

              {{-- Phí vận chuyển --}}
              <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span>Phí vận chuyển:</span>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
              </div>

              {{-- Đường gạch ngang --}}
              <hr style="margin: 16px 0; border-top: 1px solid #ddd;">

              {{-- Tổng cộng --}}
              <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px;">
                <span style="text-transform: uppercase;">Tổng cộng:</span>
                <span style="color: #e53935;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection