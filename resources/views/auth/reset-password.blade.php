@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<section class="user-form-part">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="user-form-logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/client/images/logo.png') }}" alt="logo"></a>
                </div>
                <div class="user-form-card">
                    <div class="user-form-title">
                        <h2>Any issue?</h2>
                        <p>Set your new secure password below</p>
                    </div>

                    {{-- ✅ FORM RESET PASSWORD --}}
                    <form class="user-form" method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- Laravel yêu cầu truyền token --}}
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Your email" value="{{ old('email') }}" required autofocus>
                            @error('email')<small class="text-danger d-block">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="New password" required>
                            @error('password')<small class="text-danger d-block">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required>
                        </div>

                        <div class="form-button">
                            <button type="submit">Change Password</button>
                        </div>
                    </form>
                </div>

                <div class="user-form-remind">
                    <p>Go Back To <a href="{{ route('login') }}">Login here</a></p>
                </div>

                <div class="user-form-footer">
                    <p>Greeny | &COPY; Copyright by <a href="#">Mironcoder</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
