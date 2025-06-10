@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu')

@section('content')
    <section class="user-form-part py-5" style="background: #f9f9f9;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-10">
                    <div class="user-form-logo text-center mb-4">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/client/images/logo.png') }}" alt="logo" height="50">
                        </a>
                    </div>

                    <div class="card shadow-sm border-0 p-4">
                        <div class="text-center mb-3">
                            <h3 class="fw-bold">🔐 Reset Password</h3>
                            <p class="text-muted mb-0">Set your new secure password below</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Your email"
                                    value="{{ old('email') }}" required autofocus>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="New password"
                                    required>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Repeat new password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none small">← Quay lại trang đăng nhập</a>
                        </div>
                    </div>

                    <div class="text-center mt-4 text-muted small">
                        © {{ now()->year }} Greeny by <a href="#" class="text-decoration-none">Mironcoder</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection