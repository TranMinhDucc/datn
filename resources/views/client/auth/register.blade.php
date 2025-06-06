@extends('layouts.client')

@section('title', 'Đăng kí')

@section('content')
<!--=====================================
                                    USER FORM PART START
                        =======================================-->
<section class="user-form-part">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-12 col-lg-12 col-xl-10">
                <div class="user-form-logo">
                    <a href='index.html'><img src="{{ asset('assets/client/images/logo.png') }}" alt="logo"></a>
                </div>
                <div class="user-form-card">
                    <div class="user-form-title">
                        <h2>Join Now!</h2>
                        <p>Setup A New Account In A Minute</p>
                    </div>
                    <div class="user-form-group">
                        <ul class="user-form-social">
                            <li><a href="#" class="facebook"><i class="fab fa-facebook-f"></i>Join with facebook</a>
                            </li>
                            <li><a href="#" class="twitter"><i class="fab fa-twitter"></i>Join with twitter</a></li>
                            <li><a href="#" class="google"><i class="fab fa-google"></i>Join with google</a></li>
                            <li><a href="#" class="instagram"><i class="fab fa-instagram"></i>Join with instagram</a>
                            </li>
                        </ul>
                        <div class="user-form-divider">
                            <p>or</p>
                        </div>
                        <form method="POST" action="{{ url('register') }}" class="user-form">
                            @csrf

                            <div class="form-group">
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Enter your username">
                                @error('username')
                                <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" name="fullname" value="{{ old('fullname') }}" class="form-control" placeholder="Enter your full name">
                                @error('fullname')
                                <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email">
                                @error('email')
                                <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Enter your password">
                                @error('password')
                                <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Enter repeat password">
                                @error('password_confirmation')
                                <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="check">
                                <label class="form-check-label" for="check">Accept all the <a href="#">Terms & Conditions</a></label>
                            </div>

                            <div class="form-button">
                                <button type="submit">register</button>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="user-form-remind">
                    <p>Already Have An Account?<a href="{{url('login')}}">login here</a></p>
                </div>
                <div class="user-form-footer">
                    <p>Greeny | &COPY; Copyright by <a href="#">Mironcoder</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection