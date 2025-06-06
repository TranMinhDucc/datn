<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from mironcoder-greeny.netlify.app/assets/client/ltr/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 May 2025 15:29:05 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>

<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="name" content="Greeny">
    <meta name="title" content="Greeny - eCommerce HTML Template">
    <meta name="keywords"
        content="organic, food, shop, ecommerce, store, html, bootstrap, template, agriculture, vegetables, webshop, farm, grocery, natural, online store">

    <title>@yield('title', 'Trang khách hàng')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/client/images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/client/fonts/flaticon/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/fonts/icofont/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/fonts/fontawesome/fontawesome.min.css') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/client/vendor/venobox/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/vendor/slickslider/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/vendor/niceselect/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/vendor/bootstrap/bootstrap.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/index.css') }}">


    <!-- Custom Faq -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/faq.css') }}">


    <!-- Custom Contact -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/contact.css') }}">

    <!-- Custom Policy -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/privacy.css') }}">

    <!-- Custom Profile -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/profile.css') }}">

    <!-- Custom Wallet -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/wallet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/user-auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/wallet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/product-details.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/product-details.css') }}">

    <!-- Toastr JS và CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


</head>

<body>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<script type='text/javascript'>
        toastr.error('" . addslashes($_SESSION['error']) . "');
    </script>";
        unset($_SESSION['error']); // Xóa để không hiển thị lại
    }

    if (isset($_SESSION['success'])) {
        echo "<script type='text/javascript'>
        toastr.success('" . addslashes($_SESSION['success']) . "');
    </script>";
        unset($_SESSION['success']); // Xóa để không hiển thị lại
    }
    ?>


    @include('layouts.partials.client.header')
    @include('layouts.partials.client.navbar')
    <script>
        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif

        @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
        @endif

        @if(session('info'))
        toastr.info("{{ session('info') }}");
        @endif
    </script>
    {{-- <main class="container mt-4">
        @include('layouts.partials.alert')
        @yield('content')
    </main> --}}
    @yield('content')
    @include('layouts.partials.client.footer')

    <!-- Vendor JS -->
    <script src="{{ asset('assets/client/vendor/bootstrap/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/countdown/countdown.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/niceselect/nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/slickslider/slick.min.js') }}"></script>
    <script src="{{ asset('assets/client/vendor/venobox/venobox.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/client/js/nice-select.js') }}"></script>
    <script src="{{ asset('assets/client/js/countdown.js') }}"></script>
    <script src="{{ asset('assets/client/js/accordion.js') }}"></script>
    <script src="{{ asset('assets/client/js/venobox.js') }}"></script>
    <script src="{{ asset('assets/client/js/slick.js') }}"></script>
    <script src="{{ asset('assets/client/js/main.js') }}"></script>
</body>

</html>