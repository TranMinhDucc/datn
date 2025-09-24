@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('content')

    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Login</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                            <li class="breadcrumb-item active"> <a href="#">Login</a></li>
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
                    <div class="login-img"> <img class="img-fluid"
                            src="https://themes.pixelstrap.net/katie/assets/images/login/1.svg" alt=""></div>
                </div>
                <div class="col-xxl-4 col-lg-6 mx-auto">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h4>Chào mừng đến với katie</h4>
                            <p>Register Your Account</p>
                        </div>
                        <div class="login-box">
                            @push('alert')
                                <script>
                                    @if (session('success'))
                                        Swal.fire({
                                            icon: 'success',
                                            title: '🎉 {{ session('success') }}',
                                            showConfirmButton: false,
                                            timer: 2500,
                                            timerProgressBar: true
                                        });
                                    @endif
                                </script>
                            @endpush



                            <form method="POST" action="{{ route('login') }}" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" id="floatingInputValue" name="login" type="text"
                                            placeholder="Email hoặc tên đăng nhập" value="{{ old('login') }}">
                                        <label for="floatingInputValue">Email hoặc tên đăng nhập</label>
                                        @error('login')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" id="floatingInputValue1" name="password" type="password"
                                            placeholder="Password">
                                        <label for="floatingInputValue1">Mật khẩu</label>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="forgot-box">
                                        {{-- <div>
                                            <input class="custom-checkbox me-2" id="remember" type="checkbox"
                                                name="remember">
                                            <label for="remember">Remember me</label>
                                        </div> --}}
                                        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn login btn_black sm" type="submit">Đăng nhập</button>
                                </div>
                            </form>

                        </div>
                        {{-- <div class="other-log-in">
                            <h6>HOẶC</h6>
                        </div>
                        <div class="log-in-button">
                            <ul>
                                <li> <a href="https://www.google.com/" target="_blank"> <i class="fa-brands fa-google me-2">
                                        </i>Google</a></li>
                                <li> <a href="https://www.facebook.com/" target="_blank"><i
                                            class="fa-brands fa-facebook-f me-2"></i>Facebook </a></li>
                            </ul>
                        </div>
                        <div class="other-log-in"></div> --}}
                        <div class="sign-up-box">
                            <p>Bạn chưa có tài khoản?</p><a href="{{ route('register') }}">Đăng ký</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection