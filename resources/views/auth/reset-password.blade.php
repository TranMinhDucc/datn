@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu')

@section('content')
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
                        <div class="log-in-title text-center mb-4">
                            <h4><i class="fa-solid fa-unlock-keyhole me-2 text-black"></i> Đặt lại mật khẩu</h4>

                            <p>Hãy nhập mật khẩu mới của bạn bên dưới</p>
                        </div>

                        <div class="login-box">
                            <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                                {{-- Email --}}
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" name="email" type="text" placeholder="Email"
                                            value="{{ old('email') }}">
                                        <label>Email</label>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Mật khẩu mới --}}
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" id="password" name="password" type="password"
                                            placeholder="Mật khẩu mới">
                                        <label>Mật khẩu mới</label>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nhập lại mật khẩu --}}
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control" id="password_confirmation" name="password_confirmation"
                                            type="password" placeholder="Nhập lại mật khẩu">
                                        <label>Nhập lại mật khẩu</label>
                                        @error('password_confirmation')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn login btn_black sm w-100" type="submit">
                                        <i class="fa-solid fa-key me-1"></i> Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none small">← Quay lại đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-control').forEach(function (input) {
                input.addEventListener('input', function () {
                    this.classList.remove('is-invalid');
                });
            });
        });
    </script>
@endsection