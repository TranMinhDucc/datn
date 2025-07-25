@extends('layouts.client')

@section('title', 'Đặt hàng thành công')

@section('content')

<section class="section-b-space py-0">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 px-0">
        <div class="order-box-1"><img src="{{ asset('storage/gif/success.gif') }}" alt="Thành công">

          <h4>Đặt hàng thành công</h4>
          <p>Đơn hàng đã được đặt thành công và đơn hàng của bạn sẽ được giao</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section-b-space">
  <div class="custom-container container order-success">
    <div class="row gy-4">
      <div class="col-xl-8">
        <div class="order-items sticky">
          <h4>Thông Tin Đặt Hàng</h4>
          <p>Hóa đơn đã được gửi đến email đăng ký của bạn. Kiểm tra lại thông tin chi tiết đơn hàng bên dưới.</p>

          <div class="order-table">
            <div class="table-responsive theme-scrollbar">
              <table class="table">
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->orderItems as $item)
                  <tr>
                    <td>
                      <div class="cart-box">
                        <img src="{{ $item->image_url }}" alt="" style="width: 60px;">
                        <div>
                          <h5>{{ $item->product_name }}</h5>
                          <p>SKU: {{ $item->sku }}</p>
                          @php
                          $attrs = json_decode($item->variant_values ?? '[]', true);
                          @endphp
                          <p>Phân loại: {{ implode(', ', $attrs ?? []) }}</p>

                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                  </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td></td>
                    <td class="fw-bold">Tổng cộng:</td>
                    <td class="fw-bold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>

      <div class="col-xl-4">
        <div class="summery-box">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Chi tiết đơn hàng </h4>
          </div>
          <div class="summery-content">
            <ul>
              <li>
                <p class="fw-semibold">Sản phẩm ({{ $order->orderItems->sum('quantity') }})</p>
                <h6>{{ number_format($order->subtotal, 0, ',', '.') }}đ</h6>
              </li>
              <li>
                <p>Gửi tới </p>
                <span>
                  {{ $order->address->ward->name ?? '' }},
                  {{ $order->address->district->name ?? '' }},
                  {{ $order->address->province->name ?? 'Chưa có địa chỉ' }}
                </span>
              </li>
            </ul>
            <ul>
              <li>
                <p>Phí vận chuyển</p>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
              </li>
              <li>
                <p>Tổng tiền chưa VAT </p>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
              </li>
              <li>
                <p>Phí VAT </p>
                <span>{{ number_format($order->tax_amount, 0, ',', '.') }}đ</span>
              </li>
            </ul>
            <div class="d-flex align-items-center justify-content-between">
              <h6>Total ({{ $order->currency ?? 'VND' }})</h6>
              <h5>{{ number_format($order->total_amount, 0, ',', '.') }}đ</h5>
            </div>
            <div class="note-box">
              <p>Tôi hy vọng cửa hàng có thể giao hàng sớm nhất có thể vì tôi cần nó để tặng bạn tôi vào bữa tiệc tuần tới.</p>
            </div>
          </div>
        </div>
        <div class="summery-footer">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Địa chỉ giao hàng</h4>
          </div>
          <ul>
            <li>
              <h6>{{ $order->address->address_line ?? '' }}</h6>
              <h6>
                {{ $order->address->ward->name ?? '' }},
                {{ $order->address->district->name ?? '' }},
                {{ $order->address->province->name ?? '' }}
              </h6>
            </li>
            <li>
              <h6>Expected Date Of Delivery:
                <span>{{ now()->addDays(3)->format('d/m/Y') }}</span>
              </h6>
            </li>
          </ul>
        </div>
      </div>


    </div>
  </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentUser = localStorage.getItem('currentUser') || 'guest';
        localStorage.removeItem(`cartItems_${currentUser}`);
        sessionStorage.removeItem('shippingCoupon');
        sessionStorage.removeItem('productCoupon');
    });
</script>

@endsection