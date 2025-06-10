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
                            {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif --}}

                            <form method="POST" action="{{ route('register') }}" class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" name="username" type="text" placeholder="Tên đăng nhập"
                                            value="{{ old('username') }}">
                                        <label>Tên đăng nhập</label>
                                        @error('username')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
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
                                    <div class="form-floating">
                                        <input class="form-control" name="email" type="text" placeholder="Email"
                                            value="{{ old('email') }}">
                                        <label>Nhập email</label>
                                        @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" name="password" type="password" placeholder="Mật khẩu">
                                        <label>Nhập mật khẩu</label>
                                        @error('password')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" name="password_confirmation" type="password"
                                            placeholder="Nhập lại mật khẩu">
                                        <label>Nhập lại mật khẩu</label>
                                        @error('password_confirmation')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror

                                        {{-- Nếu lỗi xác nhận nằm trong 'password' (mặc định của Laravel) --}}
                                        @if ($errors->has('password') && str_contains($errors->first('password'), 'không khớp'))
                                            <div class="text-danger mt-1">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
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