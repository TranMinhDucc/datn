<div class="custom-container container header-1">
    <div class="row">
        <div class="col-12 p-0">
            <div class="mobile-fix-option">
                <ul>
                    <li> <a href="index.html"><i class="iconsax" data-icon="home-1"></i>Home</a></li>
                    <li><a href="search.html"><i class="iconsax" data-icon="search-normal-2"></i>Search</a>
                    </li>
                    <li class="shopping-cart"> <a href="cart.html"><i class="iconsax"
                                data-icon="shopping-cart"></i>Cart</a></li>
                    <li><a href="wishlist.html"><i class="iconsax" data-icon="heart"></i>My Wish</a></li>
                    <li> <a href="{{ route('client.account.dashboard') }}"><i class="iconsax"
                                data-icon="user-2"></i>Account</a></li>
                </ul>
            </div>
            <div class="offcanvas offcanvas-start" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="staticBackdropLabel">
                <div class="offcanvas-header">
                    <h3 class="offcanvas-title" id="staticBackdropLabel">Offcanvas</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div></div>I will not close if you click outside of me.
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="main-menu">
                <a class="brand-logo" href="{{ url('/') }}">
                    <img class="img-fluid for-light" src="{{ asset('storage/' . $settings['logo_light']) }}" alt="logo">

                    <img class="img-fluid for-dark" src="{{ asset('storage/' . $settings['logo_dark']) }}" alt="logo">

                </a>
                @include('layouts.partials.client.navbar')

                <div class="sub_header">
                    <div class="toggle-nav" id="toggle-nav">
                        <i class="fa-solid fa-bars-staggered sidebar-bar"></i>
                    </div>
                    <ul class="justify-content-end">
                        <li>
                            <button href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop"
                                aria-controls="offcanvasTop"><i class="iconsax"
                                    data-icon="search-normal-2"></i></button>
                        </li>
                        <li>
                            <a href="{{ route('client.account.wishlist.index') }}"><i class="iconsax"
                                    data-icon="heart"></i>
                                <span class=""></span></a>
                        </li>
                        <li class="onhover-div">
                            <a href="#"><i class="iconsax" data-icon="user-2"></i></a>
                            <div class="onhover-show-div user">
                                <ul>
                                    @auth
                                    @if (Auth::user()->role === 'admin')
                                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                                    @endif
                                    <li><a href="{{ route('client.account.dashboard') }}">Thông tin tài khoản</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-start p-0"
                                                style="color: #000; text-decoration: none;">
                                                Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                    @else
                                    <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                                    @endauth
                                </ul>
                            </div>
                        </li>
                        <li class="onhover-div shopping-cart position-relative">
                            <a class="p-0" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                aria-controls="offcanvasRight">
                                <i class="iconsax pe-2 fs-5" data-icon="basket-2"></i>

                                <!-- Badge số lượng sản phẩm -->
                                <span class="cart_qty_cls" id="cart-count-badge">0</span>
                            </a>
                        </li>



                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    function updateCartBadge() {
        try {
            const cartData = localStorage.getItem('cartItems');
            const cart = cartData ? JSON.parse(cartData) : [];

            // Tính tổng quantity
            const totalItems = cart.reduce((sum, item) => {
                return sum + (parseInt(item.quantity) || 1);
            }, 0);

            const badge = document.getElementById('cart-count-badge');
            if (badge) {
                if (totalItems > 0) {
                    badge.textContent = totalItems;
                    badge.style.display = 'inline-block';
                } else {
                    badge.textContent = '0';
                    badge.style.display = 'none';
                }
            }
        } catch (e) {
            console.error('Lỗi khi cập nhật giỏ hàng:', e);
        }
    }

    // Cập nhật khi trang tải
    document.addEventListener('DOMContentLoaded', updateCartBadge);

    // 👉 Gọi lại updateCartBadge() mỗi khi bạn thêm hoặc xóa sản phẩm
</script>