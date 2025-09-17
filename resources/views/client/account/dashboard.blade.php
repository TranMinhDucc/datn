@extends('layouts.client')

@section('title', 'my profile')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Bảng Điều Khiển</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="{{ route('client.home') }}">Trang Chủ </a></li>
                            <li class="breadcrumb-item active"> <a href="#">Bảng Điều Khiển</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0">
        <div class="custom-container container user-dashboard-section">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="left-dashboard-show">
                        <button class="btn btn_black sm rounded bg-primary">Show Menu</button>
                    </div>
                    <div class="dashboard-left-sidebar sticky">
                        <div class="profile-box">
                            <div class="profile-bg-img"></div>
                            <div class="dashboard-left-sidebar-close"><i class="fa-solid fa-xmark"></i></div>
                            <div class="profile-contain text-center">
                                <form id="avatarForm" method="POST" action="{{ route('client.account.avatar.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*"
                                        style="display: none;" onchange="document.getElementById('avatarForm').submit()">

                                    <div class="profile-image position-relative"
                                        onclick="document.getElementById('avatarInput').click()" style="cursor:pointer;">
                                        <div class="avatar-wrapper">
                                            <img src="{{ $user->avatar_url }} " alt="avatar" class="avatar-img"
                                                style="width: 130px;
                                      height: 130px;
                                      border-radius: 50%;
                                      overflow: hidden;
                                      position: relative;
                                      margin: auto;
                                      border: 3px solid #fff;
                                      box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);">
                                        </div>
                                        <div class="camera-icon-overlay d-flex justify-content-center align-items-center">
                                            {{-- <i class="fas fa-camera"></i> --}}
                                        </div>
                                    </div>
                                </form>
                                <div class="profile-name mt-3">
                                    <h4>{{ $user['fullname'] }}</h4>
                                    <h6>{{ $user['email'] }}</h6>
                                    <span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
                                        tabindex="0">Chỉnh Sửa Hồ Sơ
                                    </span>
                                </div>
                            </div>

                        </div>
                        <ul class="nav flex-column nav-pills dashboard-tab" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <li>
                                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill"
                                    data-bs-target="#dashboard" role="tab" aria-controls="dashboard"
                                    aria-selected="true"><i class="iconsax" data-icon="home-1"></i>
                                    Bảng Điều Khiển</button>
                                </button>
                            </li>
                            <li>
                                <button class="nav-link" id="notifications-tab" data-bs-toggle="pill"
                                    data-bs-target="#notifications" role="tab" aria-controls="notifications"
                                    aria-selected="false"><i class="iconsax" data-icon="lamp-2"></i>Thông Báo</button>
                            </li>
                            <li>
                                <button class="nav-link" id="order-tab" data-bs-toggle="pill" data-bs-target="#order"
                                    role="tab" aria-controls="order" aria-selected="false"><i class="iconsax"
                                        data-icon="receipt-square"></i>
                                    Đơn Hàng</button>
                            </li>
                            <li>
                                <button class="nav-link" id="wishlist-tab" data-bs-toggle="pill" data-bs-target="#wishlist"
                                    role="tab" aria-controls="wishlist" aria-selected="false"> <i class="iconsax"
                                        data-icon="heart"></i>Danh Sách Yêu Thích
                                </button>
                            </li>
                            <li>
                                <button class="nav-link" id="saved-card-tab" data-bs-toggle="pill"
                                    data-bs-target="#saved-card" role="tab" aria-controls="saved-card"
                                    aria-selected="false"> <i class="iconsax" data-icon="bank-card"></i>Thẻ
                                    Đã Lưu </button>
                            </li>
                            <li>
                                <button class="nav-link" id="address-tab" data-bs-toggle="pill" data-bs-target="#address"
                                    role="tab" aria-controls="address" aria-selected="false"><i class="iconsax"
                                        data-icon="cue-cards"></i>Địa Chỉ</button>
                            </li>
                            <li>
                                <button class="nav-link" id="privacy-tab" data-bs-toggle="pill"
                                    data-bs-target="#privacy" role="tab" aria-controls="privacy"
                                    aria-selected="false"> <i class="iconsax" data-icon="security-user"></i>Quyền riêng
                                    tư</button>
                            </li>
                        </ul>
                        <div class="logout-button"> <a class="btn btn_black sm" data-bs-toggle="modal"
                                data-bs-target="#Confirmation-modal" title="Quick View" tabindex="0"><i
                                    class="iconsax me-1" data-icon="logout-1"></i> Đăng Xuất </a></div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel"
                            aria-labelledby="dashboard-tab">
                            <div class="dashboard-right-box">
                                <div class="my-dashboard-tab">
                                    <div class="dashboard-items"> </div>
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Bảng điều khiển của tôi
                                        </h4>
                                    </div>
                                    <div class="dashboard-user-name">
                                        <h6>Xin chào, <b>{{ $user['fullname'] }}</b></h6>
                                        <p>Bảng điều khiển của tôi cung cấp cái nhìn tổng quan toàn diện về các chỉ số và dữ
                                            liệu quan trọng liên quan đến hoạt động của bạn.
                                            Nó mang đến thông tin chi tiết theo thời gian thực về hiệu suất, bao gồm số liệu
                                            bán hàng, lưu lượng truy cập website, mức độ tương tác của khách hàng và nhiều
                                            hơn nữa.
                                            Với các widget có thể tùy chỉnh và hình ảnh trực quan dễ hiểu, bảng điều khiển
                                            giúp bạn đưa ra quyết định nhanh chóng và theo dõi hiệu quả tiến độ đạt được các
                                            mục tiêu của mình.
                                        </p>
                                    </div>
                                    <div class="total-box">
                                        <div class="row gy-4">
                                            <div class="col-xl-4">
                                                <div class="totle-contain">
                                                    <div class="wallet-point"><img
                                                            src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/wallet.svg"
                                                            alt=""><img class="img-1"
                                                            src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/wallet.svg"
                                                            alt=""></div>
                                                    <div class="totle-detail">
                                                        <h6>Số dư hiện tại</h6>
                                                        <h4>{{ number_format($user->balance) }}đ</h4>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="totle-contain">
                                                    <div class="wallet-point"><img
                                                            src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/coin.svg"
                                                            alt=""><img class="img-1"
                                                            src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/coin.svg"
                                                            alt=""></div>
                                                    <div class="totle-detail">
                                                        <h6>Điểm tích lũy</h6>
                                                        <h4>{{ number_format($user->point) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="totle-contain">
                                                    <div class="wallet-point"><img
                                                            src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/order.svg"
                                                            alt=""><img class="img-1"
                                                            src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/order.svg"
                                                            alt=""></div>
                                                    <div class="totle-detail">
                                                        <h6>Tổng số đơn hàng</h6>
                                                        <h4>12</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-about">
                                        <div class="row">
                                            <div class="col-xl-7">
                                                <div class="sidebar-title">
                                                    <div class="loader-line"></div>
                                                    <h5>Thông tin cá nhân</h5>
                                                </div>
                                                <ul class="profile-information">
                                                    <li>
                                                        <h6>Tên :</h6>
                                                        <p>{{ $user['fullname'] }}</p>
                                                    </li>
                                                    <li>
                                                        <h6>Số điện thoại:</h6>
                                                        @if (!empty($user['phone']))
                                                            <p>{{ $user['phone'] }}</p>
                                                        @else
                                                            <p>
                                                                Bạn chưa có thông tin hotline !
                                                                <span data-bs-toggle="modal" data-bs-target="#edit-box"
                                                                    title="Quick View" tabindex="0">Thêm</span>
                                                            </p>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        <h6>Địa chỉ:</h6>
                                                        @if (!empty($user['address']))
                                                            <p>{{ $user['address'] }}</p>
                                                        @else
                                                            <p>
                                                                Bạn chưa có địa chỉ !
                                                                <span data-bs-toggle="modal" data-bs-target="#edit-box"
                                                                    title="Quick View" tabindex="0">Thêm</span>
                                                            </p>
                                                        @endif
                                                    </li>


                                                    {{-- <li>
                                                        <h6>Address:</h6>
                                                        <p>26, Starts Hollow Colony Denver, Colorado, United States 80014
                                                        </p>
                                                    </li> --}}
                                                </ul>
                                                <div class="sidebar-title">
                                                    <div class="loader-line"></div>
                                                    <h5>Tài khoản</h5>
                                                </div>
                                                <ul class="profile-information mb-0">
                                                    <li>
                                                        <h6>Email :</h6>
                                                        <p>{{ $user->email }}
                                                            <span data-bs-toggle="modal" data-bs-target="#edit-box"
                                                                title="Quick View" tabindex="0">Edit</span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <h6>Password :</h6>
                                                        <p>●●●●●●<span data-bs-toggle="modal"
                                                                data-bs-target="#edit-password" title="Quick View"
                                                                tabindex="0">Edit</span></p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5">
                                                <div class="profile-image d-none d-xl-block"> <img class="img-fluid"
                                                        src="{{ asset('assets/client/images/other-img/dashboard.png') }}"
                                                        alt=""></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="notifications" role="tabpanel"
                            aria-labelledby="notifications-tab">
                            <div class="dashboard-right-box">
                                <div class="notification-tab">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Thông báo</h4>
                                    </div>
                                    @php
                                        function getStatusLabel($status)
                                        {
                                            return match ($status) {
                                                'pending' => '🕐 Chờ xác nhận',
                                                'confirmed' => '✅ Đã xác nhận',
                                                'shipping' => '🚚 Đang giao hàng',
                                                'completed' => '🎉 Đã hoàn tất',
                                                'cancelled' => '❌ Đã huỷ',
                                                default => ucfirst($status),
                                            };
                                        }
                                    @endphp

                                    <ul class="notification-body">
                                        @forelse ($notifications as $notification)
                                            <li>
                                                <div class="user-img">
                                                    @php

                                                        $item = \App\Models\OrderItem::where(
                                                            'order_id',
                                                            $notification->data['order_id'],
                                                        )->first();
                                                        $image =
                                                            $item?->image_url ??
                                                            asset('assets/client/images/default.png');
                                                    @endphp

                                                    <img src="{{ $image }}" alt="Đơn hàng" width="50">
                                                </div>
                                                <div class="user-contant">
                                                    <h6>
                                                        Đơn hàng #{{ $notification->data['order_code'] ?? 'N/A' }} -
                                                        {{ getStatusLabel($notification->data['status'] ?? '') }}
                                                        <span>{{ $notification->created_at->format('H:i d/m') }}</span>
                                                    </h6>
                                                    <p>
                                                        Trạng thái đơn hàng đã được cập nhật thành
                                                        <strong>{{ getStatusLabel($notification->data['status'] ?? '') }}</strong>.
                                                        @if (($notification->data['status'] ?? '') === 'cancelled' && !empty($notification->data['cancel_reason_by_admin']))
                                                            <br><span>Lý do:
                                                                {{ $notification->data['cancel_reason_by_admin'] }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </li>
                                        @empty
                                            <li>
                                                <p class="text-center">Không có thông báo nào.</p>
                                            </li>
                                        @endforelse
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                            <div class="dashboard-right-box">
                                <div class="wishlist-box ratio1_3">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Wishlist</h4>
                                    </div>
                                    <div class="row-cols-md-3 row-cols-2 grid-section view-option row gy-4 g-xl-4">
                                        @forelse ($wishlists as $item)
                                            @php
                                                $product = $item->product;
                                            @endphp
                                            <div class="col">
                                                <div class="product-box-3 product-wishlist">
                                                    <div class="img-wrapper">
                                                        <div class="label-block">
                                                            <a class="label-2 wishlist-icon delete-button delete-wishlist"
                                                                href="javascript:;" data-id="{{ $product->id }}"
                                                                title="Remove from Wishlist">
                                                                <i class="iconsax" data-icon="trash"
                                                                    aria-hidden="true"></i>
                                                            </a>
                                                            <form id="remove-wishlist-{{ $product->id }}"
                                                                action="{{ route('client.account.wishlist.remove', $product->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                        <div class="product-image">
                                                            <a class="pro-first"
                                                                href="{{ route('client.products.show', $product->slug) }}">
                                                                <img class="bg-img"
                                                                    src="{{ asset('storage/' . $product->image ?? 'assets/client/images/no-image.png') }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                            <a class="pro-sec"
                                                                href="{{ route('client.products.show', $product->slug) }}">
                                                                <img class="bg-img"
                                                                    src="{{ asset('storage/' . $product->image ?? 'assets/client/images/no-image.png') }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="cart-info-icon">
                                                            <a href="#" title="Add to cart"><i class="iconsax"
                                                                    data-icon="basket-2"></i></a>
                                                            <a href="#" title="Compare"><i class="iconsax"
                                                                    data-icon="arrow-up-down"></i></a>
                                                            <a href="#" title="Quick View"><i class="iconsax"
                                                                    data-icon="eye"></i></a>
                                                        </div>
                                                    </div>
                                                    @php
                                                        // Lấy đánh giá
                                                        $reviews = App\Models\Review::where('product_id', $product->id)
                                                            ->where('approved', true)
                                                            ->with('user')
                                                            ->latest()
                                                            ->get();
                                                        $rating_summary = [
                                                            'avg_rating' => null,
                                                            'total_rating' => count($reviews),
                                                            '5_star_percent' => 0,
                                                            '4_star_percent' => 0,
                                                            '3_star_percent' => 0,
                                                            '2_star_percent' => 0,
                                                            '1_star_percent' => 0,
                                                        ];
                                                        if ($rating_summary['total_rating'] > 0) {
                                                            $star_5 = $star_4 = $star_3 = $star_2 = $star_1 = 0;

                                                            foreach ($reviews as $review) {
                                                                switch ($review->rating) {
                                                                    case '1':
                                                                        $star_1++;
                                                                        break;
                                                                    case '2':
                                                                        $star_2++;
                                                                        break;
                                                                    case '3':
                                                                        $star_3++;
                                                                        break;
                                                                    case '4':
                                                                        $star_4++;
                                                                        break;
                                                                    case '5':
                                                                        $star_5++;
                                                                        break;
                                                                }
                                                            }

                                                            $total = $rating_summary['total_rating'];

                                                            $rating_summary['1_star_percent'] = round(
                                                                ($star_1 / $total) * 100,
                                                            );
                                                            $rating_summary['2_star_percent'] = round(
                                                                ($star_2 / $total) * 100,
                                                            );
                                                            $rating_summary['3_star_percent'] = round(
                                                                ($star_3 / $total) * 100,
                                                            );
                                                            $rating_summary['4_star_percent'] = round(
                                                                ($star_4 / $total) * 100,
                                                            );
                                                            $rating_summary['5_star_percent'] = round(
                                                                ($star_5 / $total) * 100,
                                                            );
                                                            $rating_summary['avg_rating'] =
                                                                ($star_5 * 5 +
                                                                    $star_4 * 4 +
                                                                    $star_3 * 3 +
                                                                    $star_2 * 2 +
                                                                    $star_1) /
                                                                $total;
                                                        }
                                                    @endphp
                                                    <div class="product-detail">
                                                        <ul class="rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($rating_summary['avg_rating'] >= $i)
                                                                    <li><i class="fa-solid fa-star"></i></li>
                                                                @elseif ($rating_summary['avg_rating'] >= $i - 0.5)
                                                                    <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                                                @else
                                                                    <li><i class="fa-regular fa-star"></i></li>
                                                                @endif
                                                            @endfor
                                                            <li>{{ $rating_summary['avg_rating'] }}</li>
                                                        </ul>

                                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                                            <h6>{{ $product->name }}</h6>
                                                        </a>
                                                        <p>{{ number_format($product->sale_price, 0, ',', '.') }} đ</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p>Danh sách yêu thích của bạn đang trống.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
                            <div class="dashboard-right-box">
                                <div class="order">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Lịch sử đơn hàng</h4>
                                    </div>
                                    <form action="{{ route('client.account.dashboard') }}" class="form_search_order mb-4">
                                        <input name="order_code" type="text" class="form-control input_search_order" value="{{ request('order_code') }}" placeholder="Nhập mã đơn hàng cần tìm ...">
                                    </form>
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            @php
                                                $orderStatus = [
                                                    'all' => 'Tất cả',
                                                    'pending' => 'Chờ duyệt',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'shipping' => 'Đang giao',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy',
                                                    'returning' => 'Đang trả',
                                                    'returned' => 'Đã trả',
                                                ];
                                                $orderAfterSort = [];
                                                foreach ($orders as $order) {
                                                    if (!isset($orderAfterSort[$order->status])) {
                                                        $orderAfterSort[$order->status] = [];
                                                    }
                                                    if (array_key_exists($order->status, $orderStatus)) {
                                                        $orderAfterSort[$order->status][] = $order;
                                                    }
                                                }
                                            @endphp
                                            <ul class="nav nav-pills order-tab mb-2" id="order-status-pills-tab"
                                                role="tablist" aria-orientation="horizontal">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($orderStatus as $status => $title)
                                                    <li>
                                                        <button class="nav-link {{ $i == 0 ? 'active' : '' }}"
                                                            id="order-status-tab-{{ $status }}"
                                                            data-bs-toggle="pill"
                                                            data-bs-target="#order-status-{{ $status }}"
                                                            role="tab" aria-controls="dashboard"
                                                            aria-selected="false" tabindex="-1">
                                                            {{ $title }}
                                                            @if ($status == 'all')
                                                                <b>{{ count($orders) }}</b>
                                                            @else
                                                                <b>{{ isset($orderAfterSort[$status]) ? count($orderAfterSort[$status]) : 0 }}</b>
                                                            @endif
                                                        </button>
                                                    </li>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </ul>

                                            <div class="tab-content" id="order-status-pills-tabContent">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($orderStatus as $status => $title)
                                                    @php
                                                        if ($status == 'all') {
                                                            $items = $orders;
                                                        } else {
                                                            $items = $orderAfterSort[$status] ?? [];
                                                        }
                                                        if (count($items) <= 0) {
                                                            continue;
                                                        }
                                                    @endphp
                                                    <div class="tab-pane fade {{ $i == 0 ? 'active show' : '' }}"
                                                        id="order-status-{{ $status }}" role="tabpanel"
                                                        aria-labelledby="dashboard-tab">
                                                        @foreach ($items as $order)
                                                            <div class="order-box">
                                                                <div
                                                                    class="order-container d-flex justify-content-between align-items-center">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="order-icon me-3">
                                                                            <i class="iconsax"
                                                                                data-icon="{{ $order->status === 'refunded' ? 'undo' : ($order->status === 'cancelled' ? 'box-add' : 'box') }}"></i>
                                                                            <div class="couplet">
                                                                                <i
                                                                                    class="fa-solid fa-{{ $order->status === 'cancelled' ? 'xmark' : 'check' }}"></i>
                                                                            </div>
                                                                        </div>

                                                                        <div class="order-detail">
                                                                            {{-- Tiêu đề trạng thái bằng tiếng Việt --}}
                                                                            <h5 class="mb-0">
                                                                                @php
                                                                                    $statusText = match (
                                                                                        $order->status
                                                                                    ) {
                                                                                        'pending' => 'Chờ xác nhận',
                                                                                        'confirmed' => 'Đã xác nhận',
                                                                                        'processing' => 'Đang xử lý',
                                                                                        'ready_for_dispatch'
                                                                                            => 'Sẵn sàng giao hàng',
                                                                                        'shipping' => 'Đang giao hàng',
                                                                                        'delivery_failed'
                                                                                            => 'Giao hàng thất bại',
                                                                                        'delivered' => 'Đã giao hàng',
                                                                                        'completed' => 'Đã hoàn tất',
                                                                                        'cancelled' => 'Đã hủy',
                                                                                        'return_requested'
                                                                                            => 'Yêu cầu trả hàng',
                                                                                        'returning' => 'Đang trả hàng',
                                                                                        'returned' => 'Đã trả hàng',
                                                                                        'exchange_requested'
                                                                                            => 'Yêu cầu đổi hàng',
                                                                                        'exchanged' => 'Đã đổi hàng',
                                                                                        'refund_processing'
                                                                                            => 'Đang hoàn tiền',
                                                                                        'refunded' => 'Đã hoàn tiền',
                                                                                        default
                                                                                            => 'Không rõ trạng thái',
                                                                                    };
                                                                                @endphp
                                                                                {{ $statusText }}
                                                                            </h5>

                                                                            {{-- Ngày đặt hàng --}}
                                                                            <p class="mb-0 text-muted"
                                                                                style="font-size: 0.875rem;">
                                                                                đặt vào ngày
                                                                                {{ optional($order->created_at)->format('d/m/Y') }}
                                                                            </p>
                                                                            <div class="show-more-my-order collapsed"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#myOrder{{ $order->id }}"
                                                                                aria-expanded="false"
                                                                                style="cursor:pointer;">
                                                                                <span class="text-show text-primary">Xem
                                                                                    chi tiết</span>
                                                                                <span
                                                                                    class="text-hide d-none text-danger">Thu
                                                                                    gọn</span>
                                                                            </div>

                                                                            <div class="collapse mt-2"
                                                                                id="myOrder{{ $order->id }}">
                                                                                <!-- Nội dung chi tiết đơn hàng -->
                                                                                <p>Thông tin đơn hàng #{{ $order->id }}
                                                                                </p>
                                                                            </div>


                                                                            {{-- Thông báo hoàn tiền --}}
                                                                            @if ($order->status === 'cancelled')
                                                                                <h6>
                                                                                    <b>Hoàn tiền đang xử lý:</b>
                                                                                    {{ number_format($order->refund_amount, 0, ',', '.') }}₫
                                                                                    vào
                                                                                    ngày
                                                                                    {{ optional($order->refunded_at)->format('d/m/Y') }}.
                                                                                </h6>
                                                                            @elseif($order->status === 'refunded')
                                                                                <p>
                                                                                    Số tiền hoàn
                                                                                    <b>{{ number_format($order->refund_amount, 0, ',', '.') }}₫</b>
                                                                                    đã được hoàn thành thành công vào ngày
                                                                                    {{ optional($order->refunded_at)->format('d/m/Y') }}.
                                                                                </p>
                                                                            @endif
                                                                        </div>

                                                                    </div>


                                                                    <div
                                                                        class="order-actions d-flex justify-content-end flex-wrap gap-2 mt-3">

                                                                        <!-- Modal -->
                                                                        @if ($order->delivered_at && now()->diffInDays($order->delivered_at) <= 3)
                                                                            @if ($order->returnRequests->isEmpty())
                                                                                <a href="{{ route('client.account.return_requests.create', $order->id) }}"
                                                                                    class="btn btn-danger">
                                                                                    Hoàn / Đổi hàng
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ route('client.account.return_requests.index') }}"
                                                                                    class="btn btn-outline-primary">
                                                                                    📝 Đã gửi khiếu nại – Xem lại
                                                                                </a>
                                                                            @endif
                                                                        @endif




                                                                        @if (in_array($order->status, ['pending', 'confirmed']))
                                                                            @if ($order->status === 'pending')
                                                                                {{-- Hủy trực tiếp --}}
                                                                                <button
                                                                                    class="btn btn-outline-danger btn-sm"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#cancelModal-{{ $order->id }}">
                                                                                    Hủy đơn hàng
                                                                                </button>
                                                                            @elseif ($order->status === 'confirmed')
                                                                                @if (!$order->cancel_request)
                                                                                    {{-- Gửi yêu cầu hủy --}}
                                                                                    <button
                                                                                        class="btn btn-outline-warning btn-sm"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#cancelModal-{{ $order->id }}">
                                                                                        Gửi yêu cầu hủy
                                                                                    </button>
                                                                                @else
                                                                                    {{-- Đã gửi yêu cầu hủy --}}
                                                                                    <button
                                                                                        class="btn btn-outline-secondary btn-sm"
                                                                                        disabled>
                                                                                        Đang chờ duyệt
                                                                                    </button>
                                                                                @endif
                                                                            @endif


                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="cancelModal-{{ $order->id }}"
                                                                                tabindex="-1">
                                                                                <div class="modal-dialog modal-dialog-centered"
                                                                                    style="max-width: 500px;">
                                                                                    <div
                                                                                        class="modal-content rounded-4 shadow">
                                                                                        <div
                                                                                            class="modal-header border-bottom-0 pb-0">
                                                                                            <h5 class="modal-title">
                                                                                                <i
                                                                                                    class="bi bi-x-octagon-fill text-danger me-2"></i>
                                                                                                Lý do hủy đơn hàng
                                                                                            </h5>
                                                                                            <button type="button"
                                                                                                class="btn-close"
                                                                                                data-bs-dismiss="modal"></button>
                                                                                        </div>
                                                                                        <form method="POST"
                                                                                            action="{{ route('client.orders.cancel', $order->id) }}">
                                                                                            @csrf
                                                                                            @method('PATCH')
                                                                                            <div class="modal-body pt-0">
                                                                                                <div
                                                                                                    class="form-check my-2">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="radio"
                                                                                                        name="cancel_reason"
                                                                                                        value="Tôi không còn nhu cầu"
                                                                                                        id="r1-{{ $order->id }}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="r1-{{ $order->id }}">❌
                                                                                                        Tôi không còn nhu
                                                                                                        cầu</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="form-check my-2">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="radio"
                                                                                                        name="cancel_reason"
                                                                                                        value="Đặt nhầm sản phẩm"
                                                                                                        id="r2-{{ $order->id }}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="r2-{{ $order->id }}">📦
                                                                                                        Đặt nhầm sản
                                                                                                        phẩm</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="form-check my-2">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="radio"
                                                                                                        name="cancel_reason"
                                                                                                        value="Đặt nhầm địa chỉ"
                                                                                                        id="r4-{{ $order->id }}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="r4-{{ $order->id }}">📍
                                                                                                        Đặt nhầm địa
                                                                                                        chỉ</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="form-check my-2">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="radio"
                                                                                                        name="cancel_reason"
                                                                                                        value="Thay đổi phương thức thanh toán"
                                                                                                        id="r5-{{ $order->id }}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="r5-{{ $order->id }}">💳
                                                                                                        Thay đổi phương thức
                                                                                                        thanh
                                                                                                        toán</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="form-check my-2">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="radio"
                                                                                                        name="cancel_reason"
                                                                                                        value="Tìm được giá tốt hơn"
                                                                                                        id="r6-{{ $order->id }}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="r6-{{ $order->id }}">💰
                                                                                                        Tìm được giá tốt
                                                                                                        hơn</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="form-check my-2">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="radio"
                                                                                                        name="cancel_reason"
                                                                                                        value="Khác"
                                                                                                        id="reasonOther-{{ $order->id }}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="reasonOther-{{ $order->id }}">✏️
                                                                                                        Khác</label>
                                                                                                </div>

                                                                                                <div id="customReasonWrapper-{{ $order->id }}"
                                                                                                    class="d-none">
                                                                                                    <textarea name="cancel_reason_other" id="customReason-{{ $order->id }}" class="form-control mt-2" rows="3"
                                                                                                        placeholder="Nhập lý do khác (nếu có)..."></textarea>
                                                                                                    <div id="errorText-{{ $order->id }}"
                                                                                                        class="text-danger mt-1 d-none">
                                                                                                        Vui lòng nhập lý do
                                                                                                        khi chọn
                                                                                                        "Khác".
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>
                                                                                            <div
                                                                                                class="modal-footer border-top-0">
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-bs-dismiss="modal">Đóng</button>
                                                                                                <button type="submit"
                                                                                                    class="btn btn-danger">Xác
                                                                                                    nhận
                                                                                                    hủy</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif



                                                                        {{-- Theo dõi đơn hàng --}}
                                                                        @if ($order->status === 'shipping')
                                                                            <a href="{{ route('client.orders.tracking.show', $order->id) }}"
                                                                                class="btn btn-outline-primary btn-sm">
                                                                                🚚 Theo dõi đơn hàng
                                                                            </a>
                                                                        @endif


                                                                        {{-- Liên hệ người bán --}}


                                                                        {{-- Mua lại (nếu đã hoàn thành) --}}
                                                                        @if (in_array($order->status, ['completed', 'cancelled']))
                                                                            <button class="btn btn-danger btn-sm fw-bold"
                                                                                onclick="reorderToCart({{ $order->id }})">
                                                                                Mua Lại
                                                                            </button>
                                                                        @endif




                                                                    </div>


                                                                </div>

                                                                <div class="collapse" id="myOrder{{ $order->id }}">
                                                                    @foreach ($order->orderItems as $orderItem)
                                                                        <div class="product-order-detail">
                                                                            <div
                                                                                class="product-box position-relative d-flex align-items-start">
                                                                                {{-- Ảnh sản phẩm --}}
                                                                                @if ($orderItem->product)
                                                                                    <img src="{{ asset('storage/' . $orderItem->product->image) }}"
                                                                                        alt="{{ $orderItem->product_name }}"
                                                                                        style="max-width: 200px; max-height: 100px; object-fit: contain;">
                                                                                @else
                                                                                    <img src="{{ asset('images/default.png') }}"
                                                                                        alt="Không có ảnh"
                                                                                        style="max-width: 150px;">
                                                                                @endif

                                                                                {{-- Nội dung --}}
                                                                                <div class="order-wrap">
                                                                                    <h5>{{ $orderItem->product_name }}</h5>
                                                                                    <p style="overflow:hidden;width:100%;">
                                                                                        {{ $orderItem->product->description ?? 'Không có mô tả' }}
                                                                                    </p>
                                                                                    <ul
                                                                                        style="list-style: none; padding-left: 0;">
                                                                                        <li>
                                                                                            <p>Giá :</p>
                                                                                            <span>{{ number_format($orderItem->price, 0, ',', '.') }}₫</span>
                                                                                        </li>

                                                                                        @php
                                                                                            $variantValues = json_decode(
                                                                                                $orderItem->variant_values ??
                                                                                                    '{}',
                                                                                                true,
                                                                                            );
                                                                                        @endphp

                                                                                        @if (!empty($variantValues))
                                                                                            @foreach ($variantValues as $key => $value)
                                                                                                <li>
                                                                                                    <p>{{ ucfirst($key) }}
                                                                                                        :</p>
                                                                                                    <span>{{ $value }}</span>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        @endif


                                                                                        <li>
                                                                                            <p>Mã đơn hàng :</p>
                                                                                            <span>{{ $orderItem->order->order_code ?? '---' }}</span>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>

                                                                                {{-- Link ẩn phủ toàn bộ box --}}
                                                                                <a href="{{ route('client.orders.tracking.show', $order->id) }}"
                                                                                    class="stretched-link"></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="return-box">
                                                                            <div class="review-box">
                                                                                @php
                                                                                    // Lấy model sản phẩm để tính trung bình sao

                                                                                    $prod =
                                                                                        $orderItem->product ??
                                                                                        ($product ?? null);

                                                                                    // Trung bình sao (0 → 5), làm tròn 1 chữ số
                                                                                    $avgRating = $prod
                                                                                        ? round(
                                                                                            $prod
                                                                                                ->reviews()
                                                                                                ->avg('rating') ?? 0,
                                                                                            1,
                                                                                        )
                                                                                        : 0;

                                                                                    $fullStars = floor($avgRating); // số sao đầy
                                                                                    $halfStar =
                                                                                        $avgRating - $fullStars >= 0.5; // có nửa sao không
                                                                                @endphp

                                                                                <ul class="rating">
                                                                                    {{-- Sao đầy --}}
                                                                                    @for ($i = 1; $i <= $fullStars; $i++)
                                                                                        <li><i
                                                                                                class="fa-solid fa-star"></i>
                                                                                        </li>
                                                                                    @endfor

                                                                                    {{-- Sao nửa --}}
                                                                                    @if ($halfStar)
                                                                                        <li><i
                                                                                                class="fa-solid fa-star-half-stroke"></i>
                                                                                    @endif

                                                                                    {{-- Sao rỗng --}}
                                                                                    @for ($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                                                                        <li><i
                                                                                                class="fa-regular fa-star"></i>
                                                                                        </li>
                                                                                    @endfor
                                                                                </ul>



                                                                                @if ($order->status == 'completed')
                                                                                    {{-- Nút modal --}}
                                                                                    <span class="openReviewModal"
                                                                                        title="Quick View" tabindex="0"
                                                                                        data-product="{{ $orderItem->product->id }}"
                                                                                        data-product-name="{{ $orderItem->product->name }}"
                                                                                        data-product-price="{{ $orderItem->price }}"
                                                                                        data-product-image="{{ $orderItem->product->image }}"
                                                                                        data-order-item-id="{{ $orderItem->id }}">
                                                                                        Viết đánh giá
                                                                                    </span>

                                                                                    {{-- Link dự phòng đến trang sản phẩm --}}
                                                                                    {{-- @if ($orderItem->product)
                                                                                    <a href="{{ route('client.products.show', $orderItem->product->slug) }}#review"
                                                                                        class="d-block mt-1">
                                                                                        <small>Hoặc viết tại trang sản phẩm</small>
                                                                                    </a>
                                                                                    @endif --}}
                                                                                @endif
                                                                            </div>
                                                                            {{-- <h6>* Exchange/Return window closed on 20 Mar</h6> --}}
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- <div class="col-12">
                                            <div class="order-box">
                                                <div class="order-container">
                                                    <div class="order-icon"><i class="iconsax" data-icon="undo"></i>
                                                        <div class="couplet"><i class="fa-solid fa-check"></i></div>
                                                    </div>
                                                    <div class="order-detail">
                                                        <h5>Refund Credited</h5>
                                                        <p>
                                                            Your Refund Of <b> $389.00 </b>For then return has been
                                                            processed Successfully on 4th Apr.<a href="#"> View
                                                                Refund details</a></p>
                                                    </div>
                                                </div>
                                                <div class="product-order-detail">
                                                    <div class="product-box"> <a href="product.html"> <img
                                                                src="{{ asset('assets/client/images/notification/9.jpg') }}"
                                                                alt=""></a>
                                                        <div class="order-wrap">
                                                            <h5>Rustic Minidress with Halterneck</h5>
                                                            <p>Versatile sporty slogans short sleeve quirky laid back orange
                                                                lux hoodies vests pins
                                                                badges.</p>
                                                            <ul>
                                                                <li>
                                                                    <p>Prize : </p><span>$20.00</span>
                                                                </li>
                                                                <li>
                                                                    <p>Size : </p><span>M</span>
                                                                </li>
                                                                <li>
                                                                    <p>Order Id :</p><span>ghat56han50</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="return-box">
                                                    <div class="review-box">
                                                        <ul class="rating">
                                                            <li> <i class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i><i
                                                                    class="fa-regular fa-star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <h6>
                                                        * Exchange/Return window closed on 20 mar</h6>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="saved-card" role="tabpanel" aria-labelledby="saved-card-tab">
                            <div class="dashboard-right-box">
                                <div class="saved-card">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>My Card Details</h4>
                                    </div>
                                    <div class="payment-section">
                                        <div class="row gy-3">
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="payment-card">
                                                    <div class="bank-info"><img class="bank"
                                                            src="{{ asset('assets/client/images/bank-card/bank1.png') }}"
                                                            alt="bank1">
                                                        <div class="card-type"><img class="bank-card"
                                                                src="{{ asset('assets/client/images/bank-card/1.png') }}"
                                                                alt="card"></div>
                                                    </div>
                                                    <div class="card-details"><span>Card Number</span>
                                                        <h5>6458 50XX XXXX 0851</h5>
                                                    </div>
                                                    <div class="card-details-wrap">
                                                        <div class="card-details"><span>Name On Card</span>
                                                            <h5>Josephin water</h5>
                                                        </div>
                                                        <div class="text-center card-details"><span>Validity</span>
                                                            <h5>XX/XX</h5>
                                                        </div>
                                                        <div class="btn-box"><span data-bs-toggle="modal"
                                                                data-bs-target="#edit-bank-card" title="Quick View"
                                                                tabindex="0"><i class="iconsax"
                                                                    data-icon="edit-1"></i></span><span
                                                                data-bs-toggle="modal" data-bs-target="#bank-card-modal"
                                                                title="Quick View" tabindex="0"><i class="iconsax"
                                                                    data-icon="trash"></i></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="payment-card">
                                                    <div class="bank-info"><img class="bank"
                                                            src="{{ asset('assets/client/images/bank-card/bank2.png') }}"
                                                            alt="bank1">
                                                        <div class="card-type"><img class="bank-card"
                                                                src="{{ asset('assets/client/images/bank-card/2.png') }}"
                                                                alt="card"></div>
                                                    </div>
                                                    <div class="card-details"><span>Card Number</span>
                                                        <h5>6458 50XX XXXX 0851</h5>
                                                    </div>
                                                    <div class="card-details-wrap">
                                                        <div class="card-details"><span>Name On Card</span>
                                                            <h5>Josephin water</h5>
                                                        </div>
                                                        <div class="text-center card-details"><span>Validity</span>
                                                            <h5>XX/XX</h5>
                                                        </div>
                                                        <div class="btn-box"><span data-bs-toggle="modal"
                                                                data-bs-target="#edit-bank-card" title="Quick View"
                                                                tabindex="0"><i class="iconsax"
                                                                    data-icon="edit-1"></i></span><span
                                                                data-bs-toggle="modal" data-bs-target="#bank-card-modal"
                                                                title="Quick View" tabindex="0"><i class="iconsax"
                                                                    data-icon="trash"></i></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="payment-card">
                                                    <div class="bank-info"><img class="bank"
                                                            src="{{ asset('assets/client/images/bank-card/bank3.png') }}"
                                                            alt="bank1">
                                                        <div class="card-type"><img class="bank-card"
                                                                src="{{ asset('assets/client/images/bank-card/3.png') }}"
                                                                alt="card"></div>
                                                    </div>
                                                    <div class="card-details"><span>Card Number</span>
                                                        <h5>6458 50XX XXXX 0851</h5>
                                                    </div>
                                                    <div class="card-details-wrap">
                                                        <div class="card-details"><span>Name On Card</span>
                                                            <h5>Josephin water</h5>
                                                        </div>
                                                        <div class="text-center card-details"><span>Validity</span>
                                                            <h5>XX/XX</h5>
                                                        </div>
                                                        <div class="btn-box"><span data-bs-toggle="modal"
                                                                data-bs-target="#edit-bank-card" title="Quick View"
                                                                tabindex="0"><i class="iconsax"
                                                                    data-icon="edit-1"></i></span><span
                                                                data-bs-toggle="modal" data-bs-target="#bank-card-modal"
                                                                title="Quick View" tabindex="0"><i class="iconsax"
                                                                    data-icon="trash"></i></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="payment-card">
                                                    <div class="add-card">
                                                        <h6 data-bs-toggle="modal" data-bs-target="#add-bank-card"
                                                            title="Quick View" tabindex="0">+
                                                            Add Card</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                            <div class="dashboard-right-box">
                                <div class="address-tab">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Địa chỉ của tôi</h4>
                                    </div>
                                    <div class="d-flex justify-content-end mb-3">
                                        <button class="btn add-address" data-bs-toggle="modal"
                                            data-bs-target="#add-address" title="Thêm địa chỉ" tabindex="0">+ Thêm Địa
                                            Chỉ</button>
                                    </div>

                                    <div class="row gy-3">
                                        @foreach ($addresses as $address)
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="address-option">
                                                    <label for="address-{{ $address->id }}">
                                                        <span class="delivery-address-box">
                                                            <span class="form-check">
                                                                <input class="custom-radio"
                                                                    id="address-{{ $address->id }}" type="radio"
                                                                    {{ $address->is_default ? 'checked' : '' }}
                                                                    name="default_address"
                                                                    onchange="document.getElementById('set-default-{{ $address->id }}').submit();" />
                                                            </span>
                                                            <span class="address-detail">
                                                                <span class="address">
                                                                    <span
                                                                        class="address-title">{{ $address->title }}</span>
                                                                </span>
                                                                <span class="address">
                                                                    <span class="address-home">
                                                                        <span class="address-tag"> Địa chỉ:</span>
                                                                        {{ $address->address }}, {{ $address->city }},
                                                                        {{ $address->state }},
                                                                        {{ $address->country }}
                                                                    </span>
                                                                </span>
                                                                <span class="address">
                                                                    <span class="address-home">
                                                                        <span class="address-tag">Mã bưu chính:</span>
                                                                        {{ $address->pincode }}
                                                                    </span>
                                                                </span>
                                                                <span class="address">
                                                                    <span class="address-home">
                                                                        <span class="address-tag">Điện thoại
                                                                            :</span>{{ $address->phone }}</span>
                                                                </span></span></span><span class="buttons"> <a
                                                                class="btn btn_black sm" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editAddressModal-{{ $address->id }}"
                                                                title="Quick View" tabindex="0">Sửa
                                                            </a><a class="btn btn_outline sm" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteAddressModal-{{ $address->id }}"
                                                                title="Quick View" tabindex="0">Xóa
                                                            </a></span></label>
                                                </div>
                                            </div>
                                            {{-- Edit modal --}}
                                            <div class="reviews-modal modal theme-modal fade"
                                                id="editAddressModal-{{ $address->id }}" tabindex="-1" role="dialog"
                                                aria-modal="true">
                                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4>Sửa địa chỉ</h4>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body pt-0">
                                                            <form
                                                                action="{{ route('client.account.address.update', $address->id) }}"
                                                                method="POST" class="row g-3">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="col-6">
                                                                    <label class="form-label">Loại địa chỉ</label>
                                                                    <select
                                                                        class="form-select @error('title') is-invalid @enderror"
                                                                        name="title">
                                                                        <option value="">-- Chọn loại --</option>
                                                                        <option value="Nhà riêng"
                                                                            {{ old('title', $address->title) == 'Nhà riêng' ? 'selected' : '' }}>
                                                                            Nhà riêng</option>
                                                                        <option value="Công ty"
                                                                            {{ old('title', $address->title) == 'Công ty' ? 'selected' : '' }}>
                                                                            Công ty</option>
                                                                        <option value="Khác"
                                                                            {{ old('title', $address->title) == 'Khác' ? 'selected' : '' }}>
                                                                            Khác</option>
                                                                    </select>
                                                                    @error('title')
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-6">
                                                                    <label class="form-label">Tên người nhận</label>
                                                                    <input
                                                                        class="form-control @error('full_name') is-invalid @enderror"
                                                                        name="full_name"
                                                                        value="{{ old('full_name', $address->full_name) }}">
                                                                    @error('full_name')
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-6">
                                                                    <label class="form-label">Điện thoại</label>
                                                                    <input
                                                                        class="form-control @error('phone') is-invalid @enderror"
                                                                        type="text" name="phone"
                                                                        value="{{ old('phone', $address->phone) }}">
                                                                    @error('phone')
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-6">
                                                                    <label class="form-label">Mã bưu chính</label>
                                                                    <input
                                                                        class="form-control @error('pincode') is-invalid @enderror"
                                                                        name="pincode"
                                                                        value="{{ old('pincode', $address->pincode) }}">
                                                                    @error('pincode')
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <input type="hidden" name="country" value="Vietnam">

                                                                <div class="col-4">
                                                                    <label class="form-label">Tỉnh/Thành phố</label>
                                                                    <select class="form-select" name="province_id"
                                                                        id="province-select-{{ $address->id }}"
                                                                        data-current-district="{{ $address->district_id }}"
                                                                        data-current-ward="{{ $address->ward_id }}"
                                                                        required>
                                                                        <option value="">-- Chọn tỉnh --</option>
                                                                        @foreach ($provinces as $province)
                                                                            <option value="{{ $province->id }}"
                                                                                {{ old('province_id', $address->province_id) == $province->id ? 'selected' : '' }}>
                                                                                {{ $province->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>

                                                                <div class="col-4">
                                                                    <label class="form-label">Quận/Huyện</label>
                                                                    <select class="form-select" name="district_id"
                                                                        id="district-select-{{ $address->id }}"
                                                                        required>
                                                                        <option value="">-- Chọn huyện --</option>
                                                                        {{-- Khi load modal, bạn có thể đổ sẵn district của address --}}
                                                                    </select>
                                                                </div>

                                                                <div class="col-4">
                                                                    <label class="form-label">Phường/Xã</label>
                                                                    <select class="form-select" name="ward_id"
                                                                        id="ward-select-{{ $address->id }}" required>
                                                                        <option value="">-- Chọn xã --</option>
                                                                        {{-- Khi load modal, bạn có thể đổ sẵn ward của address --}}
                                                                    </select>
                                                                </div>

                                                                <div class="col-12">
                                                                    <label class="form-label">Địa chỉ chi tiết</label>
                                                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $address->address) }}</textarea>
                                                                    @error('address')
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <button type="submit"
                                                                    class="btn btn-dark btn-lg px-5 py-2 fw-semibold">
                                                                    Cập nhật
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Delete Modal --}}
                                            <div class="modal theme-modal confirmation-modal"
                                                id="deleteAddressModal-{{ $address->id }}" tabindex="-1"
                                                role="dialog" aria-modal="true">
                                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <img class="img-fluid" src="../assets/images/gif/question.gif"
                                                                alt="">
                                                            <h4>Xác nhận xóa địa chỉ?</h4>
                                                            <p>Địa chỉ sẽ bị xóa vĩnh viễn khỏi danh sách. Bạn có muốn tiếp
                                                                tục?</p>
                                                            <form
                                                                action="{{ route('client.account.address.destroy', $address->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="submit-button">
                                                                    <button class="btn" type="button"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close">Hủy</button>
                                                                    <button class="btn" type="submit"
                                                                        data-bs-dismiss="modal" aria-label="Close">Đồng
                                                                        ý</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Form update default address --}}
                                            <form id="set-default-{{ $address->id }}"
                                                action="{{ route('client.account.address.setDefault', $address->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                            <div class="dashboard-right-box">
                                <div class="privacy-tab">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Privacy</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="privacy-title">
                                                <h5>Allows others to see my profile</h5>
                                                <p>Choose who can access your app and if users need to <a
                                                        href="sign-up.html"> sign up.</a></p>
                                            </div><span class="short-title">access</span>
                                            <ul class="privacy-items">
                                                <li>
                                                    <div class="privacy-icon"> <i class="iconsax" data-icon="lock-2"></i>
                                                    </div>
                                                    <div class="privacy-contant">
                                                        <h6>Private</h6>
                                                        <p>Only users you choose can access</p>
                                                    </div>
                                                    <label class="switch">
                                                        <input type="checkbox" checked=""><span
                                                            class="slider round"></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <div class="privacy-icon"> <i class="iconsax" data-icon="globe"></i>
                                                    </div>
                                                    <div class="privacy-contant">
                                                        <h6>Public</h6>
                                                        <p>Anyone with the link can</p>
                                                    </div>
                                                    <label class="switch">
                                                        <input type="checkbox"><span class="slider round"></span>
                                                    </label>
                                                </li>
                                            </ul><span class="short-title">Users</span>
                                            <ul class="privacy-items">
                                                <li>
                                                    <div class="privacy-icon"> <i class="iconsax"
                                                            data-icon="package"></i>
                                                    </div>
                                                    <div class="privacy-contant">
                                                        <h6>Users in the users table </h6>
                                                        <p>Only users in the users table can sign in </p>
                                                    </div>
                                                    <label class="switch">
                                                        <input type="checkbox" checked=""><span
                                                            class="slider round"></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <div class="privacy-icon"> <i class="iconsax"
                                                            data-icon="fingerprint-circle"></i></div>
                                                    <div class="privacy-contant">
                                                        <h6>ongoing production team </h6>
                                                        <p>only members of your team can sign in </p>
                                                    </div>
                                                    <label class="switch">
                                                        <input type="checkbox"><span class="slider round"></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <div class="privacy-icon"> <i class="iconsax"
                                                            data-icon="add-layer"></i>
                                                    </div>
                                                    <div class="privacy-contant">
                                                        <h6>anyone form domain(s)</h6>
                                                        <p>only users with your email domain </p>
                                                    </div>
                                                    <label class="switch">
                                                        <input type="checkbox" checked=""><span
                                                            class="slider round"></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <div class="privacy-icon"> <i class="iconsax" data-icon="mail"></i>
                                                    </div>
                                                    <div class="privacy-contant">
                                                        <h6>any email in table </h6>
                                                        <p>Anyone with email included in a table </p>
                                                    </div>
                                                    <label class="switch">
                                                        <input type="checkbox"><span class="slider round"></span>
                                                    </label>
                                                </li>
                                            </ul><span class="short-title"> </span>
                                            <ul class="privacy-items">
                                                <li>
                                                    <div class="privacy-contant">
                                                        <h6>Publishing </h6>
                                                        <p>Your Project is Published</p>
                                                    </div>
                                                    <div class="publish-button">
                                                        <button class="btn">Unpublish</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    {{-- Edit Email Doashboar START --}}
    <div class="reviews-modal modal theme-modal fade" id="edit-email" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Edit Email</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="from-group">
                                <label class="form-label">First Name</label>
                                <input class="form-control" type="text" name="review[author]"
                                    placeholder="Enter your name.">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="from-group">
                                <label class="form-label">Email address</label>
                                <input class="form-control" type="email" placeholder="john.smith@example.com">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="from-group">
                                <label class="form-label">Phone</label>
                                <input class="form-control" type="number" name="review[author]"
                                    placeholder="Enter your Number.">
                            </div>
                        </div>
                        <button class="btn btn-submit" type="submit" data-bs-dismiss="modal"
                            aria-label="Close">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END Edit Email Doashboar --}}
    {{-- EDIT PASSWORD START --}}
    <div class="reviews-modal modal theme-modal fade" id="edit-password" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Đổi mật khẩu</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- Lưu ý --}}
                <form id="change-password-form">
                    @csrf
                    <div class="modal-body pt-0">
                        <div class="row g-3">

                            <!-- Mật khẩu hiện tại (không cần mắt) -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input class="form-control" type="password" name="current_password"
                                        placeholder="Nhập mật khẩu hiện tại">
                                    <div class="text-danger error-current_password mt-1"></div>
                                </div>
                            </div>

                            <!-- Mật khẩu mới -->
                            <div class="col-12">
                                <div class="form-group ">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_password"
                                            id="new_password" placeholder="Nhập mật khẩu mới">
                                        <span class="input-group-text toggle-password" toggle="#new_password">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="text-danger error-new_password mt-1"></div>
                                </div>
                            </div>
                            <!-- Xác nhận mật khẩu -->
                            <div class="col-12">
                                <div class="form-group ">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_password_confirmation"
                                            id="new_password_confirmation" placeholder="Nhập lại mật khẩu mới">
                                        <span class="input-group-text toggle-password"
                                            toggle="#new_password_confirmation">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="text-danger error-new_password_confirmation mt-1"></div>
                                </div>
                            </div>


                            <div class="col-12 text-end">
                                <button class="btn btn-submit" type="submit">Lưu mật khẩu</button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- END EDIT PASSWORD --}}
    {{-- Edit ADDRESS START --}}

    <div class="reviews-modal modal theme-modal fade" id="add-address" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Thêm địa chỉ</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('client.account.address.store') }}" method="POST" class="row g-3"
                        id="address-form">
                        @csrf

                        <div class="col-6">
                            <label class="form-label">Loại địa chỉ</label>
                            <select class="form-select @error('title') is-invalid @enderror" name="title">
                                <option value="">-- Chọn loại --</option>
                                <option value="Nhà riêng" {{ old('title') == 'Nhà riêng' ? 'selected' : '' }}>Nhà
                                    riêng
                                </option>
                                <option value="Công ty" {{ old('title') == 'Công ty' ? 'selected' : '' }}>Công ty
                                </option>
                                <option value="Khác" {{ old('title') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tên người nhận</label>
                            <input class="form-control @error('full_name') is-invalid @enderror" name="full_name"
                                value="{{ old('full_name') }}">
                            @error('full_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">Điện thoại</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">Mã bưu chính</label>
                            <input class="form-control @error('pincode') is-invalid @enderror" name="pincode"
                                value="{{ old('pincode') }}">
                            @error('pincode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" class="form-control" name="country" value="Vietnam">
                        <div class="col-4">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" name="province_id" id="province-select" required>
                                <option value="">-- Chọn tỉnh --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Quận/Huyện</label>
                            <select class="form-select" name="district_id" id="district-select" required>
                                <option value="">-- Chọn huyện --</option>
                            </select>
                            @error('district_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Phường/Xã</label>
                            <select class="form-select" name="ward_id" id="ward-select" required>
                                <option value="">-- Chọn xã --</option>
                            </select>
                            @error('ward_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Địa chỉ chi tiết</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg px-5 py-2 fw-semibold">
                            Submit
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- END Edit Email Doashboar --}}

    <div class="reviews-modal modal theme-modal fade" id="edit-box" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Cập nhật thông tin người dùng</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="update-profile-form">
                    @csrf
                    <div class="modal-body pt-0">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="from-group">
                                    <label class="form-label">Họ tên</label>
                                    <input class="form-control" type="text" name="fullname"
                                        value="{{ $user->fullname ?? '' }}">
                                    <div class="text-danger error-fullname"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="text" name="email"
                                        value="{{ $user->email ?? '' }}">
                                    <div class="text-danger error-email"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="from-group">
                                <label class="form-label">Số điện thoại</label>
                                <input class="form-control" type="text" name="phone"
                                    value="{{ $user->phone ?? '' }}">
                                <div class="text-danger error-phone"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="from-group">
                                <label class="form-label">Địa chỉ</label>
                                <textarea class="form-control" name="address" cols="30" rows="3">{{ $user->address ?? '' }}</textarea>
                                <div class="text-danger error-address"></div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-submit" type="submit">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Edit Email Doashboar --}}

    {{-- Modal đánh giá --}}
    <div class="customer-reviews-modal modal theme-modal fade" id="Reviews-modal" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Viết đánh giá của bạn</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="rating-form" action="{{ route('client.review') }}" method="POST" class="row g-3">
                        @csrf
                        <input id="reviewProductId" type="hidden" name="product_id" value="">
                        <input id="orderItemId" type="hidden" name="order_item_id" value="">
                        <input type="hidden" name="rating" id="rating-value" value="0">

                        <div class="col-12">
                            <div class="reviews-product d-flex gap-3">
                                <img src="" alt="" id="reviewProductImage" width="80">
                                <div>
                                    <h5 id="reviewProductName"></h5>
                                    <p>
                                        <span id="reviewProductPrice"></span>đ
                                        {{-- <del id="reviewProductPriceOff">đ</del> --}}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="customer-rating">
                                <label class="form-label">Đánh giá</label>
                                <ul class="rating p-0 mb-0 d-flex" style="list-style: none; cursor: pointer;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li class="star" data-value="{{ $i }}">
                                            <i class="fa-regular fa-star fs-4 me-1"></i>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Nội dung :</label>
                                <textarea name="comment" class="form-control" id="comment" cols="30" rows="4"
                                    placeholder="Viết bình luận của bạn tại đây..." required></textarea>
                            </div>
                        </div>

                        <div class="modal-button-group d-flex gap-2">
                            <button class="btn btn-cancel" type="button" data-bs-dismiss="modal">Hủy</button>
                            <button class="btn btn-submit submit-rating" type="button">Gửi</button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('rating-form');
                            const stars = document.querySelectorAll('.star');
                            const ratingInput = document.getElementById('rating-value');
                            const commentInput = document.getElementById('comment');

                            const productId = document.getElementById('reviewProductId');
                            const orderItemId = document.getElementById('orderItemId');
                            const reviewProductName = document.getElementById('reviewProductName');
                            const reviewProductPrice = document.getElementById('reviewProductPrice');
                            const reviewProductImage = document.getElementById('reviewProductImage');

                            stars.forEach((star, index) => {
                                star.addEventListener('click', () => {
                                    const rating = star.getAttribute('data-value');
                                    ratingInput.value = rating;

                                    stars.forEach(s => s.querySelector('i').classList.replace('fa-solid',
                                        'fa-regular'));
                                    stars.forEach(s => s.querySelector('i').classList.replace('fa-solid',
                                        'fa-regular'));

                                    for (let i = 0; i < rating; i++) {
                                        stars[i].querySelector('i').classList.replace('fa-regular', 'fa-solid');
                                    }
                                });
                            });

                            document.querySelectorAll('.openReviewModal').forEach(button => {
                                button.addEventListener('click', function() {
                                    const btn = $(button);
                                    $(form).trigger('reset');

                                    productId.value = btn.data('product');
                                    orderItemId.value = btn.data('order-item-id');
                                    reviewProductName.innerText = btn.data('product-name');
                                    reviewProductPrice.innerText = btn.data('product-price');
                                    reviewProductImage.src = @js(asset('storage')) + '/' + btn.data(
                                        'product-image');

                                    $('#Reviews-modal').modal('show');
                                })
                            })

                            document.querySelectorAll('.submit-rating').forEach(button => {
                                button.addEventListener('click', function() {
                                    const rate = ratingInput.value;
                                    const comment = commentInput.value;
                                    if (isNaN(rate) || (rate <= 0 || rate > 5)) {
                                        Swal.fire('Thông báo', 'Vui lòng lựa chọn đánh giá của bạn', 'warning');
                                        return;
                                    }
                                    if (comment == '') {
                                        Swal.fire('Thông báo', 'Vui lòng nhập nội dung đánh giá', 'warning');
                                        return;
                                    }
                                    form.submit();
                                })
                            });
                        })
                    </script>
                </div>
            </div>
        </div>
    </div>
    {{-- Tự động mở lại modal nếu có lỗi --}}
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById('add-address'));
                modal.show();
            });
        </script>
    @endif
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .input-group-text {
            cursor: pointer;
            background-color: #fff;
            border-left: none;
            color: #938181;
        }

        .input-group .form-control {
            border-right: none;
        }

        .fa-eye {
            color: #938181;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .input-group-text {
            cursor: pointer;
            background-color: #fff;
            border-left: none;
            color: #938181;
        }

        .input-group .form-control {
            border-right: none;
        }

        .fa-eye {
            color: #938181;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .avatar-wrapper {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            margin: auto;
            border: 3px solid #fff;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Cố định tỉ lệ */
            object-position: center;
            /* Luôn crop ở giữa */
            border-radius: 50%;
            display: block;
        }
    </style>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province-select');
            const districtSelect = document.getElementById('district-select');
            const wardSelect = document.getElementById('ward-select');

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                districtSelect.innerHTML = '<option value="">-- Đang tải huyện --</option>';
                wardSelect.innerHTML = '<option value="">-- Chọn xã --</option>';

                fetch(`/api/districts?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">-- Chọn huyện --</option>';
                        data.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.id;
                            opt.textContent = d.name;
                            districtSelect.appendChild(opt);
                        });
                    });
            });

            districtSelect.addEventListener('change', function() {
                const districtId = this.value;
                wardSelect.innerHTML = '<option value="">-- Đang tải xã --</option>';

                fetch(`/api/wards?district_id=${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        wardSelect.innerHTML = '<option value="">-- Chọn xã --</option>';
                        data.forEach(w => {
                            const opt = document.createElement('option');
                            opt.value = w.id;
                            opt.textContent = w.name;
                            wardSelect.appendChild(opt);
                        });
                    });
            });
        });
    </script>
