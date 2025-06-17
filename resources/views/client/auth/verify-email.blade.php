@extends('layouts.client')

@section('title', 'Xác minh email')

@section('content')
    <section class="user-form-part py-5" style="min-height: 80vh; background-color: #fef2f2;">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="bg-white shadow rounded-4 p-4 p-md-5 text-center">

                        {{-- Cảnh báo: chấm đỏ --}}
                        <div class="mb-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/463/463612.png" alt="Cảnh báo"
                                style="width: 60px; height: 60px;">
                        </div>

                        {{-- Tiêu đề --}}
                        <h3 class="fw-semibold text-danger mb-2">Xác minh email cần thiết</h3>

                        {{-- Mô tả --}}
                        <p class="text-muted mb-4" style="font-size: 15px;">
                            Bạn cần xác minh địa chỉ email để tiếp tục sử dụng tài khoản.<br>
                            Một liên kết xác minh đã được gửi tới hộp thư của bạn.
                        </p>

                        {{-- Flash message nếu có --}}
                        @if (session('status'))
                            <div class="alert alert-success small">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Gửi lại xác minh --}}
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                Gửi lại email xác minh
                            </button>
                        </form>

                        {{-- Footer --}}
                        <div class="mt-4 small text-muted">
                            Katie &copy; {{ now()->year }} · Thiết kế bởi <a href="#"
                                class="text-danger text-decoration-none">KatieTeam</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection