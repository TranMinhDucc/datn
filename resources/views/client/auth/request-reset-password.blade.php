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
<section class="user-form-part">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="user-form-logo">
                    <a href='{{ url('/') }}'><img src="{{ asset('assets/client/images/logo.png') }}" alt="logo"></a>
                </div>
                <div class="user-form-card">
                    <div class="user-form-title">
                        <h2>Worried?</h2>
                        <p>No Problem! Just Follow The Simple Way</p>
                    </div>


                    {{-- ✅ Form gửi email để nhận link --}}
                    <form class="user-form" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            @error('email')
                                <small class="text-danger d-block mt-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="form-button">
                            <button type="submit">Get reset link</button>
                        </div>
                    </form>
                </div>

                <div class="user-form-remind">
                    <p>Go Back To <a href="{{ route('login') }}">Login here</a></p>
                </div>

                <div class="user-form-footer">
                    <p>Greeny | &copy; Copyright by <a href="#">Mironcoder</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
