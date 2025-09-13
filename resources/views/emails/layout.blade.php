<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Katie Shop | @yield('title', 'Thông báo từ hệ thống')</title>
</head>

<body style="margin:0; padding:0; background-color:#f5f5f5; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;">

    <div style="max-width:600px; margin:0 auto; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

        {{-- HEADER --}}
        <div style="background-image: url('https://themes.pixelstrap.net/katie/assets/images/mega-menu/banner.png'); background-size: cover; background-position: center; padding: 40px 20px; text-align: center; color: #ffffff;">
            <div style="background-color: rgba(167, 167, 167, 0.5); padding: 30px; border-radius: 8px; display: inline-block;">
                <img src="https://yourdomain.com/logo.png" alt="Katie Shop" width="120" style="margin-bottom: 10px;">
                <h1 style="margin: 0; font-size: 24px;">Katie Shop</h1>
                <p style="margin: 5px 0 0; font-size: 14px;">Thời trang thanh lịch & hiện đại</p>
            </div>
        </div>

        {{-- BODY --}}
        <div style="padding: 30px;">
            @yield('content')
        </div>

        {{-- FOOTER --}}
        <div style="background-color:#f1f1f1; padding:20px; text-align:center; font-size:12px; color:#888;">
            <p>© 2025 Katie Shop. All rights reserved.</p>
            <p>Hotline: 0123 456 789 | Email: <a href="mailto:support@katie.vn" style="color:#f57ea4;">support@katie.vn</a></p>
            <p><a href="https://katie.vn" style="color:#888;">Chính sách bảo mật</a> | <a href="https://katie.vn" style="color:#888;">Điều khoản dịch vụ</a></p>
        </div>
    </div>

</body>
</html>
