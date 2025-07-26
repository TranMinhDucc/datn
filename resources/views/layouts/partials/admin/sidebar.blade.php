<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{ url('admin') }}">
            <img alt="Logo" src="{{ asset('assets/admin/media/logos/default-dark.svg') }}"
                class="h-25px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset('assets/admin/media/logos/default-small.svg') }}"
                class="h-20px app-sidebar-logo-minimize" />
        </a>

        <!--end::Logo image-->

        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="fa-regular fa-square-caret-left fs-3 rotate-180"></i>
            {{-- <i class="ki-duotone ki-black-left-line fs-3 rotate-180"> --}}
            <span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">

                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">
                    <!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Main
                            </span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.dashboard') }}"><span
                                class="menu-icon"><i class="fa-solid fa-table-columns fs-4"><span
                                        class="path1"></span><span class="path2"></span></i></span><span
                                class="menu-title">Dashboard</span></a><!--end:Menu link-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Dịch vụ
                            </span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                    class="fa-solid fa-cart-shopping fs-4"></i><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i></span><span
                                class="menu-title">Sản phẩm</span><span
                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.categories.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Chuyên
                                        mục</span></a><!--end:Menu link-->
                            </div>
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.products.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Tất cả sản
                                        phẩm</span></a><!--end:Menu link-->
                            </div>
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.product-labels.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Nhãn dán
                                    </span></a><!--end:Menu link-->
                            </div>
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.inventory.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Kho hàng
                                    </span></a><!--end:Menu link-->
                            </div>
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Quản lý</span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                   
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.users.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-solid fa-user fs-4"><span
                                        class="path1"></span><span class="path2"></span></i></span><span
                                class="menu-title">Thành viên</span></a><!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.orders.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-solid fa-box fs-4"><span
                                        class="path1"></span><span class="path2"></span></i></span><span
                                class="menu-title">Đơn hàng</span></a><!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('admin.reviews.index') }}" target="_blank">
                            <span class="menu-icon">
                                <i class="fa-solid fa-star fs-4"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </span>
                            <span class="menu-title">Đánh giá</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                    class="fa-solid fa-location-arrow fs-4"></i><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i></span><span
                                class="menu-title">Quản lý địa chỉ</span><span
                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.shipping-addresses.index') }}"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">Địa chỉ
                                        khách hàng</span></a><!--end:Menu link-->
                            </div>
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.shopSettings.edit') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Địa chỉ
                                        lấy hàng</span></a><!--end:Menu link-->
                            </div>
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('admin.shipping-addresses.index') }}" target="_blank">
                            <span class="menu-icon">
                                <i class="fa-solid fa-location-dot fs-4"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </span>
                            <span class="menu-title">Quản lý địa chỉ</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('admin.wishlists.index') }}" target="_blank">
                            <span class="menu-icon">
                                <i class="fa-solid fa-location-dot fs-4"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </span>
                            <span class="menu-title">Quản lý Yêu Thích
                        </a>
                        <!--end:Menu link-->
                    </div>


                    <div class="menu-item">

                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.dashboard') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-solid fa-user fs-4"><span
                                        class="path1"></span><span class="path2"></span></i></span><span
                                class="menu-title">Theme</span></a><!--end:Menu link-->
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                    class="fa-solid fa-wallet fs-4"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span><span
                                        class="path4"></span></i></span><span class="menu-title">Nạp
                                tiền</span><span
                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.bank.view_payment') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Ngân
                                        hàng</span></a><!--end:Menu link-->
                            </div>
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link" href="toolbars/saas.html"><span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                        class="menu-title">Thẻ cào</span></a><!--end:Menu link-->
                            </div>

                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->

                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.email_campaigns.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-solid fa-envelope fs-4"><span
                                        class="path1"></span><span class="path2"></span></i></span><span
                                class="menu-title">Email Campaigns</span></a><!--end:Menu link-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.coupons.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-solid fa-percent fs-4"><span
                                        class="path1"></span><span class="path2"></span><span
                                        class="path3"></span><span class="path4"></span></i></span><span
                                class="menu-title">Mã giảm
                                giá</span></a><!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.brands.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-regular fa-money-bill-1 fs-4"><span
                                        class="path1"></span><span class="path2"></span><span
                                        class="path3"></span><span class="path4"></span></i></span><span
                                class="menu-title">Thương
                                hiệu</span></a><!--end:Menu link-->
                    </div>

                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.faq.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-regular fa-money-bill-1 fs-4"><span
                                        class="path1"></span><span class="path2"></span><span
                                        class="path3"></span><span class="path4"></span></i></span><span
                                class="menu-title">FAQ</span></a><!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.tags.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-regular fa-money-bill-1 fs-4"><span
                                        class="path1"></span><span class="path2"></span><span
                                        class="path3"></span><span class="path4"></span></i></span><span
                                class="menu-title">Tags</span></a><!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                    class="fa-brands fa-blogger-b fs-4"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span><span
                                        class="path4"></span></i></span><span class="menu-title">Bài
                                viết</span><span
                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.blogs.create') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Viết bài
                                        mới</span></a><!--end:Menu link-->
                            </div>
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.blogs.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Tất cả
                                        bài viết</span></a><!--end:Menu link-->
                            </div>
                            <div class="menu-item">
                                <!--begin:Menu link--><a class="menu-link"
                                    href="{{ route('admin.blog-categories.index') }}"><span class="menu-bullet"><span
                                            class="bullet bullet-dot"></span></span><span class="menu-title">Danh
                                        mục</span></a><!--end:Menu link-->
                            </div>

                        </div>
                        <div class="menu-item">
                            <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.contacts.index') }}"
                                target="_blank"><span class="menu-icon"><i class="fa-solid fa-user fs-4"><span
                                            class="path1"></span><span class="path2"></span></i></span><span
                                    class="menu-title">Liên hệ </span></a><!--end:Menu link-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.shipping-fees.index') }}"
                            target="_blank"><span class="menu-icon"><i class="fa-solid fa-truck-fast fs-4"></i><span
                                    class="path1"></span><span class="path2"></span><span
                                    class="path3"></span><span class="path4"></span></i></span><span
                                class="menu-title">Phí
                                ship</span></a><!--end:Menu link-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Cài đặt hệ thống
                            </span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->

                    <div class="menu-item">
                        <!--begin:Menu link--><a class="menu-link" href="{{ route('admin.settings.index') }}"><span
                                class="menu-icon"><i class="fa-solid fa-gear fs-4"><span class="path1"></span><span
                                        class="path2"></span></i></span><span class="menu-title">Cài
                                đặt</span></a><!--end:Menu link-->
                    </div>
                    <!--end:Menu item--><!--begin:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
    <!--begin::Footer-->
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="https://preview.keenthemes.com/html/metronic/docs"
            class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100"
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
            title="200+ in-house components and 3rd-party plugins">
            <span class="btn-label">
                Docs & Components
            </span>

            <i class="ki-duotone ki-document btn-icon fs-2 m-0"><span class="path1"></span><span
                    class="path2"></span></i>
        </a>
    </div>
    <!--end::Footer-->
</div>