@endpush

@section('js')
    <script src="{{ asset('assets/client/js/dashboard-left-sidebar.js') }}"></script>
    {{-- EYE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-password').forEach(el => {
                el.addEventListener('click', function() {
                    const input = document.querySelector(this.getAttribute('toggle'));
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa - eye - slash ');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
    {{-- END EYE --}}
    {{-- AJAX UPDATE EMAIL --}}
    {{-- EDIT PASSWORD --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('change-password-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const url = '{{ route('client.account.change_password.submit') }}';
                form.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (response.ok && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đổi mật khẩu thành công!',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                form.reset();
                                const modal = bootstrap.Modal.getInstance(document
                                    .getElementById('edit-password'));
                                modal.hide();
                            });
                        } else if (data.errors) {
                            Object.entries(data.errors).forEach(([field, message]) => {
                                const errorEl = document.querySelector('.error-' + field);
                                if (errorEl) errorEl.textContent = message[0];
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi máy chủ',
                            text: 'Vui lòng thử lại.'
                        });
                    });
            });
        });
    </script>
    {{-- END EDIT PASSWORD --}}
    {{-- AJAX UPDATE PROFILE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('update-profile-form');

            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const url = '{{ route('client.account.profile.update') }}';

                // Xóa lỗi cũ
                form.querySelectorAll('.text-danger').forEach(el => el.innerText = '');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (response.ok && data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'edit-box'));
                            if (modal) modal.hide();

                            Swal.fire({
                                icon: 'success',
                                title: '🎉 Cập nhật thành công!',
                                html: 'Thông tin cá nhân của bạn đã được lưu.',
                                timer: 800,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                willClose: () => location.reload()
                            });
                        } else if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorDiv = document.querySelector('.error-' + field);
                                if (errorDiv) errorDiv.innerText = data.errors[field][0];
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi máy chủ',
                            text: 'Vui lòng thử lại sau.',
                            confirmButtonText: 'OK'
                        });
                        console.error(err);
                    });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#province-select').on('change', function() {
            const provinceId = $(this).val();
            $('#district-select').html('<option value="">-- Đang tải huyện --</option>');
            $('#ward-select').html('<option value="">-- Chọn xã --</option>');
            if (provinceId) {
                $.get(`/api/districts?province_id=${provinceId}`, function(data) {
                    let html = '<option value="">-- Chọn huyện --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#district-select').html(html);
                });
            }
        });

        $('#district-select').on('change', function() {
            const districtId = $(this).val();
            $('#ward-select').html('<option value="">-- Đang tải xã --</option>');
            if (districtId) {
                $.get(`/api/wards?district_id=${districtId}`, function(data) {
                    let html = '<option value="">-- Chọn xã --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#ward-select').html(html);
                });
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#province-select').on('change', function() {
            const provinceId = $(this).val();
            $('#district-select').html('<option value="">-- Đang tải huyện --</option>');
            $('#ward-select').html('<option value="">-- Chọn xã --</option>');
            if (provinceId) {
                $.get(`/api/districts?province_id=${provinceId}`, function(data) {
                    let html = '<option value="">-- Chọn huyện --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#district-select').html(html);
                });
            }
        });

        $('#district-select').on('change', function() {
            const districtId = $(this).val();
            $('#ward-select').html('<option value="">-- Đang tải xã --</option>');
            if (districtId) {
                $.get(`/api/wards?district_id=${districtId}`, function(data) {
                    let html = '<option value="">-- Chọn xã --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#ward-select').html(html);
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Thêm vào wishlist
            document.querySelectorAll('.add-to-wishlist').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    const icon = this.querySelector('i');

                    fetch(`/account/wishlist/add/${productId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Đã thêm vào yêu thích!',
                                    showConfirmButton: false,
                                    timer: 1000
                                });

                                // ✅ Đổi icon trái tim rỗng thành đầy
                                icon.setAttribute('data-icon', 'heart-fill');
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: data.message ||
                                        'Sản phẩm đã có trong danh sách!',
                                    showConfirmButton: false,
                                    timer: 1200
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi máy chủ!',
                                text: 'Vui lòng thử lại.'
                            });
                        });
                });
            });

            // ✅ Xác nhận xoá khỏi wishlist
            document.querySelectorAll('.delete-wishlist').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc muốn xoá?',
                        text: 'Sản phẩm sẽ bị xoá khỏi danh sách yêu thích!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`remove-wishlist-${productId}`)
                                .submit();
                        }
                    });
                });
            });
        });
    </script>

    {{-- ✅ Hiển thị thông báo session sau khi redirect --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('
                                                                                                                                                                                                                                <<<<<<< HEAD
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    success ') }}',

                ===
                ===
                =
                success ') }}',
                >>>
                >>>
                >
                98 c996a41720f9f49ab11f6be11ec37e99ba8541
                showConfirmButton: false,

                timer: 1200
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[id^="reasonOther-"]').forEach(otherRadio => {
                const orderId = otherRadio.id.split('-')[1];
                const wrapper = document.getElementById(`customReasonWrapper-${orderId}`);
                const textarea = document.getElementById(`customReason-${orderId}`);
                const errorText = document.getElementById(`errorText-${orderId}`);

                const allRadios = document.querySelectorAll(`input[name="cancel_reason"]`);

                allRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (otherRadio.checked) {
                            wrapper.classList.remove('d-none');
                            textarea.disabled = false;
                        } else {
                            wrapper.classList.add('d-none');
                            textarea.disabled = true;
                            textarea.classList.remove('is-invalid');
                            errorText?.classList.add('d-none');
                        }
                    });
                });
            });
        });
    </script>


    <script>
        function reorderToCart(orderId) {
            fetch(`/orders/${orderId}/reorder-data`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert("Không thể mua lại đơn hàng.");
                        return;
                    }

                    const currentUser = localStorage.getItem('currentUser') || 'guest';
                    const cartKey = `cartItems_${currentUser}`;
                    let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

                    data.items.forEach(item => {
                        const existing = cart.find(ci => ci.variant_id == item.variant_id);
                        if (existing) {
                            existing.quantity += item.quantity;
                        } else {
                            cart.push(item);
                        }
                    });

                    localStorage.setItem(cartKey, JSON.stringify(cart));
                    window.location.href = '/cart'; // ✅ Chuyển đến giỏ hàng
                })
                .catch(err => {
                    console.error(err);
                    alert("Lỗi khi mua lại đơn hàng.");
                });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.show-more-my-order').forEach(function(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    let showText = toggleBtn.querySelector('.text-show');
                    let hideText = toggleBtn.querySelector('.text-hide');

                    setTimeout(() => {
                        if (toggleBtn.classList.contains('collapsed')) {
                            // Đang thu gọn -> hiện "Xem chi tiết" màu xanh
                            showText.classList.remove('d-none');
                            hideText.classList.add('d-none');
                        } else {
                            // Đang mở -> hiện "Thu gọn" màu đỏ
                            showText.classList.add('d-none');
                            hideText.classList.remove('d-none');
                        }
                    }, 200);
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bắt sự kiện khi mở modal sửa địa chỉ
            document.querySelectorAll('[id^="editAddressModal-"]').forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    const addressId = this.id.split('-')[1]; // lấy ID address
                    const provinceSelect = document.getElementById(`province-select-${addressId}`);
                    const districtSelect = document.getElementById(`district-select-${addressId}`);
                    const wardSelect = document.getElementById(`ward-select-${addressId}`);

                    // Lấy dữ liệu hiện tại từ blade (in ra trong attribute data-)
                    const currentDistrict = provinceSelect.getAttribute('data-current-district');
                    const currentWard = provinceSelect.getAttribute('data-current-ward');

                    // 1. Load lại district khi mở modal
                    if (provinceSelect.value) {
                        fetch(`/api/districts?province_id=${provinceSelect.value}`)
                            .then(res => res.json())
                            .then(data => {
                                districtSelect.innerHTML =
                                    '<option value="">-- Chọn huyện --</option>';
                                data.forEach(d => {
                                    const opt = document.createElement('option');
                                    opt.value = d.id;
                                    opt.textContent = d.name;
                                    if (d.id == currentDistrict) opt.selected = true;
                                    districtSelect.appendChild(opt);
                                });

                                // 2. Load lại ward nếu có district cũ
                                if (currentDistrict) {
                                    fetch(`/api/wards?district_id=${currentDistrict}`)
                                        .then(res => res.json())
                                        .then(wards => {
                                            wardSelect.innerHTML =
                                                '<option value="">-- Chọn xã --</option>';
                                            wards.forEach(w => {
                                                const opt = document.createElement(
                                                    'option');
                                                opt.value = w.id;
                                                opt.textContent = w.name;
                                                if (w.id == currentWard) opt
                                                    .selected = true;
                                                wardSelect.appendChild(opt);
                                            });
                                        });
                                }
                            });
                    }

                    // 3. Sự kiện thay đổi province
                    provinceSelect.addEventListener('change', function() {
                        const provinceId = this.value;
                        districtSelect.innerHTML =
                            '<option value="">-- Đang tải huyện --</option>';
                        wardSelect.innerHTML = '<option value="">-- Chọn xã --</option>';

                        fetch(`/api/districts?province_id=${provinceId}`)
                            .then(res => res.json())
                            .then(data => {
                                districtSelect.innerHTML =
                                    '<option value="">-- Chọn huyện --</option>';
                                data.forEach(d => {
                                    const opt = document.createElement(
                                        'option');
                                    opt.value = d.id;
                                    opt.textContent = d.name;
                                    districtSelect.appendChild(opt);
                                });
                            });
                    });

                    // 4. Sự kiện thay đổi district
                    districtSelect.addEventListener('change', function() {
                        const districtId = this.value;
                        wardSelect.innerHTML =
                            '<option value="">-- Đang tải xã --</option>';

                        fetch(`/api/wards?district_id=${districtId}`)
                            .then(res => res.json())
                            .then(data => {
                                wardSelect.innerHTML =
                                    '<option value="">-- Chọn xã --</option>';
                                data.forEach(w => {
                                    const opt = document.createElement(
                                        'option');
                                    opt.value = w.id;
                                    opt.textContent = w.name;
                                    wardSelect.appendChild(opt);
                                });
                            });
                    });
                });
            });
        });
    </script>

    <script>
        $('.input_search_order').on('change', function () {
            $('.form_search_order').trigger('submit');
        });
    </script>

    @if (request()->filled('order_code'))
        <script>
            $('#v-pills-tab .nav-link').removeClass('active');
            $('#v-pills-tabContent .tab-pane').removeClass('show active')
            $('#order-tab').addClass('active');
            $('#order').addClass('show active');
            $('#order-status-all').addClass('show active')
        </script>
    @endif
@endsection
