<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from mironcoder-greeny.netlify.app/assets/ltr/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 May 2025 15:29:05 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="name" content="Greeny">
    <meta name="title" content="Greeny - eCommerce HTML Template">
    <meta name="keywords" content="organic, food, shop, ecommerce, store, html, bootstrap, template, agriculture, vegetables, webshop, farm, grocery, natural, online store">

    <title>@yield('title', 'Trang khách hàng')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/flaticon/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/icofont/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/fontawesome.min.css') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/venobox/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/slickslider/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/niceselect/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
</head>

<body>

    @include('layouts.partials.client.header')
 @include('layouts.partials.client.navbar')
    {{-- <main class="container mt-4">
        @include('layouts.partials.alert')
        @yield('content')
    </main> --}}
@yield('content')
    @include('layouts.partials.client.footer')

          <!-- Vendor JS -->
<script src="{{ asset('assets/vendor/bootstrap/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/vendor/countdown/countdown.min.js') }}"></script>
<script src="{{ asset('assets/vendor/niceselect/nice-select.min.js') }}"></script>
<script src="{{ asset('assets/vendor/slickslider/slick.min.js') }}"></script>
<script src="{{ asset('assets/vendor/venobox/venobox.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/nice-select.js') }}"></script>
<script src="{{ asset('assets/js/countdown.js') }}"></script>
<script src="{{ asset('assets/js/accordion.js') }}"></script>
<script src="{{ asset('assets/js/venobox.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
