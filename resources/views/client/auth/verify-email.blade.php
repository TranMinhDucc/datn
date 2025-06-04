@extends('layouts.client')

@section('title', 'Xác minh email')

@section('content')
<section class="user-form-part">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="user-form-card text-center p-5" style="max-width: 100%; border-radius: 8px;">
                    <div class="user-form-logo mb-4">
                        <img src="{{ asset('assets/client/images/logo.png') }}" alt="logo">
                    </div>

                    <h2 class="mb-3" style="color: #28a745; font-weight: bold;">Xác Minh Email</h2>
                    <p class="mb-4">Chúng tôi đã gửi một liên kết xác minh đến email của bạn.</p>

                    <form method="POST" action="{{ route('verification.send') }}" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-success px-4 py-2">
                            GỬI LẠI EMAIL XÁC MINH
                        </button>
                    </form>


                <div class="user-form-footer mt-3 text-center">
                    <p>Greeny | &copy; Copyright by <a href="#">Mironcoder</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
