<div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">      
<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">


    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">

      <a href="{{ route('admin.dashboard') }}">
    <img alt="Logo" src="{{ asset('assets/admin/assets/media/logos/default-dark.svg') }}"
         class="h-25px app-sidebar-logo-default" />

    <img alt="Logo" src="{{ asset('assets/admin/assets/media/logos/default-small.svg') }}"
         class="h-20px app-sidebar-logo-minimize" />
</a>
        <!--end::Logo image-->


        <!--begin::Sidebar toggle-->
        <!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->

        <!--end::Sidebar toggle-->
    </div>
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
 <div class="menu-item">
    <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="fa-solid fa-gauge me-2"></i>
        <span class="menu-title">Dashboard</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.banners.index') }}" class="menu-link">
        <i class="fa-solid fa-image me-2"></i>
        <span class="menu-title">Banner</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.users.index') }}" class="menu-link">
        <i class="fa-solid fa-user me-2"></i>
        <span class="menu-title">User</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.categories.index') }}" class="menu-link">
        <i class="fa-solid fa-layer-group me-2"></i>
        <span class="menu-title">Category</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.products.index') }}" class="menu-link">
        <i class="fa-solid fa-box-open me-2"></i>
        <span class="menu-title">Product</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.posts.index') }}" class="menu-link">
        <i class="fa-solid fa-newspaper me-2"></i>
        <span class="menu-title">Post</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.statuses.index') }}" class="menu-link">
        <i class="fa-solid fa-toggle-on me-2"></i>
        <span class="menu-title">Trạng thái</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.orders.index') }}" class="menu-link">
        <i class="fa-solid fa-receipt me-2"></i>
        <span class="menu-title">Đơn hàng</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.payment_banks.index') }}" class="menu-link">
        <i class="fa-solid fa-credit-card me-2"></i>
        <span class="menu-title">Thanh toán</span>
    </a>
</div>

<div class="menu-item">
    <a href="{{ route('admin.reviews.index') }}" class="menu-link">
        <i class="fa-solid fa-star me-2"></i>
        <span class="menu-title">Reviews</span>
    </a>
</div>

              
               
                <div class="menu-item"><!--begin:Menu link--><a class="menu-link"
                        {{-- href="https://preview.keenthemes.com/html/metronic/docs/base/utilities" --}}
                        target="_blank"><span class="menu-icon"><span
                                    class="path1"></span><span class="path2"></span></i></span><span
                            class="menu-title"></span></a><!--end:Menu link--></div>
                <!--end:Menu item--><!--begin:Menu item-->
                <div class="menu-item"><!--begin:Menu link--><a class="menu-link"
                        {{-- href="https://preview.keenthemes.com/html/metronic/docs" target="_blank"><span --}}
                            class="menu-icon"><span
                                    class="path1"></span><span class="path2"></span></i></span><span
                            class="menu-title"></span></a><!--end:Menu link--></div>
                <!--end:Menu item--><!--begin:Menu item-->
                <div class="menu-item"><!--begin:Menu link--><a class="menu-link"
                        {{-- href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog" --}}
                        target="_blank"><span class="menu-icon"><span

