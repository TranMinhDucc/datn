@extends('layouts.client')

@section('title', 'Quên mật khẩu')

@section('content')

    @if (session('status'))
        <script>
            toastr.success("{{ session('status') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <section class="section-b-space pt-0 login-bg-img">
        <div class="custom-container container login-page">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6">
                    <div class="log-in-box">
                        <div class="log-in-title text-center mb-4">
                            <h4>Khôi phục mật khẩu</h4>
                            <p>Nhập email đã đăng ký để nhận đường dẫn đặt lại mật khẩu</p>
                        </div>

                        <div class="login-box">
                            <form method="POST" action="{{ route('password.email') }}" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input class="form-control @error('email') is-invalid @enderror" name="email"
                                            type="email" placeholder="Nhập email" value="{{ old('email') }}" required>
                                        <label for="email">Email</label>
                                        @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <button class="btn login btn_black sm w-100" type="submit">
                                        Gửi đường dẫn khôi phục
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <p>Quay lại <a href="{{ route('login') }}" class="text-decoration-underline">Đăng nhập</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection