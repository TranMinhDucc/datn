@extends('layouts.client')

@section('title', 'Đăng kí')

@section('content')

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Sign Up</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Sign Up</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space pt-0 login-bg-img">
        <div class="custom-container container login-page">
            <div class="row align-items-center">
                <div class="col-xxl-7 col-6 d-none d-lg-block">
                    <div class="login-img">
                        <img class="img-fluid" src="https://themes.pixelstrap.net/katie/assets/images/login/1.svg" alt="">
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-6 mx-auto">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h4>Chào mừng đến với Katie</h4>
                            <p>Tạo tài khoản</p>
                        </div>
                        <div class="login-box">
                            <form method="POST" action="{{ route('register') }}" class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <div class="form-floating">
                                        <input class="form-control" name="username" type="text" placeholder="Tên đăng nhập"
                                            value="{{ old('username') }}">
                                        <label>Tên đăng nhập (chỉ chữ, số, dấu _)</label>
                                        @error('username')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Họ và tên</label>
                                    <div class="form-floating">
                                        <input class="form-control" name="fullname" type="text" placeholder="Nhập họ tên"
                                            value="{{ old('fullname') }}">
                                        <label>Nhập họ tên</label>
                                        @error('fullname')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <div class="form-floating">
                                        <input class="form-control" name="email" type="text" placeholder="Email"
                                            value="{{ old('email') }}">
                                        <label>Nhập email</label>
                                        @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mật khẩu -->
                                <div class="col-12">
                                    <label class="form-label">Mật khẩu</label>
                                    <div class="position-relative">
                                        <input class="form-control pe-5" type="password" name="password" id="password"
                                            placeholder="Nhập mật khẩu">
                                        <i class="fa fa-eye toggle-password" toggle="#password"></i>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nhập lại mật khẩu -->
                                <div class="col-12">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <div class="position-relative">
                                        <input class="form-control pe-5" type="password" name="password_confirmation"
                                            id="password_confirmation" placeholder="Nhập lại mật khẩu">
                                        <i class="fa fa-eye toggle-password" toggle="#password_confirmation"></i>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror

                                    @if ($errors->has('password') && str_contains($errors->first('password'), 'không khớp'))
                                        <div class="text-danger mt-1">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>



                                <div class="col-12">
                                    <div class="forgot-box">
                                        <div>
                                            <input class="custom-checkbox me-2" id="category1" type="checkbox">
                                            <label for="category1">Tôi đồng ý với <span>Điều khoản</span> và <span>Quyền
                                                    riêng tư</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn login btn_black sm" type="submit">Sign Up</button>
                                </div>
                            </form>
                        </div>

                        <div class="other-log-in">
                            <h6>OR</h6>
                        </div>
                        <div class="log-in-button">
                            <ul>
                                <li><a href="https://www.google.com/" target="_blank"><i
                                            class="fa-brands fa-google me-2"></i>Google</a></li>
                                <li><a href="https://www.facebook.com/" target="_blank"><i
                                            class="fa-brands fa-facebook-f me-2"></i>Facebook</a></li>
                            </ul>
                        </div>

                        <div class="sign-up-box">
                            <p>Bạn đã có tài khoản?</p><a href="{{ route('login') }}">Đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            z-index: 10;
        }

        .form-control {
            padding: 15px;
        }

        .form-control.pe-5 {
            padding-right: 2.5rem !important;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-password').forEach(function (eye) {
                eye.addEventListener('click', function () {
                    const input = document.querySelector(eye.getAttribute('toggle'));
                    const isPassword = input.getAttribute('type') === 'password';

                    input.setAttribute('type', isPassword ? 'text' : 'password');

                    // Đổi icon từ fa-eye -> fa-eye-slash và ngược lại
                    eye.classList.toggle('fa-eye');
                    eye.classList.toggle('fa-eye-slash');
                });

                // Đặt icon mặc định là fa-eye nếu chưa có
                if (!eye.classList.contains('fa-eye') && !eye.classList.contains('fa-eye-slash')) {
                    eye.classList.add('fa-eye');
                }
            });
        });
    </script>
@endsection