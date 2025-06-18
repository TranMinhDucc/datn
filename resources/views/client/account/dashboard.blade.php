@extends('layouts.client')

@section('title', 'my profile')

@section('content')
  <section class="section-b-space pt-0">
    <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
      <div class="col-sm-6">
        <h4>Dashboard</h4>
      </div>
      <div class="col-sm-6">
        <ul class="breadcrumb float-end">
        <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
        <li class="breadcrumb-item active"> <a href="#">Dashboard</a></li>
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
          <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;"
            onchange="document.getElementById('avatarForm').submit()">

          <div class="profile-image position-relative" onclick="document.getElementById('avatarInput').click()"
            style="cursor:pointer;">
            <div class="avatar-wrapper">
            <img src="{{ $user->avatar_url }}" alt="avatar" class="avatar-img" style="width: 130px;
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
          <span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View" tabindex="0">Edit
            Profile</span>
          </div>
        </div>
        </div>
        <ul class="nav flex-column nav-pills dashboard-tab" id="v-pills-tab" role="tablist"
        aria-orientation="vertical">
        <li>
          <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill" data-bs-target="#dashboard"
          role="tab" aria-controls="dashboard" aria-selected="true"><i class="iconsax" data-icon="home-1"></i>
          Dashboard</button>
        </li>
        <li>
          <button class="nav-link" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications"
          role="tab" aria-controls="notifications" aria-selected="false"><i class="iconsax"
            data-icon="lamp-2"></i>Notifications </button>
        </li>
        <li>
          <button class="nav-link" id="order-tab" data-bs-toggle="pill" data-bs-target="#order" role="tab"
          aria-controls="order" aria-selected="false"><i class="iconsax" data-icon="receipt-square"></i>
          Order</button>
        </li>
        <li>
          <button class="nav-link" id="wishlist-tab" data-bs-toggle="pill" data-bs-target="#wishlist" role="tab"
          aria-controls="wishlist" aria-selected="false"> <i class="iconsax" data-icon="heart"></i>Wishlist
          </button>
        </li>
        <li>
          <button class="nav-link" id="saved-card-tab" data-bs-toggle="pill" data-bs-target="#saved-card" role="tab"
          aria-controls="saved-card" aria-selected="false"> <i class="iconsax" data-icon="bank-card"></i>Saved
          Card</button>
        </li>
        <li>
          <button class="nav-link" id="address-tab" data-bs-toggle="pill" data-bs-target="#address" role="tab"
          aria-controls="address" aria-selected="false"><i class="iconsax"
            data-icon="cue-cards"></i>Address</button>
        </li>
        <li>
          <button class="nav-link" id="privacy-tab" data-bs-toggle="pill" data-bs-target="#privacy" role="tab"
          aria-controls="privacy" aria-selected="false"> <i class="iconsax"
            data-icon="security-user"></i>Privacy</button>
        </li>
        </ul>
        <div class="logout-button"> <a class="btn btn_black sm" data-bs-toggle="modal"
          data-bs-target="#Confirmation-modal" title="Quick View" tabindex="0"><i class="iconsax me-1"
          data-icon="logout-1"></i> Logout </a></div>
      </div>
      </div>
      <div class="col-xl-9 col-lg-8">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
        <div class="dashboard-right-box">
          <div class="my-dashboard-tab">
          <div class="dashboard-items"> </div>
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Bảng điều khiển của tôi
            </h4>
          </div>
          <div class="dashboard-user-name">
            <h6>Xin chào, <b>{{$user['fullname']}}</b></h6>
            <p>My dashboard provides a comprehensive overview of key metrics and data relevant to your operations.
            It offers real-time insights into performance, including sales figures, website traffic, customer
            engagement, and more. With customizable widgets and intuitive visualizations, it facilitates quick
            decision-making and allows you to track progress towards your goals effectively.</p>
          </div>
          <div class="total-box">
            <div class="row gy-4">
            <div class="col-xl-4">
              <div class="totle-contain">
              <div class="wallet-point"><img
                src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/wallet.svg" alt=""><img
                class="img-1" src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/wallet.svg"
                alt=""></div>
              <div class="totle-detail">
                <h6>Số dư hiện tại</h6>
                <h4>$ 84.40 </h4>
              </div>
              </div>
            </div>
            <div class="col-xl-4">
              <div class="totle-contain">
              <div class="wallet-point"><img
                src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/coin.svg" alt=""><img
                class="img-1" src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/coin.svg"
                alt=""></div>
              <div class="totle-detail">
                <h6>Total Points</h6>
                <h4>500</h4>
              </div>
              </div>
            </div>
            <div class="col-xl-4">
              <div class="totle-contain">
              <div class="wallet-point"><img
                src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/order.svg" alt=""><img
                class="img-1" src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/order.svg"
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
                <p>{{$user['fullname']}}</p>
              </li>
              <li>
                <h6>Số điện thoại:</h6>
                @if(!empty($user['phone']))
          <p>{{ $user['phone'] }}</p>
          @else
          <p>
          Bạn chưa có thông tin hotline !
          <span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
            tabindex="0">Thêm</span>
          </p>
          @endif
              </li>
              <li>
                <h6>Địa chỉ:</h6>
                @if(!empty($user['address']))
          <p>{{ $user['address'] }}</p>
          @else
          <p>
          Bạn chưa có địa chỉ !
          <span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
            tabindex="0">Thêm</span>
          </p>
          @endif
              </li>


              {{-- <li>
                <h6>Address:</h6>
                <p>26, Starts Hollow Colony Denver, Colorado, United States 80014</p>
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
                <span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
                  tabindex="0">Edit</span>
                </p>
              </li>
              <li>
                <h6>Password :</h6>
                <p>●●●●●●<span data-bs-toggle="modal" data-bs-target="#edit-password" title="Quick View"
                  tabindex="0">Edit</span></p>
              </li>
              </ul>
            </div>
            <div class="col-xl-5">
              <div class="profile-image d-none d-xl-block"> <img class="img-fluid"
                src="{{ asset('assets/client/images/other-img/dashboard.png') }}" alt=""></div>
            </div>
            </div>

          </div>
          </div>
        </div>
        </div>
        <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
        <div class="dashboard-right-box">
          <div class="notification-tab">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Notifications</h4>
          </div>
          <ul class="notification-body">
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/1.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Mint - is your budget ready for spring spending?<span>2:14PM</span></h6>
              <p>A quick weekend trip, a staycation in your own town, or a weeklong vacay with the family—it’s
              your choice if it’s in the budget. No matter what you plan on doing during spring break, budget
              ahead for it.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/2.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/3.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Figma - Move work forward<span>2:14PM</span></h6>
              <p>Hello, Everyone understands why a new language would be advantageous: one could refuse to pay
              for high-priced translators.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/4.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Trip Reminder.<span>2:14PM</span></h6>
              <p>I'm sorry, but I have to disagree with Mr. Zingier. We are all aware that the title is the most
              crucial component of any article. Your reader won't even make it to the first sentence without
              an engaging title. </p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/5.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/6.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/7.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Figma - Move work forward<span>2:14PM</span></h6>
              <p>Hello, Everyone understands why a new language would be advantageous: one could refuse to pay
              for high-priced translators.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/8.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Trip Reminder.<span>2:14PM</span></h6>
              <p>I'm sorry, but I have to disagree with Mr. Zingier. We are all aware that the title is the most
              crucial component of any article. Your reader won't even make it to the first sentence without
              an engaging title.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/9.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/10.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/11.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Figma - Move work forward<span>2:14PM</span></h6>
              <p>Hello, Everyone understands why a new language would be advantageous: one could refuse to pay
              for high-priced translators.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/12.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Trip Reminder.<span>2:14PM</span></h6>
              <p>I'm sorry, but I have to disagree with Mr. Zingier. We are all aware that the title is the most
              crucial component of any article. Your reader won't even make it to the first sentence without
              an engaging title. </p>
            </div>
            </li>
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
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="#"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/1.jpg') }}" alt="product"></a><a
                class="pro-sec" href="#"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/20.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv1">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="#">
                <h6>Greciilooks Women's Stylish Top</h6>
              </a>
              <p>$100.00
                <del>$140.00</del><span>-20%</span>
              </p>
              </div>
              <ul class="profile-information mb-0">
              <li>
                <h6>Email :</h6>
                <p>john.customer@example.com<span data-bs-toggle="modal" data-bs-target="#edit-email"
                  title="Quick View" tabindex="0">Edit</span></p>
              </li>
              <li>
                <h6>Password :</h6>
                <p>●●●●●●<span data-bs-toggle="modal" data-bs-target="#edit-password" title="Quick View"
                  tabindex="0">Edit</span></p>
              </li>
              </ul>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/2.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/19.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Wide Linen-Blend Trousers</h6>
              </a>
              <p>$100.00
                <del>$18.00 </del>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/3.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/18.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Long Sleeve Rounded T-Shirt</h6>
              </a>
              <p>$120.30
                <del>$140.00</del><span>-20%</span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/4.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/17.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv2">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Blue lined White T-Shirt</h6>
              </a>
              <p>$190.00
                <del>$210.00</del>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/9.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/16.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv3">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Greciilooks Women's Stylish Top</h6>
              </a>
              <p>$100.00
                <del>$140.00</del><span>-20%</span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/10.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/15.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Wide Linen-Blend Trousers</h6>
              </a>
              <p>$100.00
                <del>$18.00 </del>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/11.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/14.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Long Sleeve Rounded T-Shirt</h6>
              </a>
              <p>$120.30
                <del>$140.00</del><span>-20% </span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/12.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/13.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv4">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Blue lined White T-Shirt</h6>
              </a>
              <p>$190.00
                <del>$210.00</del>
              </p>
              </div>
            </div>
            </div>
          </div>
          </div>
        </div>
        </div>
        <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
        <div class="dashboard-right-box">
          <div class="order">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>My Orders History</h4>
          </div>
          <div class="row gy-4">
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="box"></i>
                <div class="couplet"><i class="fa-solid fa-check"></i></div>
              </div>
              <div class="order-detail">
                <h5>Delivered</h5>
                <p>on Fri, 1 Mar</p>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/1.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-solid fa-star"> </i><i class="fa-solid fa-star"> </i><i
                  class="fa-solid fa-star"> </i><i class="fa-solid fa-star-half-stroke"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul><span data-bs-toggle="modal" data-bs-target="#Reviews-modal" title="Quick View"
                tabindex="0">Write Review</span>
              </div>
              <h6> <span> </span>* Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="undo"></i>
                <div class="couplet"><i class="fa-solid fa-check"></i></div>
              </div>
              <div class="order-detail">
                <h5>Refund Credited</h5>
                <p>
                Your Refund Of <b> $389.00 </b>For then return has been processed Successfully on 4th Apr.<a
                  href="#"> View Refund details</a></p>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/9.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul>
              </div>
              <h6>
                * Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="box"></i>
                <div class="couplet"><i class="fa-solid fa-check"></i></div>
              </div>
              <div class="order-detail">
                <h5>Delivered</h5>
                <p>on Fri, 1 Mar</p>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/2.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-solid fa-star"> </i><i class="fa-solid fa-star"> </i><i
                  class="fa-solid fa-star"> </i><i class="fa-solid fa-star-half-stroke"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul><span data-bs-toggle="modal" data-bs-target="#Reviews-modal" title="Quick View"
                tabindex="0">Write Review</span>
              </div>
              <h6>
                * Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="box-add"></i>
                <div class="couplet"><i class="fa-solid fa-xmark"></i></div>
              </div>
              <div class="order-detail">
                <h5>Cancelled</h5>
                <p>on Fri, 1 Mar</p>
                <h6> <b>Refund lanitiated : </b>$774.00 on Thu, 24 Feb 2024. <a href="#"> View
                  Refunddetails</a></h6>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/6.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul>
              </div>
              <h6>
                * Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
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
                src="{{ asset('assets/client/images/bank-card/bank1.png') }}" alt="bank1">
                <div class="card-type"><img class="bank-card"
                  src="{{ asset('assets/client/images/bank-card/1.png') }}" alt="card"></div>
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
                <div class="btn-box"><span data-bs-toggle="modal" data-bs-target="#edit-bank-card"
                  title="Quick View" tabindex="0"><i class="iconsax" data-icon="edit-1"></i></span><span
                  data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Quick View"
                  tabindex="0"><i class="iconsax" data-icon="trash"></i></span></div>
              </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-6">
              <div class="payment-card">
              <div class="bank-info"><img class="bank"
                src="{{ asset('assets/client/images/bank-card/bank2.png') }}" alt="bank1">
                <div class="card-type"><img class="bank-card"
                  src="{{ asset('assets/client/images/bank-card/2.png') }}" alt="card"></div>
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
                <div class="btn-box"><span data-bs-toggle="modal" data-bs-target="#edit-bank-card"
                  title="Quick View" tabindex="0"><i class="iconsax" data-icon="edit-1"></i></span><span
                  data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Quick View"
                  tabindex="0"><i class="iconsax" data-icon="trash"></i></span></div>
              </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-6">
              <div class="payment-card">
              <div class="bank-info"><img class="bank"
                src="{{ asset('assets/client/images/bank-card/bank3.png') }}" alt="bank1">
                <div class="card-type"><img class="bank-card"
                  src="{{ asset('assets/client/images/bank-card/3.png') }}" alt="card"></div>
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
                <div class="btn-box"><span data-bs-toggle="modal" data-bs-target="#edit-bank-card"
                  title="Quick View" tabindex="0"><i class="iconsax" data-icon="edit-1"></i></span><span
                  data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Quick View"
                  tabindex="0"><i class="iconsax" data-icon="trash"></i></span></div>
              </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-6">
              <div class="payment-card">
              <div class="add-card">
                <h6 data-bs-toggle="modal" data-bs-target="#add-bank-card" title="Quick View" tabindex="0">+
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
            <h4>My Address Details</h4>
          </div>
          <div class="row gy-3">
            <div class="col-xxl-4 col-md-6">
            <div class="address-option">
              <label for="address-billing-0"> <span class="delivery-address-box"> <span class="form-check">
                <input class="custom-radio" id="address-billing-0" type="radio" checked="checked"
                  name="radio"></span><span class="address-detail"><span class="address"> <span
                  class="address-title">New Home </span></span><span class="address"> <span
                  class="address-home"> <span class="address-tag"> Address:</span>26, Starts Hollow
                  Colony, Denver, Colorado, United States</span></span><span class="address"> <span
                  class="address-home"> <span class="address-tag">Pin Code:</span>80014</span></span><span
                  class="address"> <span class="address-home"> <span class="address-tag">Phone :</span>+1
                  5551855359</span></span></span></span><span class="buttons"> <a class="btn btn_black sm"
                href="#" data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
                tabindex="0">Edit
                </a><a class="btn btn_outline sm" href="#" data-bs-toggle="modal"
                data-bs-target="#bank-card-modal" title="Quick View" tabindex="0">Delete </a></span></label>
            </div>
            </div>
            <div class="col-xxl-4 col-md-6">
            <div class="address-option">
              <label for="address-billing-3"> <span class="delivery-address-box"> <span class="form-check">
                <input class="custom-radio" id="address-billing-3" type="radio" name="radio"></span><span
                class="address-detail"><span class="address"> <span class="address-title">IT
                  Office</span></span><span class="address"> <span class="address-home"> <span
                    class="address-tag"> Address:</span>55B, Claire Cav Street, San Jose, California,
                  United States</span></span><span class="address"> <span class="address-home"> <span
                    class="address-tag">Pin Code:</span>94088</span></span><span class="address"> <span
                  class="address-home"> <span class="address-tag">Phone :</span>+1
                  5551855359</span></span></span></span><span class="buttons"> <a class="btn btn_black sm"
                href="#" data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
                tabindex="0">Edit
                </a><a class="btn btn_outline sm" href="#" data-bs-toggle="modal"
                data-bs-target="#bank-card-modal" title="Quick View" tabindex="0">Delete</a></span></label>
            </div>
            </div>
            <div class="col-xxl-4 col-md-6">
            <div class="address-option">
              <label for="address-billing-2"> <span class="delivery-address-box"> <span class="form-check">
                <input class="custom-radio" id="address-billing-2" type="radio" name="radio"></span><span
                class="address-detail"><span class="address"> <span class="address-title">IT
                  Office</span></span><span class="address"> <span class="address-home"> <span
                    class="address-tag"> Address:</span>55B, Claire Cav Street, San Jose, California,
                  United States</span></span><span class="address"> <span class="address-home"> <span
                    class="address-tag">Pin Code:</span>94088</span></span><span class="address"> <span
                  class="address-home"> <span class="address-tag">Phone :</span>+1
                  5551855359</span></span></span></span><span class="buttons"> <a class="btn btn_black sm"
                href="#" data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
                tabindex="0">Edit
                </a><a class="btn btn_outline sm" href="#" data-bs-toggle="modal"
                data-bs-target="#bank-card-modal" title="Quick View" tabindex="0">Delete</a></span></label>
            </div>
            </div>
            <div class="col-xxl-4 col-md-6">
            <div class="address-option">
              <label for="address-billing-2"> <span class="delivery-address-box"> <span class="form-check">
                <input class="custom-radio" id="address-billing-2" type="radio" name="radio"></span><span
                class="address-detail"><span class="address"> <span class="address-title">IT
                  Office</span></span><span class="address"> <span class="address-home"> <span
                    class="address-tag"> Address:</span>55B, Claire Cav Street, San Jose, California,
                  United States</span></span><span class="address"> <span class="address-home"> <span
                    class="address-tag">Pin Code:</span>94088</span></span><span class="address"> <span
                  class="address-home"> <span class="address-tag">Phone :</span>+1
                  5551855359</span></span></span></span><span class="buttons"> <a class="btn btn_black sm"
                href="#" data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View"
                tabindex="0">Edit
                </a><a class="btn btn_outline sm" href="#" data-bs-toggle="modal"
                data-bs-target="#bank-card-modal" title="Quick View" tabindex="0">Delete</a></span></label>
            </div>
            </div>
          </div>
          <button class="btn add-address" data-bs-toggle="modal" data-bs-target="#add-address" title="Quick View"
            tabindex="0">+ Add Address</button>
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
              <p>Choose who can access your app and if users need to <a href="sign-up.html"> sign up.</a></p>
            </div><span class="short-title">access</span>
            <ul class="privacy-items">
              <li>
              <div class="privacy-icon"> <i class="iconsax" data-icon="lock-2"></i></div>
              <div class="privacy-contant">
                <h6>Private</h6>
                <p>Only users you choose can access</p>
              </div>
              <label class="switch">
                <input type="checkbox" checked=""><span class="slider round"></span>
              </label>
              </li>
              <li>
              <div class="privacy-icon"> <i class="iconsax" data-icon="globe"></i></div>
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
              <div class="privacy-icon"> <i class="iconsax" data-icon="package"></i></div>
              <div class="privacy-contant">
                <h6>Users in the users table </h6>
                <p>Only users in the users table can sign in </p>
              </div>
              <label class="switch">
                <input type="checkbox" checked=""><span class="slider round"></span>
              </label>
              </li>
              <li>
              <div class="privacy-icon"> <i class="iconsax" data-icon="fingerprint-circle"></i></div>
              <div class="privacy-contant">
                <h6>ongoing production team </h6>
                <p>only members of your team can sign in </p>
              </div>
              <label class="switch">
                <input type="checkbox"><span class="slider round"></span>
              </label>
              </li>
              <li>
              <div class="privacy-icon"> <i class="iconsax" data-icon="add-layer"></i></div>
              <div class="privacy-contant">
                <h6>anyone form domain(s)</h6>
                <p>only users with your email domain </p>
              </div>
              <label class="switch">
                <input type="checkbox" checked=""><span class="slider round"></span>
              </label>
              </li>
              <li>
              <div class="privacy-icon"> <i class="iconsax" data-icon="mail"></i></div>
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
        <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
        <div class="dashboard-right-box">
          <div class="notification-tab">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Notifications</h4>
          </div>
          <ul class="notification-body">
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/1.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Mint - is your budget ready for spring spending?<span>2:14PM</span></h6>
              <p>A quick weekend trip, a staycation in your own town, or a weeklong vacay with the family—it’s
              your choice if it’s in the budget. No matter what you plan on doing during spring break, budget
              ahead for it.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/2.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/3.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Figma - Move work forward<span>2:14PM</span></h6>
              <p>Hello, Everyone understands why a new language would be advantageous: one could refuse to pay
              for high-priced translators.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/4.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Trip Reminder.<span>2:14PM</span></h6>
              <p>I'm sorry, but I have to disagree with Mr. Zingier. We are all aware that the title is the most
              crucial component of any article. Your reader won't even make it to the first sentence without
              an engaging title. </p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/5.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/6.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/7.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Figma - Move work forward<span>2:14PM</span></h6>
              <p>Hello, Everyone understands why a new language would be advantageous: one could refuse to pay
              for high-priced translators.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/8.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Trip Reminder.<span>2:14PM</span></h6>
              <p>I'm sorry, but I have to disagree with Mr. Zingier. We are all aware that the title is the most
              crucial component of any article. Your reader won't even make it to the first sentence without
              an engaging title.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/9.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/10.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
              <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address
              verified:</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/11.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Figma - Move work forward<span>2:14PM</span></h6>
              <p>Hello, Everyone understands why a new language would be advantageous: one could refuse to pay
              for high-priced translators.</p>
            </div>
            </li>
            <li>
            <div class="user-img"> <img src="{{ asset('assets/client/images/notification/12.jpg') }}" alt="">
            </div>
            <div class="user-contant">
              <h6>Trip Reminder.<span>2:14PM</span></h6>
              <p>I'm sorry, but I have to disagree with Mr. Zingier. We are all aware that the title is the most
              crucial component of any article. Your reader won't even make it to the first sentence without
              an engaging title. </p>
            </div>
            </li>
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
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="#"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/1.jpg') }}" alt="product"></a><a
                class="pro-sec" href="#"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/20.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv1">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="#">
                <h6>Greciilooks Women's Stylish Top</h6>
              </a>
              <p>$100.00
                <del>$140.00</del><span>-20%</span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/2.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/19.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Wide Linen-Blend Trousers</h6>
              </a>
              <p>$100.00
                <del>$18.00 </del>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/3.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/18.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Long Sleeve Rounded T-Shirt</h6>
              </a>
              <p>$120.30
                <del>$140.00</del><span>-20%</span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/4.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/17.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv2">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Blue lined White T-Shirt</h6>
              </a>
              <p>$190.00
                <del>$210.00</del>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/9.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/16.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv3">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Greciilooks Women's Stylish Top</h6>
              </a>
              <p>$100.00
                <del>$140.00</del><span>-20%</span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/10.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/15.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-regular fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Wide Linen-Blend Trousers</h6>
              </a>
              <p>$100.00
                <del>$18.00 </del>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/11.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/14.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Long Sleeve Rounded T-Shirt</h6>
              </a>
              <p>$120.30
                <del>$140.00</del><span>-20% </span>
              </p>
              </div>
            </div>
            </div>
            <div class="col">
            <div class="product-box-3 product-wishlist">
              <div class="img-wrapper">
              <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                  data-icon="trash" aria-hidden="true"></i></a></div>
              <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/12.jpg') }}" alt="product"></a><a
                class="pro-sec" href="product.html"> <img class="bg-img"
                  src="{{ asset('assets/client/images/product/product-3/13.jpg') }}" alt="product"></a>
              </div>
              <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true">
                </i></a><a href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                  data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                  data-icon="eye" aria-hidden="true"></i></a></div>
              <div class="countdown">
                <ul class="clockdiv4">
                <li>
                  <div class="timer">
                  <div class="days"></div>
                  </div><span class="title">Days</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="hours"></div>
                  </div><span class="title">Hours</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="minutes"></div>
                  </div><span class="title">Min</span>
                </li>
                <li class="dot"> <span>:</span></li>
                <li>
                  <div class="timer">
                  <div class="seconds"></div>
                  </div><span class="title">Sec</span>
                </li>
                </ul>
              </div>
              </div>
              <div class="product-detail">
              <ul class="rating">
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star"></i></li>
                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                <li>4.3</li>
              </ul><a href="product.html">
                <h6>Blue lined White T-Shirt</h6>
              </a>
              <p>$190.00
                <del>$210.00</del>
              </p>
              </div>
            </div>
            </div>
          </div>
          </div>
        </div>
        </div>
        <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
        <div class="dashboard-right-box">
          <div class="order">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>My Orders History</h4>
          </div>
          <div class="row gy-4">
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="box"></i>
                <div class="couplet"><i class="fa-solid fa-check"></i></div>
              </div>
              <div class="order-detail">
                <h5>Delivered</h5>
                <p>on Fri, 1 Mar</p>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/1.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-solid fa-star"> </i><i class="fa-solid fa-star"> </i><i
                  class="fa-solid fa-star"> </i><i class="fa-solid fa-star-half-stroke"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul><span data-bs-toggle="modal" data-bs-target="#Reviews-modal" title="Quick View"
                tabindex="0">Write Review</span>
              </div>
              <h6> <span> </span>* Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="undo"></i>
                <div class="couplet"><i class="fa-solid fa-check"></i></div>
              </div>
              <div class="order-detail">
                <h5>Refund Credited</h5>
                <p>
                Your Refund Of <b> $389.00 </b>For then return has been processed Successfully on 4th Apr.<a
                  href="#"> View Refund details</a></p>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/9.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul>
              </div>
              <h6>
                * Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="box"></i>
                <div class="couplet"><i class="fa-solid fa-check"></i></div>
              </div>
              <div class="order-detail">
                <h5>Delivered</h5>
                <p>on Fri, 1 Mar</p>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/2.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-solid fa-star"> </i><i class="fa-solid fa-star"> </i><i
                  class="fa-solid fa-star"> </i><i class="fa-solid fa-star-half-stroke"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul><span data-bs-toggle="modal" data-bs-target="#Reviews-modal" title="Quick View"
                tabindex="0">Write Review</span>
              </div>
              <h6>
                * Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
            <div class="col-12">
            <div class="order-box">
              <div class="order-container">
              <div class="order-icon"><i class="iconsax" data-icon="box-add"></i>
                <div class="couplet"><i class="fa-solid fa-xmark"></i></div>
              </div>
              <div class="order-detail">
                <h5>Cancelled</h5>
                <p>on Fri, 1 Mar</p>
                <h6> <b>Refund lanitiated : </b>$774.00 on Thu, 24 Feb 2024. <a href="#"> View
                  Refunddetails</a></h6>
              </div>
              </div>
              <div class="product-order-detail">
              <div class="product-box"> <a href="product.html"> <img
                  src="{{ asset('assets/client/images/notification/6.jpg') }}" alt=""></a>
                <div class="order-wrap">
                <h5>Rustic Minidress with Halterneck</h5>
                <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins
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
                <li> <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i></li>
                </ul>
              </div>
              <h6>
                * Exchange/Return window closed on 20 mar</h6>
              </div>
            </div>
            </div>
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
                src="{{ asset('assets/client/images/bank-card/bank1.png') }}" alt="bank1">
                <div class="card-type"><img class="bank-card"
                  src="{{ asset('assets/client/images/bank-card/1.png') }}" alt="card"></div>
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
                <div class="btn-box"><span data-bs-toggle="modal" data-bs-target="#edit-bank-card"
                  title="Quick View" tabindex="0"><i class="iconsax" data-icon="edit-1"></i></span><span
                  data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Quick View"
                  tabindex="0"><i class="iconsax" data-icon="trash"></i></span></div>
              </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-6">
              <div class="payment-card">
              <div class="bank-info"><img class="bank"
                src="{{ asset('assets/client/images/bank-card/bank2.png') }}" alt="bank1">
                <div class="card-type"><img class="bank-card"
                  src="{{ asset('assets/client/images/bank-card/2.png') }}" alt="card"></div>
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
                <div class="btn-box"><span data-bs-toggle="modal" data-bs-target="#edit-bank-card"
                  title="Quick View" tabindex="0"><i class="iconsax" data-icon="edit-1"></i></span><span
                  data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Quick View"
                  tabindex="0"><i class="iconsax" data-icon="trash"></i></span></div>
              </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-6">
              <div class="payment-card">
              <div class="bank-info"><img class="bank"
                src="{{ asset('assets/client/images/bank-card/bank3.png') }}" alt="bank1">
                <div class="card-type"><img class="bank-card"
                  src="{{ asset('assets/client/images/bank-card/3.png') }}" alt="card"></div>
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
                <div class="btn-box"><span data-bs-toggle="modal" data-bs-target="#edit-bank-card"
                  title="Quick View" tabindex="0"><i class="iconsax" data-icon="edit-1"></i></span><span
                  data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Quick View"
                  tabindex="0"><i class="iconsax" data-icon="trash"></i></span></div>
              </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-6">
              <div class="payment-card">
              <div class="add-card">
                <h6 data-bs-toggle="modal" data-bs-target="#add-bank-card" title="Quick View" tabindex="0">+
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
            <h4>My Address Details</h4>
          </div>
          <div class="d-flex justify-content-end mb-3">
            <button class="btn add-address" data-bs-toggle="modal" data-bs-target="#add-address"
            title="Thêm địa chỉ" tabindex="0">+ Thêm Địa Chỉ</button>
          </div>

          <div class="row gy-3">
            @foreach ($addresses as $address)
        <div class="col-xxl-4 col-md-6">
        <div class="address-option">
          <label for="address-{{ $address->id }}">
          <span class="delivery-address-box">
          <span class="form-check">
          <input class="custom-radio" id="address-{{ $address->id }}" type="radio" {{ $address->is_default ? 'checked' : '' }} name="default_address"
            onchange="document.getElementById('set-default-{{ $address->id }}').submit();" />
          </span>
          <span class="address-detail">
          <span class="address">
            <span class="address-title">{{ $address->title }}</span>
          </span>
          <span class="address">
            <span class="address-home">
            <span class="address-tag"> Địa chỉ:</span>
            {{ $address->address }}, {{ $address->city }}, {{ $address->state }},
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
            <span class="address-tag">Điện thoại :</span>{{ $address->phone }}</span>
          </span></span></span><span class="buttons"> <a class="btn btn_black sm" href="#"
          data-bs-toggle="modal" data-bs-target="#editAddressModal-{{ $address->id }}"
          title="Quick View" tabindex="0">Sửa
          </a><a class="btn btn_outline sm" href="#" data-bs-toggle="modal"
          data-bs-target="#deleteAddressModal-{{ $address->id }}" title="Quick View" tabindex="0">Xóa
          </a></span></label>
        </div>
        </div>
        {{-- Edit modal --}}
        <div class="reviews-modal modal theme-modal" id="editAddressModal-{{ $address->id }}" tabindex="-1"
        role="dialog" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
          <div class="modal-header">
          <h4>Sửa địa chỉ</h4>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pt-0">
          <form action="{{ route('client.account.address.update', $address->id) }}" method="POST"
          class="row g-3">
          @csrf
          @method("PUT")

          <div class="col-12">
            <label style="font-weight: 600; color: #000;">Loại địa chỉ</label>
            <select class="form-control form-select" name="title">
            <option value="Nhà riêng" {{ old('title', $address->title) == 'Nhà riêng' ? 'selected' : '' }}>Nhà riêng</option>
            <option value="Công ty" {{ old('title', $address->title) == 'Công ty' ? 'selected' : '' }}>Công ty</option>
            <option value="Khác" {{ old('title', $address->title) == 'Khác' ? 'selected' : '' }}>Khác
            </option>
            </select>
            @error('title')
        <small class="text-danger">{{ $message }}</small>
        @enderror
          </div>

          <div class="col-6">
            <div class="from-group">
            <label class="form-label">Điện Thoại</label>
            <input class="form-control" type="text" name="phone"
            value="{{ old('phone', $address->phone) }}" placeholder="Nhập số điện thoại">
            @error('phone')
        <small class="text-danger">{{ $message }}</small>
        @enderror
            </div>
          </div>

          <div class="col-6">
            <div class="from-group">
            <label class="form-label">Mã Bưu chính</label>
            <input class="form-control" name="pincode" type="text"
            value="{{ old('pincode', $address->pincode) }}">
            @error('pincode')
        <small class="text-danger">{{ $message }}</small>
        @enderror
            </div>
          </div>

          <div class="col-6">
            <div class="from-group">
            <label class="form-label">Quốc gia</label>
            <input class="form-control" type="text" name="country"
            value="{{ old('country', $address->country) }}">
            @error('country')
        <small class="text-danger">{{ $message }}</small>
        @enderror
            </div>
          </div>

          <div class="col-6">
            <div class="from-group">
            <label class="form-label">Tỉnh/Thành Phố</label>
            <input class="form-control" name="state" type="text"
            value="{{ old('state', $address->state) }}">
            @error('state')
        <small class="text-danger">{{ $message }}</small>
        @enderror
            </div>
          </div>

          <div class="col-6">
            <div class="from-group">
            <label class="form-label">Quận/Huyện</label>
            <input class="form-control" name="city" type="text"
            value="{{ old('city', $address->city) }}">
            @error('city')
        <small class="text-danger">{{ $message }}</small>
        @enderror
            </div>
          </div>

          <div class="col-12">
            <div class="from-group">
            <label class="form-label">Địa chỉ</label>
            <textarea class="form-control" name="address" cols="30" rows="4"
            placeholder="Nhập địa chỉ">{{ old('address', $address->address) }}</textarea>
            @error('address')
        <small class="text-danger">{{ $message }}</small>
        @enderror
            </div>
          </div>

          <button class="btn btn-submit" type="submit">Cập nhật</button>
          </form>
          </div>
          </div>
        </div>
        </div>


        {{-- Delete Modal --}}
        <div class="modal theme-modal confirmation-modal" id="deleteAddressModal-{{ $address->id }}"
        tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
          <div class="modal-body">
          <img class="img-fluid" src="../assets/images/gif/question.gif" alt="">
          <h4>Xác nhận xóa địa chỉ?</h4>
          <p>Địa chỉ sẽ bị xóa vĩnh viễn khỏi danh sách. Bạn có muốn tiếp tục?</p>
          <form action="{{ route('client.account.address.destroy', $address->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <div class="submit-button">
            <button class="btn" type="button" data-bs-dismiss="modal" aria-label="Close">Hủy</button>
            <button class="btn" type="submit" data-bs-dismiss="modal" aria-label="Close">Đồng
            ý</button>
          </div>
          </form>
          </div>
          </div>
        </div>
        </div>

        {{-- Form update default address --}}
        <form id="set-default-{{ $address->id }}"
        action="{{ route('client.account.address.setDefault', $address->id) }}" method="POST"
        style="display: none;">
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
              <p>Choose who can access your app and if users need to <a href="sign-up.html"> sign up.</a></p>
              </div><span class="short-title">access</span>
              <ul class="privacy-items">
              <li>
                <div class="privacy-icon"> <i class="iconsax" data-icon="lock-2"></i></div>
                <div class="privacy-contant">
                <h6>Private</h6>
                <p>Only users you choose can access</p>
                </div>
                <label class="switch">
                <input type="checkbox" checked=""><span class="slider round"></span>
                </label>
              </li>
              <li>
                <div class="privacy-icon"> <i class="iconsax" data-icon="globe"></i></div>
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
                <div class="privacy-icon"> <i class="iconsax" data-icon="package"></i></div>
                <div class="privacy-contant">
                <h6>Users in the users table </h6>
                <p>Only users in the users table can sign in </p>
                </div>
                <label class="switch">
                <input type="checkbox" checked=""><span class="slider round"></span>
                </label>
              </li>
              <li>
                <div class="privacy-icon"> <i class="iconsax" data-icon="fingerprint-circle"></i></div>
                <div class="privacy-contant">
                <h6>ongoing production team </h6>
                <p>only members of your team can sign in </p>
                </div>
                <label class="switch">
                <input type="checkbox"><span class="slider round"></span>
                </label>
              </li>
              <li>
                <div class="privacy-icon"> <i class="iconsax" data-icon="add-layer"></i></div>
                <div class="privacy-contant">
                <h6>anyone form domain(s)</h6>
                <p>only users with your email domain </p>
                </div>
                <label class="switch">
                <input type="checkbox" checked=""><span class="slider round"></span>
                </label>
              </li>
              <li>
                <div class="privacy-icon"> <i class="iconsax" data-icon="mail"></i></div>
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
    </div>
  </section>


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
          <input class="form-control" type="text" name="review[author]" placeholder="Enter your name.">
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
          <input class="form-control" type="number" name="review[author]" placeholder="Enter your Number.">
        </div>
        </div>
        <button class="btn btn-submit" type="submit" data-bs-dismiss="modal" aria-label="Close">Submit</button>
      </div>
      </div>
    </div>
    </div>
  </div>

  <div class="reviews-modal modal theme-modal fade" id="add-address" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h4>Thêm địa chỉ</h4>
      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0">
      <form action="{{ route('client.account.address.store') }}" method="POST" class="row g-3">
        @csrf

        <div class="col-12">
        <div class="from-group position-relative">
          <label class="form-label">Loại địa chỉ</label>
          <select class="form-control form-select @error('title') is-invalid @enderror" name="title">
          <option value="">-- Chọn loại --</option>
          <option value="Nhà riêng" {{ old('title') == 'Nhà riêng' ? 'selected' : '' }}>Nhà riêng</option>
          <option value="Công ty" {{ old('title') == 'Công ty' ? 'selected' : '' }}>Công ty</option>
          <option value="Khác" {{ old('title') == 'Khác' ? 'selected' : '' }}>Khác</option>
          </select>
          @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Điện Thoại</label>
          <input class="form-control @error('phone') is-invalid @enderror" type="number" name="phone"
          value="{{ old('phone') }}" placeholder="Enter your Number.">
          @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Mã Bưu chính</label>
          <input class="form-control @error('pincode') is-invalid @enderror" name="pincode" type="text"
          value="{{ old('pincode') }}">
          @error('pincode') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Quốc gia</label>
          <input class="form-control @error('country') is-invalid @enderror" type="text" name="country"
          value="{{ old('country') }}">
          @error('country') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Tỉnh/Thành Phố</label>
          <input class="form-control @error('state') is-invalid @enderror" name="state" type="text"
          list="provinceList" value="{{ old('state') }}">
          <datalist id="provinceList">
          <option value="An Giang">
          <option value="Bà Rịa - Vũng Tàu">
          <option value="Bạc Liêu">
          <option value="Bắc Giang">
          <option value="Bắc Kạn">
          <option value="Bắc Ninh">
          <option value="Bến Tre">
          <option value="Bình Dương">
          <option value="Bình Định">
          <option value="Bình Phước">
          <option value="Bình Thuận">
          <option value="Cà Mau">
          <option value="Cao Bằng">
          <option value="Cần Thơ">
          <option value="Đà Nẵng">
          <option value="Đắk Lắk">
          <option value="Đắk Nông">
          <option value="Điện Biên">
          <option value="Đồng Nai">
          <option value="Đồng Tháp">
          <option value="Gia Lai">
          <option value="Hà Giang">
          <option value="Hà Nam">
          <option value="Hà Nội">
          <option value="Hà Tĩnh">
          <option value="Hải Dương">
          <option value="Hải Phòng">
          <option value="Hậu Giang">
          <option value="Hòa Bình">
          <option value="Hưng Yên">
          <option value="Khánh Hòa">
          <option value="Kiên Giang">
          <option value="Kon Tum">
          <option value="Lai Châu">
          <option value="Lạng Sơn">
          <option value="Lào Cai">
          <option value="Lâm Đồng">
          <option value="Long An">
          <option value="Nam Định">
          <option value="Nghệ An">
          <option value="Ninh Bình">
          <option value="Ninh Thuận">
          <option value="Phú Thọ">
          <option value="Phú Yên">
          <option value="Quảng Bình">
          <option value="Quảng Nam">
          <option value="Quảng Ngãi">
          <option value="Quảng Ninh">
          <option value="Quảng Trị">
          <option value="Sóc Trăng">
          <option value="Sơn La">
          <option value="Tây Ninh">
          <option value="Thái Bình">
          <option value="Thái Nguyên">
          <option value="Thanh Hóa">
          <option value="Thừa Thiên Huế">
          <option value="Tiền Giang">
          <option value="TP. Hồ Chí Minh">
          <option value="Trà Vinh">
          <option value="Tuyên Quang">
          <option value="Vĩnh Long">
          <option value="Vĩnh Phúc">
          <option value="Yên Bái">
          </datalist>
          @error('state') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Quận/Huyện</label>
          <input class="form-control @error('city') is-invalid @enderror" name="city" type="text"
          value="{{ old('city') }}">
          @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-12">
        <div class="from-group">
          <label class="form-label">Địa chỉ</label>
          <textarea class="form-control @error('address') is-invalid @enderror" name="address" cols="30" rows="5"
          placeholder="Write your Address...">{{ old('address') }}</textarea>
          @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        </div>

        <div class="col-12">
        <button class="btn btn-submit" type="submit">Submit</button>
        </div>
      </form>
      </div>
    </div>
    </div>
  </div>

  {{-- Tự động mở lại modal nếu có lỗi --}}
  @if ($errors->any())
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    var modal = new bootstrap.Modal(document.getElementById('add-address'));
    modal.show();
    });
    </script>
  @endif

  <div class="reviews-modal modal theme-modal fade" id="edit-box" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add/Edit Address</h4>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body pt-0">
          <form method="POST">
            @csrf
            <div class="row g-3">

            <div class="col-6">
              <div class="from-group">
              <label class="form-label">Title</label>
              <input class="form-control" type="text" name="title" placeholder="e.g. Home, Office" required>
              </div>
            </div>

            <div class="col-6">
              <div class="from-group">
              <label class="form-label">Phone</label>
              <input class="form-control" type="text" name="phone" placeholder="Enter your phone number" required>
              </div>
            </div>

            <div class="col-12">
              <div class="from-group">
              <label class="form-label">Address</label>
              <textarea class="form-control" name="address" cols="30" rows="3" placeholder="Write your address..."
                required></textarea>
              </div>
            </div>

            <div class="col-4">
              <div class="from-group">
              <label class="form-label">Country</label>
              <input class="form-control" type="text" name="country" placeholder="Country" required>
              </div>
            </div>

            <div class="col-4">
              <div class="from-group">
              <label class="form-label">State</label>
              <input class="form-control" type="text" name="state" placeholder="State" required>
              </div>
            </div>

            <div class="col-4">
              <div class="from-group">
              <label class="form-label">City</label>
              <input class="form-control" type="text" name="city" placeholder="City" required>
              </div>
            </div>

            <div class="col-6">
              <div class="from-group">
              <label class="form-label">Pin Code</label>
              <input class="form-control" type="text" name="pincode" placeholder="Postal Code" required>
              </div>
            </div>

            <div class="col-6">
              <div class="form-check mt-4">
              <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
              <label class="form-check-label" for="isDefault">
                Set as Default Address
              </label>
              </div>
            </div>

            <div class="col-12">
              <button class="btn btn-submit" type="submit" data-bs-dismiss="modal" aria-label="Close">Submit</button>
            </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endsection

  <!-- Modal sửa địa chỉ -->
  @section('js')
    <script src="{{ asset('assets/client/js/dashboard-left-sidebar.js') }}"></script>
  @endsection
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
          <input class="form-control" type="text" name="review[author]" placeholder="Enter your name.">
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
          <input class="form-control" type="number" name="review[author]" placeholder="Enter your Number.">
        </div>
        </div>
        <button class="btn btn-submit" type="submit" data-bs-dismiss="modal" aria-label="Close">Submit</button>
      </div>
      </div>
    </div>
    </div>
  </div>
  {{-- END Edit Email Doashboar --}}
  {{-- EDIT PASSWORD START --}}
  <div class="reviews-modal modal theme-modal fade" id="edit-password" tabindex="-1" role="dialog" aria-modal="true">
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
            <input type="password" class="form-control" name="new_password" id="new_password"
            placeholder="Nhập mật khẩu mới">
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
            <span class="input-group-text toggle-password" toggle="#new_password_confirmation">
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
      <h4>Edit Profile</h4>
      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0">
      <div class="row g-3">
        <div class="col-6">
        <div class="from-group">
          <label class="form-label">First Name</label>
          <input class="form-control" type="text" name="review[author]" placeholder="Enter your name.">
        </div>
        </div>
        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Last Name</label>
          <input class="form-control" type="text" name="review[author]" placeholder="Enter your name.">
        </div>
        </div>
        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Email address</label>
          <input class="form-control" type="email" placeholder="john.smith@example.com">
        </div>
        </div>
        <div class="col-6">
        <div class="from-group">
          <label class="form-label">Phone</label>
          <input class="form-control" type="number" name="review[author]" placeholder="Enter your Number.">
        </div>
        </div>
        <div class="col-12">
        <div class="from-group">
          <label class="form-label">Address</label>
          <textarea class="form-control" cols="30" rows="5" placeholder="Write your Address..."></textarea>
        </div>
        </div>
        <button class="btn btn-submit" type="submit" data-bs-dismiss="modal" aria-label="Close">Submit</button>
      </div>
      </div>
    </div>
    </div>
  </div>
  {{-- END Edit Email Doashboar --}}

  <div class="reviews-modal modal theme-modal fade" id="edit-box" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h4>Edit Profile</h4>
      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="update-profile-form">
      @csrf
      <div class="modal-body pt-0">
        <div class="row g-3">

        <div class="col-6">
          <div class="from-group">
          <label class="form-label">Họ tên</label>
          <input class="form-control" type="text" name="fullname" value="{{ $user->fullname ?? '' }}">
          <div class="text-danger error-fullname"></div>
          </div>
        </div>

        <div class="col-6">
          <div class="form-group">
          <label class="form-label">Email</label>
          <input class="form-control" type="text" name="email" value="{{ $user->email ?? '' }}">
          <div class="text-danger error-email"></div>
          </div>
        </div>

        <div class="col-6">
          <div class="from-group">
          <label class="form-label">Số điện thoại</label>
          <input class="form-control" type="text" name="phone" value="{{ $user->phone ?? '' }}">
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
      </div>
      </form>

    </div>
    </div>
  </div>
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


@endsection
@section('css')
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



@section('js')
  <script src="{{ asset('assets/client/js/dashboard-left-sidebar.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('update-profile-form');

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // Ngăn reload

      const formData = new FormData(form);
      const url = '{{ route("client.account.profile.update") }}';

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

      .catch(error => {
        console.error('Lỗi:', error);
      });
    });
    });
  </script>
  {{-- EYE --}}
  <script>

    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-password').forEach(el => {
      el.addEventListener('click', function () {
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
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('change-password-form');
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(form);
      const url = '{{ route("client.account.change_password.submit") }}';
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
          const modal = bootstrap.Modal.getInstance(document.getElementById('edit-password'));
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

    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('update-profile-form');

    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(form);
      const url = '{{ route("client.account.profile.update") }}';

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
@endsection
