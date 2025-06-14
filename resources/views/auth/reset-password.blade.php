@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu')

@section('content')
    <section class="user-form-part py-5" style="background: #f9f9f9;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-10">
                    <div class="user-form-logo text-center mb-4">
                        {{-- <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/client/images/logo.png') }}" alt="logo" height="50">
                        </a> --}}
                    </div>

                    <div class="card shadow-sm border-0 p-4">
                        <div class="text-center mb-3">
                            <h3 class="fw-bold">🔐 Thay đổi mật khẩu</h3>
                            <p class="text-muted mb-0">Đặt mật khẩu an toàn mới của bạn bên dưới</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Nhập email của bạn"
                                    value="{{ old('email') }}" autofocus>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label class="form-label">Nhập mật khẩu mới</label>
                                <div class="position-relative">
                                    <input type="password" id="password" name="password" class="form-control pe-5"
                                        placeholder="Nhập mật khẩu mới">
                                    <i class="fa-solid fa-eye toggle-password" toggle="#password"></i>
                                </div>
                                @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-3">
                                <label class="form-label">Nhập lại mật khẩu</label>
                                <div class="position-relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control pe-5" placeholder="Nhập lại mật khẩu mới">
                                    <i class="fa-solid fa-eye toggle-password" toggle="#password_confirmation"></i>
                                </div>
                                @error('password_confirmation')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
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

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .position-relative {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1rem;
            z-index: 10;
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
                    const input = document.querySelector(this.getAttribute('toggle'));
                    const isPassword = input.getAttribute('type') === 'password';

                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });

                if (!eye.classList.contains('fa-eye') && !eye.classList.contains('fa-eye-slash')) {
                    eye.classList.add('fa-eye');
                }
            });
        });
    </script>
@endsection