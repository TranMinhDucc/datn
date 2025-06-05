@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('content')

<!--=====================================
                                                        USER FORM PART START
                                            =======================================-->

<section class="user-form-part">
    <script>
        window.addEventListener('pageshow', function(event) {
            // Kiểm tra nếu là phiên bản được cache (tức là quay lại bằng Back)
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-12 col-lg-12 col-xl-10">
                <div class="user-form-logo">
                    <a href='index.html'> <img src="{{ asset('assets/client/images/logo.png') }}" alt="logo"></a>
                </div>
                <div class="user-form-card">
                    <div class="user-form-title">
                        <h2>welcome!</h2>
                        <p>Use your credentials to access</p>
                    </div>
                    <div class="user-form-group">
                        <ul class="user-form-social">
                            <li><a href="#" class="facebook"><i class="fab fa-facebook-f"></i>login with facebook</a>
                            </li>
                            <li><a href="#" class="twitter"><i class="fab fa-twitter"></i>login with twitter</a></li>
                            <li><a href="#" class="google"><i class="fab fa-google"></i>login with google</a></li>
                            <li><a href="#" class="instagram"><i class="fab fa-instagram"></i>login with instagram</a>
                            </li>
                        </ul>
                        <div class="user-form-divider">
                            <p>or</p>
                        </div>
                        <form method="POST" action="{{ url('/login') }}" class="user-form">
                            @csrf

                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Nhập email hoặc username" value="{{ old('email') }}">
                                @error('email')
                                <small class="text-danger d-block mt-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Enter your password">
                                @error('password')
                                <small class="text-danger d-block mt-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="form-button">
                                <button type="submit">login</button>
                                <p>Forgot your password?<a href="{{route('client.auth.reset_password')}}">reset here</a></p>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="user-form-remind">
                    <p>Don't have any account?<a href="{{url('register')}}">register here</a></p>
                </div>
                <div class="user-form-footer">
                    <p>Greeny | &COPY; Copyright by <a href="#">Mironcoder</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection