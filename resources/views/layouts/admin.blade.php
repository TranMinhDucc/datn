<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Trang quản trị')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/assets/media/logos/favicon.ico') }}">

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/admin/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/admin/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->

    <!-- Global Stylesheets -->
    <link href="{{ asset('assets/admin/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/admin/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }            
    </script>
    <!--end::Theme mode setup on page load-->


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <!-- Header -->
            @include('layouts.partials.admin.header')
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <!--begin::Sidebar-->
                @include('layouts.partials.admin.sidebar')
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        @yield('content')
                    </div>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    @include('layouts.partials.admin.footer')
                    <!--end::Footer-->
                </div>

            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/index.html";        </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{asset('assets/admin/assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{asset('assets/admin/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/index.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/xy.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/percent.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/radar.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/themes/Animated.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/map.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/geodata/worldLow.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/geodata/continentsLow.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/geodata/usaLow.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js')}}"></script>
    <script src="{{asset('assets/admin/assets/cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js')}}"></script>
    <script src="{{asset('assets/admin/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{asset('assets/admin/assets/js/widgets.bundle.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/custom/widgets.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/custom/apps/chat/chat.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/custom/utilities/modals/create-app.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/custom/utilities/modals/new-target.js')}}"></script>
    <script src="{{asset('assets/admin/assets/js/custom/utilities/modals/users-search.js')}}"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>